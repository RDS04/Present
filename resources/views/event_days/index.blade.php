<x-layouts.app title="Kelola Sesi Acara - PKKMB">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Kelola Sesi Acara PKKMB</h1>
            <p class="text-slate-500 text-sm mt-1">Tambah, tutup sesi lama, dan peluncuran sesi baru ke portal presensi mahasiswa</p>
        </div>
    </div>

    <!-- Active Launched Session Banner Card (Matching Portal Display) -->
    @if($activeSession)
    <div class="mb-8 p-5 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 border border-slate-700 text-white rounded-2xl shadow-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-sky-500/20 border border-sky-400/30 text-sky-400 flex items-center justify-center text-xl font-bold">
                📌
            </div>
            <div>
                <span class="text-[11px] font-bold text-sky-400 uppercase tracking-widest block">SESI BERLANGSUNG SAAT INI</span>
                <h3 class="text-base sm:text-lg font-bold text-white mt-0.5">{{ $activeSession->full_session_title }}</h3>
                <p class="text-xs text-slate-400 mt-0.5">Tanggal: {{ \Carbon\Carbon::parse($activeSession->date)->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('event-days.show', $activeSession) }}" class="px-4 py-2 bg-sky-600 hover:bg-sky-500 text-white font-bold text-xs rounded-xl shadow transition flex items-center gap-1.5" title="Lihat Data Kehadiran Sesi">
                <span>👁️</span> Lihat Data Kehadiran
            </a>
            <form method="POST" action="{{ route('event-days.toggle', $activeSession) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow transition flex items-center gap-1.5">
                    <span>&times;</span> Tutup Sesi Ini
                </button>
            </form>
        </div>
    </div>
    @else
    <div class="mb-8 p-4 bg-amber-50 border border-amber-200 text-amber-900 rounded-2xl flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-xl">⚠️</span>
            <span class="text-xs font-bold">Belum ada sesi acara yang diluncurkan/aktif. Silakan luncurkan salah satu sesi di bawah ini.</span>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- List Sesi Acara -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-4 bg-slate-50 border-b border-slate-200 font-bold text-sm text-slate-800 flex items-center justify-between">
                    <span>Daftar Sesi Acara PKKMB</span>
                    <span class="text-xs font-normal text-slate-500">Klik "Luncurkan Sesi" untuk mengganti sesi aktif</span>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($eventDays as $day)
                    <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 {{ $day->is_active ? 'bg-sky-50/50' : 'hover:bg-slate-50/50' }} transition">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded-md">
                                    {{ $day->day_name }}
                                </span>
                                @if($day->is_active)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 animate-pulse">
                                        ● SEDANG BERLANGSUNG
                                    </span>
                                @endif
                            </div>
                            <h4 class="font-bold text-slate-900 text-sm mt-1.5">{{ $day->session_name }}</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Tanggal: {{ \Carbon\Carbon::parse($day->date)->translatedFormat('l, d F Y') }}</p>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <a href="{{ route('event-days.show', $day) }}" class="px-3 py-1.5 bg-sky-100 hover:bg-sky-200 text-sky-900 rounded-lg text-xs font-bold transition flex items-center gap-1" title="Lihat Data Kehadiran Sesi Ini">
                                👁️ <span class="hidden sm:inline">Lihat Data</span>
                            </a>
                            @if(!$day->is_active)
                                <form method="POST" action="{{ route('event-days.launch', $day) }}">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Tutup sesi lama dan luncurkan {{ $day->full_session_title }} ke portal presensi?')" class="px-3.5 py-2 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white rounded-xl text-xs font-extrabold shadow transition flex items-center gap-1.5">
                                        🚀 Luncurkan Sesi Ini
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('event-days.toggle', $day) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-3 py-1.5 border border-rose-300 text-rose-700 hover:bg-rose-50 rounded-lg text-xs font-bold transition">
                                        Tutup Sesi
                                    </button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('event-days.destroy', $day) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus sesi \'{{ $day->full_session_title }}\'? Data presensi pada sesi ini akan terhapus.')" class="px-3 py-1.5 bg-rose-100 hover:bg-rose-200 text-rose-800 rounded-lg text-xs font-bold transition flex items-center gap-1" title="Hapus Sesi">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-slate-400 italic">
                        Belum ada sesi acara yang dibuat.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Add Form Card -->
        <div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                <h3 class="font-bold text-slate-900 text-sm mb-4 border-b border-slate-100 pb-3">
                    + Tambah &amp; Luncurkan Sesi Baru
                </h3>
                <form method="POST" action="{{ route('event-days.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Nama Hari</label>
                        <input type="text" name="day_name" placeholder="Contoh: Hari 1, Hari 2" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Nama Sesi Acara</label>
                        <input type="text" name="session_name" placeholder="Contoh: Sesi Pagi - Orientasi Kampus" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Tanggal Kegiatan</label>
                        <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:bg-white">
                    </div>

                    <div class="pt-2 border-t border-slate-100">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="launch_now" value="1" checked class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                            <span class="text-xs font-bold text-slate-800">Luncurkan Sesi Ini Sekarang</span>
                        </label>
                        <p class="text-[10px] text-slate-400 mt-1">Otomatis menutup sesi lama dan mengaktifkan sesi baru ini pada portal presensi.</p>
                    </div>

                    <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2">
                        🚀 Simpan &amp; Luncurkan Sesi
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
