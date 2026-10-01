<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\EventDay;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AttendanceService
{
    /**
     * Memproses simpan/update massal presensi mahasiswa per gugus dan sesi acara.
     */
    public function saveBulkAttendance(int $eventDayId, int $groupId, array $attendancesData, User $currentUser): int
    {
        return DB::transaction(function () use ($eventDayId, $groupId, $attendancesData, $currentUser) {
            $updatedCount = 0;

            foreach ($attendancesData as $studentId => $item) {
                $status = $item['status'] ?? 'alpa';
                $notes = $item['notes'] ?? null;
                $proofFile = $item['proof_file'] ?? null;

                $proofPath = null;
                if ($proofFile && $proofFile->isValid()) {
                    $proofPath = $proofFile->store('attendance_proofs', 'public');
                }

                $updateData = [
                    'recorded_by_user_id' => $currentUser->id,
                    'status' => $status,
                    'notes' => $notes,
                ];

                if ($proofPath) {
                    $updateData['proof_file_path'] = $proofPath;
                }

                Attendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'event_day_id' => $eventDayId,
                    ],
                    $updateData
                );

                $updatedCount++;
            }

            return $updatedCount;
        });
    }

    /**
     * Update presensi tunggal oleh Admin (misal untuk dispensasi/koreksi).
     */
    public function updateSingleAttendance(Attendance $attendance, array $data, User $currentUser): Attendance
    {
        if (isset($data['proof_file']) && $data['proof_file']->isValid()) {
            // Hapus file lama jika ada
            if ($attendance->proof_file_path && Storage::disk('public')->exists($attendance->proof_file_path)) {
                Storage::disk('public')->delete($attendance->proof_file_path);
            }
            $data['proof_file_path'] = $data['proof_file']->store('attendance_proofs', 'public');
            unset($data['proof_file']);
        }

        $data['recorded_by_user_id'] = $currentUser->id;
        $attendance->update($data);

        return $attendance;
    }
}
