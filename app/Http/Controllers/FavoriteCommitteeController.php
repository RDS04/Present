<?php

namespace App\Http\Controllers;

use App\Models\FavoriteCommitteeCandidate;
use App\Models\FavoriteCommitteeVote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FavoriteCommitteeController extends Controller
{
    /**
     * Tampilan Public Voting Kakak Panitia Terfavorit
     */
    public function publicView(Request $request): View
    {
        $candidates = FavoriteCommitteeCandidate::orderBy('votes_count', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        // Ambil voter identifier dari Cookie
        $voterUuid = $request->cookie('voter_uuid');
        $cookieVotedId = $request->cookie('voted_candidate_id');

        $voterIdentifier = $voterUuid ? md5('voter_' . $voterUuid) : null;

        $hasVoted = false;
        $votedCandidateId = null;
        $votedCandidate = null;

        if ($cookieVotedId) {
            $hasVoted = true;
            $votedCandidateId = (int) $cookieVotedId;
        } elseif ($voterIdentifier) {
            $voteRecord = FavoriteCommitteeVote::where('voter_identifier', $voterIdentifier)->first();
            if ($voteRecord) {
                $hasVoted = true;
                $votedCandidateId = $voteRecord->candidate_id;
            }
        }

        if ($votedCandidateId) {
            $votedCandidate = $candidates->firstWhere('id', $votedCandidateId);
        }

        // Ambil daftar unik seksi panitia untuk filter frontend
        $sections = $candidates->pluck('section')->filter()->unique()->values();

        // Hitung total votes untuk persentase
        $totalVotes = $candidates->sum('votes_count');

        return view('votingPanitia', compact(
            'candidates',
            'hasVoted',
            'votedCandidateId',
            'votedCandidate',
            'sections',
            'totalVotes'
        ));
    }

    /**
     * Eksekusi Simpan Vote (AJAX)
     */
    public function vote(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'candidate_id' => ['required', 'exists:favorite_committee_candidates,id'],
            'device_uuid' => ['nullable', 'string'],
        ], [
            'candidate_id.required' => 'Pilih salah satu kandidat panitia.',
            'candidate_id.exists' => 'Kandidat panitia tidak ditemukan.',
        ]);

        $candidateId = (int) $validated['candidate_id'];
        $ip = $request->ip();
        $userAgent = $request->userAgent();

        // Ambil UUID dari cookie atau payload request, atau buat baru
        $voterUuid = $request->cookie('voter_uuid') ?? $request->input('device_uuid') ?? Str::uuid()->toString();
        $voterIdentifier = md5('voter_' . $voterUuid);

        // 1. Cek apakah sudah pernah voting via cookie
        if ($request->cookie('voted_candidate_id')) {
            $existingId = (int) $request->cookie('voted_candidate_id');
            $existingCandidate = FavoriteCommitteeCandidate::find($existingId);
            $cName = $existingCandidate ? $existingCandidate->name : 'kandidat lain';

            return response()->json([
                'success' => false,
                'already_voted' => true,
                'message' => "Anda sudah melakukan voting sebelumnya! Setiap perangkat hanya diperbolehkan memilih 1 kali.",
                'voted_candidate_id' => $existingId,
            ], 422);
        }

        // 2. Cek apakah voter_identifier sudah ada di database
        $existingVote = FavoriteCommitteeVote::where('voter_identifier', $voterIdentifier)->first();

        if ($existingVote) {
            return response()->json([
                'success' => false,
                'already_voted' => true,
                'message' => "Perangkat Anda sudah digunakan untuk melakukan voting. Anda tidak dapat memilih kembali.",
                'voted_candidate_id' => $existingVote->candidate_id,
            ], 422)->cookie('voted_candidate_id', $existingVote->candidate_id, 525600);
        }

        // 3. Simpan vote secara atomic / transaction
        try {
            DB::transaction(function () use ($candidateId, $voterIdentifier, $ip, $userAgent, &$candidate) {
                $candidate = FavoriteCommitteeCandidate::lockForUpdate()->findOrFail($candidateId);

                FavoriteCommitteeVote::create([
                    'candidate_id' => $candidate->id,
                    'voter_identifier' => $voterIdentifier,
                    'ip_address' => $ip,
                    'user_agent' => $userAgent,
                ]);

                $candidate->increment('votes_count');
            });

            // Set cookie 1 tahun agar voting tersimpan di browser
            $cookieVoted = cookie('voted_candidate_id', $candidate->id, 525600);
            $cookieUuid = cookie('voter_uuid', $voterUuid, 525600);

            return response()->json([
                'success' => true,
                'message' => "Suara Anda untuk Kakak {$candidate->name} berhasil disimpan! Terima kasih telah berpartisipasi.",
                'voted_candidate_id' => $candidate->id,
                'candidate_name' => $candidate->name,
                'votes_count' => $candidate->fresh()->votes_count,
            ])->cookie($cookieVoted)->cookie($cookieUuid);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses voting Anda. Silakan coba beberapa saat lagi.',
            ], 500);
        }
    }

    /**
     * Halaman Admin Kelola Nominasi Panitia Terfavorit
     */
    public function adminIndex(): View
    {
        $candidates = FavoriteCommitteeCandidate::orderBy('votes_count', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        $totalVotes = $candidates->sum('votes_count');
        $totalCandidates = $candidates->count();
        $topCandidate = $candidates->first();

        return view('admin.favorite_candidates.index', compact(
            'candidates',
            'totalVotes',
            'totalCandidates',
            'topCandidate'
        ));
    }

    /**
     * Admin Simpan/Upload Kandidat Baru (Foto + Data)
     */
    public function storeCandidate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'section' => ['required', 'string', 'max:100'],
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:3072'], // Max 3MB
            'description' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama Kakak Panitia wajib diisi.',
            'section.required' => 'Seksi / Divisi Panitia wajib diisi.',
            'photo.required' => 'Foto Panitia wajib diunggah.',
            'photo.image' => 'File harus berupa gambar (JPG, PNG, WEBP, GIF).',
            'photo.max' => 'Ukuran foto maksimal 3 MB.',
        ]);

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'panitia_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();

            // Pastikan folder public/uploads/candidates ada
            $uploadPath = public_path('uploads/candidates');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $file->move($uploadPath, $filename);
            $photoPath = 'uploads/candidates/' . $filename;
        }

        FavoriteCommitteeCandidate::create([
            'name' => trim($validated['name']),
            'section' => trim($validated['section']),
            'photo' => $photoPath,
            'description' => isset($validated['description']) ? trim($validated['description']) : null,
            'votes_count' => 0,
        ]);

        return redirect()->route('favorite-candidates.index')
            ->with('success', "Kandidat Kakak Panitia Terfavorit ({$validated['name']}) berhasil ditambahkan!");
    }

    /**
     * Admin Hapus Kandidat
     */
    public function destroyCandidate(FavoriteCommitteeCandidate $candidate): RedirectResponse
    {
        $name = $candidate->name;

        // Hapus file foto jika ada di server
        if ($candidate->photo && file_exists(public_path($candidate->photo))) {
            @unlink(public_path($candidate->photo));
        }

        $candidate->delete();

        return redirect()->route('favorite-candidates.index')
            ->with('success', "Kandidat ({$name}) beserta data votingnya berhasil dihapus.");
    }

    /**
     * Admin Reset Hasil Voting
     */
    public function resetVotes(): RedirectResponse
    {
        DB::transaction(function () {
            FavoriteCommitteeVote::truncate();
            FavoriteCommitteeCandidate::query()->update(['votes_count' => 0]);
        });

        return redirect()->route('favorite-candidates.index')
            ->with('success', 'Semua perolehan suara voting Panitia Terfavorit telah berhasil direset ke 0!');
    }
}
