<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Presensi Panitia PKKMB 2026</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
                padding: 0 !important;
            }
            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen text-slate-800 p-4 sm:p-8 font-sans">

    <!-- Action Bar (Hidden when printed) -->
    <div class="no-print max-w-5xl mx-auto mb-6 bg-white p-4 rounded-2xl border border-slate-200 shadow-md flex items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <a href="{{ route('committee-sections.index', request()->query()) }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-xl transition flex items-center gap-1.5">
                ⬅️ Kembali ke Rekap
            </a>
            <span class="text-xs text-slate-500 hidden sm:inline">| Mode Pratinjau Cetak / PDF</span>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white font-extrabold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                🖨️ Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Printable Paper Sheet Container -->
    <div class="max-w-5xl mx-auto bg-white p-8 sm:p-10 rounded-xl border border-slate-200 shadow-lg print:shadow-none print:border-none print:p-0">
        
        <!-- Header Document / Kop Surat -->
        <div class="border-b-2 border-slate-900 pb-4 mb-6 text-center">
            <h2 class="text-lg font-bold uppercase tracking-wider text-slate-700">PANITIA PELAKSANA PKKMB 2026</h2>
            <h1 class="text-2xl font-black uppercase text-slate-900 tracking-tight mt-1">REKAPITULASI PRESENSI PANITIA</h1>
            <p class="text-xs text-slate-600 mt-1">Sistem Informasi Presensi &amp; Rekapitulasi Kegiatan PKKMB 2026</p>
        </div>

        <!-- Filter Metadata Information -->
        <div class="grid grid-cols-2 text-xs mb-6 bg-slate-50 p-4 rounded-xl border border-slate-200">
            <div>
                <p class="mb-1"><span class="font-bold text-slate-600">Filter Seksi / Divisi:</span> 
                    <span class="font-extrabold text-purple-700">{{ $selectedSection->name ?? 'Semua Seksi / Divisi' }}</span>
                </p>
                @if($search)
                    <p><span class="font-bold text-slate-600">Pencarian Nama/NIM:</span> "{{ $search }}"</p>
                @endif
            </div>
            <div class="text-right">
                <p class="mb-1"><span class="font-bold text-slate-600">Tanggal Dicetak:</span> {{ date('d F Y, H:i') }} WIB</p>
                <p><span class="font-bold text-slate-600">Total Data Presensi:</span> <span class="font-extrabold text-slate-900">{{ $attendances->count() }} Orang</span></p>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="overflow-x-auto mb-8">
            <table class="w-full text-left text-xs border-collapse border border-slate-300">
                <thead>
                    <tr class="bg-slate-200 text-slate-900 font-extrabold border-b border-slate-300">
                        <th class="border border-slate-300 px-3 py-2.5 text-center w-10">NO</th>
                        <th class="border border-slate-300 px-3 py-2.5 w-32">NIM</th>
                        <th class="border border-slate-300 px-3 py-2.5">NAMA PANITIA</th>
                        <th class="border border-slate-300 px-3 py-2.5">SEKSI / DIVISI</th>
                        <th class="border border-slate-300 px-3 py-2.5">SESI KEGIATAN</th>
                        <th class="border border-slate-300 px-3 py-2.5 text-center w-40">WAKTU PRESENSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-300">
                    @forelse($attendances as $index => $att)
                    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/50' }}">
                        <td class="border border-slate-300 px-3 py-2 text-center font-bold text-slate-600">
                            {{ $index + 1 }}
                        </td>
                        <td class="border border-slate-300 px-3 py-2 font-mono font-semibold text-slate-800">
                            {{ $att->nim ?? '-' }}
                        </td>
                        <td class="border border-slate-300 px-3 py-2 font-bold text-slate-900">
                            {{ $att->name }}
                        </td>
                        <td class="border border-slate-300 px-3 py-2 font-semibold text-purple-800">
                            {{ $att->committeeSection->name ?? '-' }}
                        </td>
                        <td class="border border-slate-300 px-3 py-2 text-slate-700">
                            {{ $att->eventDay->full_session_title ?? '-' }}
                        </td>
                        <td class="border border-slate-300 px-3 py-2 text-center font-mono text-[11px] text-slate-600">
                            {{ $att->created_at ? $att->created_at->format('d/m/Y H:i:s') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="border border-slate-300 px-3 py-8 text-center text-slate-400 italic">
                            Tidak ada data presensi panitia yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Signature / Approval Footer -->
        <div class="mt-12 grid grid-cols-2 text-xs pt-4">
            <div>
                <p class="text-slate-500">Catatan:</p>
                <p class="text-slate-500 text-[11px] italic mt-0.5">Dokumen ini disahkan secara digital oleh Sekretariat PKKMB 2026.</p>
            </div>
            <div class="text-center">
                <p class="mb-1">Tercetak Otomatis oleh System,</p>
                <p class="font-bold text-slate-900 mb-16">Admin Sekretariat PKKMB 2026</p>
                <p class="font-extrabold text-slate-900 underline">( ___________________________ )</p>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            // Optional auto print when opened
            // window.print();
        });
    </script>
</body>
</html>
