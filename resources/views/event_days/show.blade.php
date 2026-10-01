<x-layouts.app title="Data Kehadiran Sesi {{ $eventDay->full_session_title }} - PKKMB">
    <!-- Print Only Formal Document Kop/Header -->
    <div class="hidden print:block mb-6 text-center border-b-2 border-slate-900 pb-4">
        <div class="flex items-center justify-center gap-4 mb-2">
            <img src="{{ asset('image.png') }}" alt="Logo Metamedia" class="w-14 h-14 object-contain">
            <div class="text-center">
                <h2 class="text-xl font-black uppercase text-slate-900 tracking-tight leading-tight">UNIVERSITAS METAMEDIA</h2>
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wide">PANITIA PENGENALAN KEHIDUPAN KAMPUS BAGI MAHASISWA BARU (PKKMB) 2026</h3>
                <p class="text-xs text-slate-500">Jl. Khatib Sulaiman No.1, Padang, Sumatera Barat</p>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-300">
            <h4 class="text-base font-extrabold uppercase tracking-wide text-slate-900">LAPORAN KEHADIRAN MAHASISWA BARU</h4>
            <p class="text-xs font-semibold text-slate-700 mt-0.5">
                SESI: <span class="uppercase font-bold">{{ $eventDay->full_session_title }}</span> &bull; TANGGAL: {{ \Carbon\Carbon::parse($eventDay->date)->translatedFormat('l, d F Y') }}
            </p>
        </div>
    </div>

    <!-- Screen Header Page Title & Action Buttons -->
    <div class="no-print mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('event-days.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 border border-indigo-100 transition">
                    &larr; Kembali ke Kelola Sesi
                </a>
                @if($eventDay->is_active)
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300 animate-pulse">
                        ● Sesi Berlangsung (Aktif)
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-slate-100 text-slate-700 border border-slate-300">
                        ○ Sesi Selesai / Arsip
                    </span>
                @endif
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Data Kehadiran: <span class="text-indigo-600">{{ $eventDay->full_session_title }}</span>
            </h1>
            <p class="text-slate-500 text-sm mt-1">
                Tanggal Sesi: <span class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($eventDay->date)->translatedFormat('l, d F Y') }}</span>
            </p>
        </div>

        <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
            <!-- Export Excel / CSV Button -->
            <a href="{{ route('event-days.export', $eventDay) }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export Excel / CSV
            </a>

            <!-- Cetak PDF / Print Button -->
            <button onclick="window.print()" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak PDF / Print
            </button>
        </div>
    </div>

    <!-- Screen Summary Stats Card -->
    <div class="no-print mb-8 p-5 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 border border-slate-700 text-white rounded-2xl shadow-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-sky-500/20 border border-sky-400/30 text-sky-400 flex items-center justify-center text-2xl font-bold">
                📊
            </div>
            <div>
                <span class="text-[10px] font-bold text-sky-400 uppercase tracking-widest block">REKAP KEHADIRAN SESI INI</span>
                <h3 class="text-xl font-extrabold text-white mt-0.5">{{ number_format($totalHadir) }} Mahasiswa Presensi</h3>
                <p class="text-xs text-slate-400 mt-0.5">Data yang tercatat via formulir presensi mandiri web</p>
            </div>
        </div>

        <a href="{{ route('attendance.index', ['event_day_id' => $eventDay->id]) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl transition shadow flex items-center gap-1.5">
            📝 Input Presensi Massal Manual
        </a>
    </div>

    <!-- Screen Search Form -->
    <div class="no-print bg-white p-4 rounded-2xl border border-slate-200 shadow-xs mb-6">
        <form method="GET" action="{{ route('event-days.show', $eventDay) }}" class="flex flex-col sm:flex-row items-center gap-3">
            <div class="flex-1 w-full">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Cari berdasarkan NIM, Nama Mahasiswa, atau Program Studi..." 
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition w-full sm:w-auto">
                    Cari Data
                </button>
                @if($search)
                    <a href="{{ route('event-days.show', $eventDay) }}" class="px-3.5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-xl transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table Container -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden print:border-none print:shadow-none print:rounded-none">
        <div class="no-print p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Daftar Mahasiswa Presensi pada {{ $eventDay->full_session_title }}</h3>
            <span class="text-xs text-slate-500 font-medium">Menampilkan {{ $attendances->count() }} dari {{ $attendances->total() }} data</span>
        </div>

        <div class="overflow-x-auto print:overflow-visible">
            <table class="w-full text-left text-xs border-collapse print:text-[10pt] print:w-full">
                <thead class="bg-slate-100/80 border-b border-slate-200 text-slate-600 uppercase tracking-wider text-[11px] font-bold print:bg-slate-200 print:text-black">
                    <tr>
                        <th class="py-3 px-3 w-10 text-center border print:border-slate-400">No</th>
                        <th class="py-3 px-3 border print:border-slate-400">Waktu Presensi</th>
                        <th class="py-3 px-3 border print:border-slate-400">NIM</th>
                        <th class="py-3 px-3 border print:border-slate-400">Nama Lengkap</th>
                        <th class="py-3 px-3 border print:border-slate-400">No. Telepon / WA</th>
                        <th class="py-3 px-3 border print:border-slate-400">Program Studi</th>
                        <th class="py-3 px-3 border print:border-slate-400">Gugus</th>
                        <th class="py-3 px-3 text-center border print:border-slate-400">Status</th>
                        <th class="py-3 px-3 text-center border print:border-slate-400 no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium print:divide-slate-300">
                    @forelse($attendances as $index => $attendance)
                    <tr class="hover:bg-slate-50/80 transition print:bg-white">
                        <td class="py-2.5 px-3 text-center text-slate-500 font-bold border print:border-slate-400 print:text-black">
                            {{ $attendances->firstItem() + $index }}
                        </td>
                        <td class="py-2.5 px-3 whitespace-nowrap border print:border-slate-400 print:text-black">
                            <span class="font-mono font-bold text-slate-800 print:text-black">
                                {{ $attendance->created_at->format('H:i:s') }} WIB
                            </span>
                            <span class="text-[10px] text-slate-500 block print:text-slate-700">
                                {{ $attendance->created_at->format('d/m/Y') }}
                            </span>
                        </td>
                        <td class="py-2.5 px-3 font-mono font-bold text-slate-900 border print:border-slate-400 print:text-black">
                            {{ $attendance->student->nim ?? '-' }}
                        </td>
                        <td class="py-2.5 px-3 font-bold text-slate-900 border print:border-slate-400 print:text-black">
                            {{ $attendance->student->name ?? '-' }}
                        </td>
                        <td class="py-2.5 px-3 text-slate-700 font-mono border print:border-slate-400 print:text-black">
                            {{ $attendance->student->phone ?? '-' }}
                        </td>
                        <td class="py-2.5 px-3 text-slate-800 font-semibold border print:border-slate-400 print:text-black">
                            {{ $attendance->student->study_program ?? '-' }}
                        </td>
                        <td class="py-2.5 px-3 border print:border-slate-400 print:text-black">
                            @if($attendance->student && $attendance->student->group)
                                <span class="px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 font-bold text-[11px] print:bg-transparent print:text-black print:p-0">
                                    {{ $attendance->student->group->name }}
                                </span>
                            @else
                                <span class="text-slate-400 italic text-[11px] print:text-slate-600">-</span>
                            @endif
                        </td>
                        <td class="py-2.5 px-3 text-center border print:border-slate-400">
                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300 font-bold text-xs inline-block print:bg-transparent print:border-none print:text-black">
                                Hadir
                            </span>
                        </td>
                        <td class="py-2.5 px-3 text-center border print:border-slate-400 no-print">
                            <form action="{{ route('attendance.destroy', $attendance) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data presensi {{ addslashes($attendance->student->name ?? 'mahasiswa ini') }}?');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2.5 py-1 text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-lg transition inline-flex items-center gap-1 shadow-2xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-10 text-center text-slate-400 italic border print:border-slate-400">
                            Belum ada data mahasiswa yang melakukan presensi pada sesi ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($attendances->hasPages())
        <div class="no-print p-4 border-t border-slate-200">
            {{ $attendances->links() }}
        </div>
        @endif
    </div>

    <!-- Print Only Signature Block -->
    <div class="hidden print:block mt-10 pt-4 text-xs">
        <div class="flex justify-between items-end">
            <div>
                <p>Dicetak pada: {{ date('d/m/Y H:i:s') }} WIB</p>
                <p>Sistem Presensi PKKMB Universitas Metamedia</p>
            </div>
            <div class="text-center pr-8">
                <p class="mb-14">Padang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>Koordinator Sekretariat PKKMB</p>
                <p class="font-bold underline decoration-1">( ___________________________ )</p>
            </div>
        </div>
    </div>

    <!-- Custom CSS for Clean Print / PDF Formatting -->
    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 15mm;
            }

            body {
                background: #ffffff !important;
                color: #000000 !important;
                font-family: 'Plus Jakarta Sans', Arial, sans-serif !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* Hide all unnecessary navigation, headers, footers, buttons */
            header, nav, footer, .no-print, button, form, input, select {
                display: none !important;
            }

            /* Container full width without shadows/margins */
            main {
                max-width: 100% !important;
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            /* Table styling for print */
            table {
                width: 100% !important;
                border-collapse: collapse !important;
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            th, td {
                padding: 6px 8px !important;
                border: 1px solid #94a3b8 !important;
                font-size: 9.5pt !important;
            }

            th {
                background-color: #f1f5f9 !important;
                color: #0f172a !important;
            }
        }
    </style>
</x-layouts.app>
