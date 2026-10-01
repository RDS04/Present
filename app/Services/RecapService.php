<?php

namespace App\Services;

use App\Models\EventDay;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RecapService
{
    /**
     * Ambil data rekapitulasi kehadiran dan status kelulusan mahasiswa baru.
     * Menggunakan Eager Loading untuk mencegah masalah N+1 Query.
     */
    public function getRecapData(?int $groupId = null, ?string $search = null, ?string $statusFilter = null): array
    {
        $totalSessions = EventDay::where('is_active', true)->count();
        if ($totalSessions === 0) {
            $totalSessions = EventDay::count(); // Fallback jika tidak ada flag active khusus
        }

        $query = Student::with(['group', 'attendances']);

        if ($groupId) {
            $query->where('group_id', $groupId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('study_program', 'like', "%{$search}%");
            });
        }

        $students = $query->get();

        $recapList = $students->map(function (Student $student) use ($totalSessions) {
            $attendances = $student->attendances;

            $hadirCount = $attendances->where('status', 'hadir')->count();
            $izinCount  = $attendances->where('status', 'izin')->count();
            $sakitCount = $attendances->where('status', 'sakit')->count();
            $alpaCount  = $attendances->where('status', 'alpa')->count();

            // Sesi yang belum terisi presensinya otomatis dihitung alpa jika total sesi > recorded
            $recordedCount = $attendances->count();
            $unrecordedAlpa = max(0, $totalSessions - $recordedCount);
            $effectiveAlpa = $alpaCount + $unrecordedAlpa;

            // Persentase Kehadiran = (Total Hadir / Total Sesi) * 100
            $percentage = $totalSessions > 0 ? round(($hadirCount / $totalSessions) * 100, 1) : 0;

            // Penentuan Status Kelulusan Berdasarkan Aturan Bisnis:
            // 1. Kehadiran >= 80%: Lulus
            // 2. < 80% namun kombinasi (Hadir + Izin + Sakit) >= 80% (Izin/Sakit Valid): Tugas Pengganti
            // 3. Sisa lainnya (dominan Alpa): Tidak Lulus
            $validExcusePercentage = $totalSessions > 0 ? round((($hadirCount + $izinCount + $sakitCount) / $totalSessions) * 100, 1) : 0;

            if ($percentage >= 80.0) {
                $statusKelulusan = 'Lulus';
                $badgeClass = 'bg-emerald-100 text-emerald-800 border-emerald-300';
            } elseif ($validExcusePercentage >= 80.0) {
                $statusKelulusan = 'Tugas Pengganti';
                $badgeClass = 'bg-amber-100 text-amber-800 border-amber-300';
            } else {
                $statusKelulusan = 'Tidak Lulus';
                $badgeClass = 'bg-rose-100 text-rose-800 border-rose-300';
            }

            return [
                'student' => $student,
                'nim' => $student->nim,
                'name' => $student->name,
                'study_program' => $student->study_program,
                'faculty' => $student->faculty,
                'group_name' => $student->group->name ?? '-',
                'hadir_count' => $hadirCount,
                'izin_count' => $izinCount,
                'sakit_count' => $sakitCount,
                'alpa_count' => $effectiveAlpa,
                'total_sessions' => $totalSessions,
                'percentage' => $percentage,
                'status_kelulusan' => $statusKelulusan,
                'badge_class' => $badgeClass,
            ];
        });

        // Filter berdasarkan status kelulusan jika diminta
        if ($statusFilter) {
            $recapList = $recapList->filter(fn ($item) => strtolower($item['status_kelulusan']) === strtolower($statusFilter));
        }

        return [
            'recap' => $recapList->values(),
            'total_sessions' => $totalSessions,
            'summary' => [
                'total_students' => $recapList->count(),
                'total_lulus' => $recapList->where('status_kelulusan', 'Lulus')->count(),
                'total_tugas' => $recapList->where('status_kelulusan', 'Tugas Pengganti')->count(),
                'total_tidak_lulus' => $recapList->where('status_kelulusan', 'Tidak Lulus')->count(),
            ]
        ];
    }

    /**
     * Export data rekapitulasi ke format CSV yang kompatibel dengan Microsoft Excel / Spreadsheet.
     */
    public function exportCsv(?int $groupId = null): StreamedResponse
    {
        $groupName = 'Semua_Gugus';
        if ($groupId) {
            $group = Group::find($groupId);
            if ($group) {
                $groupName = str_replace(' ', '_', $group->name);
            }
        }

        $filename = "Rekap_Presensi_PKKMB_{$groupName}_" . date('Ymd_His') . ".csv";
        $data = $this->getRecapData($groupId);

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            
            // Write UTF-8 BOM for Microsoft Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header kolom CSV
            fputcsv($file, [
                'No',
                'NIM',
                'Nama Mahasiswa',
                'Program Studi',
                'Gugus',
                'Total Hadir',
                'Total Sesi',
                'Persentase Kehadiran (%)',
            ]);

            foreach ($data['recap'] as $index => $row) {
                fputcsv($file, [
                    $index + 1,
                    "'{$row['nim']}", // prepend single quote so excel keeps leading zeros if any
                    $row['name'],
                    $row['study_program'],
                    $row['group_name'],
                    $row['hadir_count'],
                    $row['total_sessions'],
                    $row['percentage'] . '%',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
