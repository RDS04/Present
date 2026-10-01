<x-layouts.app title="Dashboard - Sistem Presensi PKKMB">
    <!-- Header Page Title -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Dashboard Presensi & Rekapitulasi</h1>
            <p class="text-slate-500 text-sm mt-1">Ringkasan status kehadiran dan kelulusan Mahasiswa Baru PKKMB</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('attendance.index') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Input Presensi Massal
            </a>
            <a href="{{ route('recap.export') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export Excel/CSV
            </a>
        </div>
    </div>

    <!-- Group Information for Pendamping -->
    @if(auth()->user()->isPendampingGugus() && $userGroup)
    <div class="mb-8 p-5 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-2xl flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold text-xl shadow">
                👥
            </div>
            <div>
                <span class="text-xs font-bold text-emerald-600 tracking-wider uppercase">Gugus Yang Anda Ampu</span>
                <h3 class="text-lg font-extrabold text-slate-900">{{ $userGroup->name }}</h3>
                <p class="text-xs text-slate-600 mt-0.5">Jumlah Mahasiswa: <span class="font-bold text-slate-800">{{ $userGroup->students_count }} Orang</span></p>
            </div>
        </div>
        <a href="{{ route('attendance.index', ['group_id' => $userGroup->id]) }}" class="px-4 py-2 bg-emerald-600 text-white rounded-xl font-bold text-xs hover:bg-emerald-700 shadow">
            Mulai Presensi &rarr;
        </a>
    </div>
    @endif

    <!-- Statistic Grid Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
        <!-- Card 1: Total Student -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Mahasiswa</span>
                <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-3xl font-extrabold text-slate-900">{{ number_format($totalStudents) }}</div>
                <div class="text-xs text-slate-500 mt-1">Terbagi dalam {{ $totalGroups }} Gugus</div>
            </div>
        </div>

        <!-- Card 2: Total Panitia -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-purple-600">Total Panitia</span>
                <div class="p-2.5 bg-purple-50 text-purple-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-3xl font-extrabold text-purple-900">{{ number_format($totalCommittee) }}</div>
                <div class="text-xs text-slate-500 mt-1">Terbagi dalam {{ $totalCommitteeSections }} Seksi</div>
            </div>
        </div>
    </div>

    <!-- Main Active Session & Management Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
            <div>
                <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                    Sesi Acara Berlangsung
                </h3>
                <p class="text-xs text-slate-500">Kelola dan luncurkan sesi aktif untuk pengisian presensi mahasiswa</p>
            </div>
            
            <div class="flex items-center gap-2">
                @if(auth()->user()->isAdminSekretariat())
                <button type="button" onclick="toggleAddSessionModal()" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5">
                    <span>+</span> Tambah Sesi Baru
                </button>
                @endif

                @if($activeSession)
                    <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold flex items-center gap-1 animate-pulse">
                        ● Status: Sesi Aktif
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">
                        ○ Tidak Ada Sesi Aktif
                    </span>
                @endif
            </div>
        </div>

        <!-- Active Session Info Box -->
        @if($activeSession)
        <div class="mt-5 p-4 rounded-xl bg-gradient-to-r from-slate-900 to-indigo-950 text-white flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-lg border border-slate-800">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-sky-500/20 border border-sky-400/30 text-sky-400 flex items-center justify-center font-extrabold text-xl shrink-0">
                    📌
                </div>
                <div>
                    <span class="text-[10px] font-bold text-sky-400 uppercase tracking-widest block">SESI BERLANGSUNG SAAT INI</span>
                    <h4 class="font-extrabold text-white text-sm sm:text-base leading-tight mt-0.5">{{ $activeSession->full_session_title }}</h4>
                    <p class="text-xs text-slate-400 mt-0.5">Tanggal: {{ \Carbon\Carbon::parse($activeSession->date)->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('event-days.show', $activeSession) }}" class="px-4 py-2 bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs rounded-xl transition shadow flex items-center gap-1.5" title="Lihat Data Kehadiran Sesi">
                    <span>👁️</span> Lihat Data Absen
                </a>

                <a href="{{ route('attendance.index', ['event_day_id' => $activeSession->id]) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl transition shadow flex items-center gap-1.5">
                    <span>📝</span> Isi Presensi Massal Sesi Ini
                </a>

                @if(auth()->user()->isAdminSekretariat())
                <form method="POST" action="{{ route('event-days.toggle', $activeSession) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyelesaikan/menutup sesi ini?')" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl transition shadow flex items-center gap-1.5">
                        <span>🏁</span> Selesaikan Sesi Ini
                    </button>
                </form>
                @endif
            </div>
        </div>
        @else
        <div class="mt-4 p-6 bg-slate-50 border border-slate-200 rounded-xl text-center">
            <div class="text-2xl mb-2">📌</div>
            <h4 class="text-sm font-bold text-slate-800">Belum Ada Sesi Acara yang Diluncurkan</h4>
            <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">Silakan pilih dan luncurkan salah satu sesi dari daftar di bawah atau buat sesi acara baru.</p>
        </div>
        @endif

        <!-- Form Tambah Sesi Modal / Collapsible -->
        @if(auth()->user()->isAdminSekretariat())
        <div id="addSessionModal" class="hidden mt-6 p-5 bg-slate-50 rounded-2xl border border-indigo-200 shadow-inner">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-200">
                <h4 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                    ➕ Form Tambah &amp; Luncurkan Sesi Acara Baru
                </h4>
                <button type="button" onclick="toggleAddSessionModal()" class="text-slate-400 hover:text-slate-600 font-bold text-lg">&times;</button>
            </div>

            <form method="POST" action="{{ route('event-days.store') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Nama Hari</label>
                    <input type="text" name="day_name" placeholder="Contoh: Hari 1, Hari 2" required class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs font-medium focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Keterangan / Sesi Acara</label>
                    <input type="text" name="session_name" placeholder="Contoh: Sesi Pagi - Pembukaan & Orientasi" required class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs font-medium focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Tanggal Kegiatan</label>
                    <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs font-medium focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="sm:col-span-3 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 pt-2 border-t border-slate-200">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="launch_now" value="1" checked class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                        <span class="text-xs font-bold text-slate-800">Luncurkan Sesi Ini Sekarang (Tutup sesi lama)</span>
                    </label>

                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <button type="button" onclick="toggleAddSessionModal()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-xl shadow-md transition flex items-center gap-1.5">
                            🚀 Simpan &amp; Luncurkan Sesi
                        </button>
                    </div>
                </div>
            </form>
        </div>
        @endif

        <!-- Quick Session Switcher List for Admin -->
        @if(auth()->user()->isAdminSekretariat() && count($allSessions) > 0)
        <div class="mt-6 pt-5 border-t border-slate-100">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Daftar Sesi Acara (Pilih &amp; Luncurkan):</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($allSessions as $session)
                <div class="p-3 rounded-xl border {{ $session->is_active ? 'bg-sky-50 border-sky-300' : 'bg-slate-50 border-slate-200' }} flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <span class="text-[10px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-indigo-100 text-indigo-700">
                            {{ $session->day_name }}
                        </span>
                        <h5 class="text-xs font-bold text-slate-900 truncate mt-1">{{ $session->session_name }}</h5>
                        <p class="text-[10px] text-slate-500">{{ \Carbon\Carbon::parse($session->date)->format('d/m/Y') }}</p>
                    </div>

                    <div class="shrink-0 flex items-center gap-1.5">
                        <a href="{{ route('event-days.show', $session) }}" class="px-2.5 py-1.5 bg-sky-100 hover:bg-sky-200 text-sky-900 rounded-lg text-[11px] font-bold transition flex items-center gap-1" title="Lihat Data Kehadiran Sesi">
                            👁️ <span class="hidden sm:inline font-bold">Lihat</span>
                        </a>

                        @if($session->is_active)
                            <form method="POST" action="{{ route('event-days.toggle', $session) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-2.5 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-900 rounded-lg text-[11px] font-bold transition flex items-center gap-1">
                                    🏁 Selesaikan
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('event-days.launch', $session) }}" class="inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Tutup sesi lama dan luncurkan {{ $session->full_session_title }}?')" class="px-2.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-[11px] font-bold shadow transition flex items-center gap-1">
                                    🚀 Luncurkan
                                </button>
                            </form>
                        @endif

                        <!-- Tombol Hapus Sesi -->
                        <form method="POST" action="{{ route('event-days.destroy', $session) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus sesi \'{{ $session->full_session_title }}\'? Data presensi pada sesi ini akan terhapus.')" class="px-2.5 py-1.5 bg-rose-100 hover:bg-rose-200 text-rose-800 rounded-lg text-[11px] font-bold transition flex items-center gap-1" title="Hapus Sesi">
                                🗑️ <span class="hidden sm:inline">Hapus</span>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <script>
        function toggleAddSessionModal() {
            const modal = document.getElementById('addSessionModal');
            if (modal) {
                modal.classList.toggle('hidden');
            }
        }
    </script>
</x-layouts.app>
