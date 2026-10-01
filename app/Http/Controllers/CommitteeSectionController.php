<?php

namespace App\Http\Controllers;

use App\Models\CommitteeAttendance;
use App\Models\CommitteeSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CommitteeSectionController extends Controller
{
    /**
     * Tampilkan Daftar Seksi Panitia & Data Absensi Panitia
     */
    public function index(Request $request): View
    {
        $sections = CommitteeSection::withCount('attendances')->orderBy('id', 'asc')->get();

        $query = CommitteeAttendance::with(['committeeSection', 'eventDay']);

        if ($request->filled('section_id')) {
            $query->where('committee_section_id', $request->section_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('nim', 'like', '%' . $search . '%');
            });
        }

        $attendances = $query->latest()->paginate(25)->withQueryString();

        return view('committee_sections.index', compact('sections', 'attendances'));
    }

    /**
     * Simpan Seksi Panitia Baru
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:committee_sections,name'],
        ], [
            'name.required' => 'Nama Seksi / Divisi Panitia wajib diisi.',
            'name.unique' => 'Seksi / Divisi Panitia tersebut sudah ada.',
        ]);

        CommitteeSection::create($validated);

        return redirect()->back()->with('success', "Seksi Panitia '{$validated['name']}' berhasil ditambahkan.");
    }

    /**
     * Hapus Seksi Panitia
     */
    public function destroy(CommitteeSection $committeeSection): RedirectResponse
    {
        $sectionName = $committeeSection->name;
        $committeeSection->delete();

        return redirect()->back()->with('success', "Seksi Panitia '{$sectionName}' berhasil dihapus.");
    }

    /**
     * Hapus Catatan Presensi Panitia
     */
    public function destroyAttendance(CommitteeAttendance $attendance): RedirectResponse
    {
        $name = $attendance->name;
        $attendance->delete();

        return redirect()->back()->with('success', "Presensi Panitia '{$name}' berhasil dihapus.");
    }

    /**
     * Export Rekap Presensi Panitia ke Excel / CSV
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        $query = CommitteeAttendance::with(['committeeSection', 'eventDay']);

        if ($request->filled('section_id')) {
            $query->where('committee_section_id', $request->section_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('nim', 'like', '%' . $search . '%');
            });
        }

        $attendances = $query->latest()->get();

        $filename = "Rekap_Presensi_Panitia_PKKMB_" . date('Ymd_His') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($attendances) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            fputcsv($file, ["REKAPITULASI PRESENSI PANITIA PKKMB 2026"]);
            fputcsv($file, ["Tanggal Export", date('d/m/Y H:i:s')]);
            fputcsv($file, ["Total Presensi", $attendances->count() . " Orang"]);
            fputcsv($file, []);

            fputcsv($file, [
                'No',
                'NIM',
                'Nama Panitia',
                'Seksi / Divisi',
                'Sesi Kegiatan',
                'Waktu Presensi'
            ]);

            foreach ($attendances as $index => $row) {
                fputcsv($file, [
                    $index + 1,
                    $row->nim ? "'{$row->nim}" : '-',
                    $row->name ?? '-',
                    $row->committeeSection->name ?? '-',
                    $row->eventDay->full_session_title ?? '-',
                    $row->created_at ? $row->created_at->format('d/m/Y H:i:s') : '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export / Cetak Rekap Presensi Panitia ke PDF (Print Layout)
     */
    public function exportPdf(Request $request): View
    {
        $query = CommitteeAttendance::with(['committeeSection', 'eventDay']);

        $selectedSection = null;
        if ($request->filled('section_id')) {
            $query->where('committee_section_id', $request->section_id);
            $selectedSection = CommitteeSection::find($request->section_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('nim', 'like', '%' . $search . '%');
            });
        }

        $attendances = $query->latest()->get();

        return view('committee_sections.pdf', [
            'attendances' => $attendances,
            'selectedSection' => $selectedSection,
            'search' => $request->search,
        ]);
    }
}
