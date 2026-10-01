<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\EventDay;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicAttendanceController extends Controller
{
    /**
     * Tampilkan Halaman Presensi Mandiri Mahasiswa Baru
     */
    public function index(): View
    {
        $groups = Group::orderBy('name', 'asc')->get();
        $activeSession = EventDay::where('is_active', true)->first();

        return view('present', compact('groups', 'activeSession'));
    }

    /**
     * Proses Simpan Absensi Mahasiswa Baru (AJAX / Form Submit)
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nim' => ['nullable', 'string', 'max:30'],
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20'],
            'study_program' => ['required', 'string', 'max:100'],
            'group_id' => ['nullable', 'exists:groups,id'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'study_program.required' => 'Jurusan / Program Studi wajib diisi.',
            'group_id.exists' => 'Gugus yang dipilih tidak valid.',
        ]);

        // 1. Cari sesi aktif saat ini
        $activeSession = EventDay::where('is_active', true)->first();
        if (!$activeSession) {
            return response()->json([
                'success' => false,
                'no_active_session' => true,
                'message' => 'Mohon maaf, sesi presensi saat ini sudah habis atau belum dibuka oleh panitia. Silakan tunggu instruksi selanjutnya.',
            ], 422);
        }

        $name = trim($validated['name']);
        $nim = !empty($validated['nim']) ? trim($validated['nim']) : null;

        // 2. Cek apakah Mahasiswa ini sudah pernah mengisi absensi pada sesi aktif ini di database
        $studentQuery = Student::query();
        $studentQuery->where(function ($q) use ($name, $nim) {
            $q->where('name', $name);
            if ($nim) {
                $q->orWhere('nim', $nim);
            }
        });

        $existingStudent = $studentQuery->first();

        if ($existingStudent) {
            $existingAttendance = Attendance::where('student_id', $existingStudent->id)
                ->where('event_day_id', $activeSession->id)
                ->first();

            if ($existingAttendance) {
                return response()->json([
                    'success' => false,
                    'already_submitted' => true,
                    'message' => "Mahasiswa ({$existingStudent->name}) sudah berhasil mengisi absensi. Anda tidak dapat mengisi absensi kembali pada sesi yang sama.",
                    'student_name' => $existingStudent->name,
                    'session_name' => $activeSession->full_session_title,
                ], 422);
            }

            // Update data mahasiswa
            $existingStudent->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'] ?? $existingStudent->phone,
                'study_program' => $validated['study_program'],
                'group_id' => !empty($validated['group_id']) ? $validated['group_id'] : $existingStudent->group_id,
            ]);
            $student = $existingStudent;
        } else {
            // Buat mahasiswa baru
            $student = Student::create([
                'nim' => $nim,
                'name' => $validated['name'],
                'phone' => $validated['phone'] ?? null,
                'study_program' => $validated['study_program'],
                'group_id' => !empty($validated['group_id']) ? $validated['group_id'] : null,
            ]);
        }

        // 4. Catat presensi 'hadir'
        Attendance::create([
            'student_id' => $student->id,
            'event_day_id' => $activeSession->id,
            'status' => 'hadir',
            'notes' => 'Presensi Mandiri via Portal Web',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih telah mengisi absensi PKKMB 2026!',
            'student_name' => $student->name,
            'session_name' => $activeSession->full_session_title,
        ]);
    }
}
