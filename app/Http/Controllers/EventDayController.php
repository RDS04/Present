<?php

namespace App\Http\Controllers;

use App\Models\EventDay;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventDayController extends Controller
{
    public function index(): View
    {
        $eventDays = EventDay::orderBy('date', 'desc')->orderBy('id', 'desc')->get();
        $activeSession = EventDay::where('is_active', true)->first();

        return view('event_days.index', compact('eventDays', 'activeSession'));
    }

    /**
     * Detail Data Kehadiran Mahasiswa pada Sesi Acara Tertentu
     */
    public function show(EventDay $eventDay, Request $request): View
    {
        $search = $request->input('search');

        $query = \App\Models\Attendance::with(['student.group', 'eventDay'])
            ->where('event_day_id', $eventDay->id);

        if ($search) {
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('study_program', 'like', "%{$search}%");
            });
        }

        $attendances = $query->orderBy('created_at', 'desc')->paginate(50)->withQueryString();
        $totalHadir = \App\Models\Attendance::where('event_day_id', $eventDay->id)->count();

        return view('event_days.show', compact('eventDay', 'attendances', 'totalHadir', 'search'));
    }

    /**
     * Export Data Kehadiran Sesi Tertentu ke Excel / CSV
     */
    public function exportCsv(EventDay $eventDay): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $sessionTitleClean = str_replace(['/', '\\', ' '], '_', $eventDay->full_session_title);
        $filename = "Absensi_Sesi_{$sessionTitleClean}_" . date('Ymd_His') . ".csv";

        $attendances = \App\Models\Attendance::with(['student.group'])
            ->where('event_day_id', $eventDay->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($eventDay, $attendances) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // Write UTF-8 BOM

            // Title & Info
            fputcsv($file, ["DATA KEHADIRAN MAHASISWA BARU - PKKMB 2026"]);
            fputcsv($file, ["Sesi Acara", $eventDay->full_session_title]);
            fputcsv($file, ["Tanggal Kegiatan", \Carbon\Carbon::parse($eventDay->date)->format('d/m/Y')]);
            fputcsv($file, ["Total Hadir", $attendances->count() . " Mahasiswa"]);
            fputcsv($file, []); // Empty row divider

            // Table Header
            fputcsv($file, [
                'No',
                'Waktu Presensi',
                'NIM',
                'Nama Lengkap',
                'No. Telepon / WA',
                'Program Studi',
                'Gugus PKKMB',
                'Status Presensi'
            ]);

            foreach ($attendances as $index => $row) {
                fputcsv($file, [
                    $index + 1,
                    $row->created_at->format('d/m/Y H:i:s'),
                    $row->student->nim ? "'{$row->student->nim}" : '-',
                    $row->student->name ?? '-',
                    $row->student->phone ? "'{$row->student->phone}" : '-',
                    $row->student->study_program ?? '-',
                    $row->student->group->name ?? '-',
                    'Hadir'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Tambah Sesi Acara Baru (bisa langsung diluncurkan)
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'day_name' => ['required', 'string', 'max:100'],
            'session_name' => ['required', 'string', 'max:100'],
            'date' => ['required', 'date'],
            'launch_now' => ['nullable', 'boolean'],
        ]);

        $shouldLaunch = !empty($request->input('launch_now'));

        if ($shouldLaunch) {
            // Tutup semua sesi lama terlebih dahulu
            EventDay::query()->update(['is_active' => false]);
            $validated['is_active'] = true;
        } else {
            $validated['is_active'] = false;
        }

        unset($validated['launch_now']);

        $eventDay = EventDay::create($validated);

        $msg = $shouldLaunch 
            ? "Sesi {$eventDay->full_session_title} berhasil dibuat dan DILUNCURKAN ke portal presensi!"
            : "Sesi {$eventDay->full_session_title} berhasil ditambahkan.";

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Luncurkan Sesi Acara (Tutup semua sesi lama, Buka sesi yang dipilih)
     */
    public function launch(EventDay $eventDay): RedirectResponse
    {
        // 1. Tutup semua sesi lama
        EventDay::query()->update(['is_active' => false]);

        // 2. Buka dan luncurkan sesi baru ini
        $eventDay->update(['is_active' => true]);

        return redirect()->back()->with('success', "🚀 Sesi '{$eventDay->full_session_title}' BERHASIL DILUNCURKAN! Sesi ini sekarang aktif di portal presensi mahasiswa.");
    }

    /**
     * Tutup / Nonaktifkan Sesi Active
     */
    public function toggleActive(EventDay $eventDay): RedirectResponse
    {
        if ($eventDay->is_active) {
            $eventDay->update(['is_active' => false]);
            return redirect()->back()->with('success', "Sesi {$eventDay->full_session_title} telah ditutup/dinonaktifkan.");
        } else {
            // Jika diaktifkan, tutup sesi lain agar hanya 1 sesi aktif
            EventDay::query()->update(['is_active' => false]);
            $eventDay->update(['is_active' => true]);
            return redirect()->back()->with('success', "Sesi {$eventDay->full_session_title} berhasil diaktifkan.");
        }
    }

    /**
     * Hapus Sesi Acara
     */
    public function destroy(EventDay $eventDay): RedirectResponse
    {
        $sessionTitle = $eventDay->full_session_title;
        $eventDay->delete();

        return redirect()->back()->with('success', "Sesi '{$sessionTitle}' telah berhasil dihapus.");
    }
}
