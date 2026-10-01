<?php

namespace App\Http\Controllers;

use App\Models\CommitteeAttendance;
use App\Models\CommitteeSection;
use App\Models\EventDay;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicPanitiaAttendanceController extends Controller
{
    /**
     * Tampilkan Halaman Presensi Mandiri Panitia
     */
    public function index(): View
    {
        $committeeSections = CommitteeSection::orderBy('name', 'asc')->get();
        $activeSession = EventDay::where('is_active', true)->first();

        return view('presentPanitia', compact('committeeSections', 'activeSession'));
    }

    /**
     * Proses Simpan Presensi Panitia (AJAX / Form Submit)
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'nim' => ['nullable', 'string', 'max:30'],
            'phone' => ['nullable', 'string', 'max:20'],
            'committee_section_id' => ['required', 'exists:committee_sections,id'],
        ], [
            'name.required' => 'Nama lengkap panitia wajib diisi.',
            'committee_section_id.required' => 'Seksi / Divisi Panitia wajib dipilih.',
            'committee_section_id.exists' => 'Seksi panitia yang dipilih tidak valid.',
        ]);

        // 1. Cari sesi aktif saat ini (jika ada)
        $activeSession = EventDay::where('is_active', true)->first();
        $eventDayId = $activeSession ? $activeSession->id : null;

        $name = trim($validated['name']);
        $nim = !empty($validated['nim']) ? trim($validated['nim']) : null;
        $sectionId = $validated['committee_section_id'];

        // 2. Cek apakah Panitia dengan NIM atau Nama ini sudah pernah mengisi absensi pada sesi/hari ini di database
        $query = CommitteeAttendance::query();

        if ($eventDayId) {
            $query->where('event_day_id', $eventDayId);
        } else {
            $query->whereDate('created_at', now()->toDateString());
        }

        $query->where(function ($q) use ($name, $nim) {
            $q->where('name', $name);
            if ($nim) {
                $q->orWhere('nim', $nim);
            }
        });

        $existingAttendance = $query->first();

        if ($existingAttendance) {
            $sessionTitle = $activeSession ? $activeSession->full_session_title : 'hari ini';
            $matchedName = $existingAttendance->name;
            return response()->json([
                'success' => false,
                'already_submitted' => true,
                'message' => "Panitia ({$matchedName}) sudah berhasil mengisi absensi panitia. Anda tidak dapat mengisi absensi kembali.",
                'committee_name' => $matchedName,
                'session_name' => $sessionTitle,
            ], 422);
        }

        // 3. Catat presensi panitia
        CommitteeAttendance::create([
            'name' => $name,
            'nim' => !empty($validated['nim']) ? trim($validated['nim']) : null,
            'phone' => !empty($validated['phone']) ? trim($validated['phone']) : null,
            'committee_section_id' => $sectionId,
            'event_day_id' => $eventDayId,
            'notes' => 'Presensi Mandiri Panitia via Portal Web',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih telah mengisi absensi Panitia PKKMB 2026!',
            'committee_name' => $name,
            'session_name' => $activeSession ? $activeSession->full_session_title : 'Presensi Harian',
        ]);
    }
}
