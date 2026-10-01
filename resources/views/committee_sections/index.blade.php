<x-layouts.app title="Kelola Seksi & Presensi Panitia - Admin">
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Kelola Seksi / Divisi Panitia PKKMB</h1>
        <p class="text-slate-500 text-sm mt-1">Tambah &amp; atur daftar seksi/divisi panitia. Data yang Anda tambahkan akan <strong>otomatis muncul pada pilihan dropdown</strong> form absensi panitia.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- List Seksi Panitia Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-4 bg-slate-50 border-b border-slate-200 font-bold text-sm text-slate-800 flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <span>👔 Daftar Seksi / Divisi Panitia</span>
                        <span class="text-[11px] font-normal text-slate-500">(Muncul di Dropdown Absen)</span>
                    </span>
                    <span class="text-xs font-bold text-purple-700 bg-purple-100 px-2.5 py-1 rounded-full">Total: {{ $sections->count() }} Seksi</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-4">
                    @forelse($sections as $section)
                    <div class="p-3.5 bg-slate-50/70 border border-slate-200 rounded-xl flex items-center justify-between hover:bg-slate-100/80 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-purple-100 text-purple-700 font-bold flex items-center justify-center text-sm shrink-0 shadow-xs">
                                👔
                            </div>
                            <div>
                                <h4 class="font-extrabold text-slate-900 text-xs sm:text-sm">{{ $section->name }}</h4>
                                <p class="text-[11px] text-slate-500 mt-0.5">
                                    Jumlah Presensi: <span class="font-bold text-purple-700">{{ $section->attendances_count }} Orang</span>
                                </p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('committee-sections.destroy', $section) }}" class="shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus Seksi {{ $section->name }}?')" class="p-1.5 bg-rose-100 hover:bg-rose-200 text-rose-800 rounded-lg text-xs font-bold transition" title="Hapus Seksi">
                                🗑️
                            </button>
                        </form>
                    </div>
                    @empty
                    <div class="col-span-2 p-8 text-center text-slate-400 italic">
                        Belum ada seksi panitia yang dibuat. Silakan tambah seksi melalui form di samping.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Add Seksi Panitia Form Card -->
        <div>
            <div class="bg-white p-5 rounded-2xl border border-purple-200 shadow-md">
                <h3 class="font-extrabold text-slate-900 text-sm mb-2 border-b border-slate-100 pb-3 flex items-center gap-2">
                    ➕ Form Input Seksi / Divisi Baru
                </h3>
                <p class="text-[11px] text-slate-500 mb-4 leading-relaxed">
                    Setiap nama seksi/divisi yang Anda inputkan di form ini akan <strong>langsung muncul secara otomatis</strong> di dropdown form absensi panitia publik.
                </p>

                <form method="POST" action="{{ route('committee-sections.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">
                            Nama Seksi / Divisi Panitia <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               placeholder="Contoh: Acara, Perlengkapan, Humas" 
                               required 
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium text-slate-900 focus:ring-2 focus:ring-purple-500 focus:bg-white transition">
                    </div>

                    <button type="submit" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white font-extrabold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-1.5 cursor-pointer">
                        ➕ Simpan Seksi Ke Dropdown
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Data Presensi Panitia Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 bg-slate-50 border-b border-slate-200 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                    📋 Rekapitulasi Data Presensi Panitia
                </h3>
                <p class="text-xs text-slate-500">Daftar kehadiran panitia PKKMB pada sesi kegiatan</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <!-- Export Buttons -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('committee-sections.export-excel', request()->query()) }}" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5">
                        📊 Export Excel
                    </a>
                    <a href="{{ route('committee-sections.export-pdf', request()->query()) }}" target="_blank" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5">
                        📄 Export PDF
                    </a>
                </div>

                <!-- Filter & Search Form -->
                <form method="GET" action="{{ route('committee-sections.index') }}" class="flex flex-wrap items-center gap-2">
                    <select name="section_id" onchange="this.form.submit()" class="px-3 py-1.5 bg-white border border-slate-300 rounded-xl text-xs font-medium text-slate-700 focus:ring-2 focus:ring-purple-500">
                        <option value="">-- Semua Seksi Panitia --</option>
                        @foreach($sections as $sec)
                            <option value="{{ $sec->id }}" {{ request('section_id') == $sec->id ? 'selected' : '' }}>
                                {{ $sec->name }}
                            </option>
                        @endforeach
                    </select>

                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari Nama/NIM..." 
                           class="px-3 py-1.5 bg-white border border-slate-300 rounded-xl text-xs font-medium text-slate-700 focus:ring-2 focus:ring-purple-500">

                    <button type="submit" class="px-3 py-1.5 bg-purple-600 text-white font-bold text-xs rounded-xl hover:bg-purple-700 transition">
                        Cari
                    </button>

                    @if(request('section_id') || request('search'))
                        <a href="{{ route('committee-sections.index') }}" class="px-3 py-1.5 bg-slate-200 text-slate-700 font-bold text-xs rounded-xl hover:bg-slate-300 transition">
                            Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">NIM</th>
                        <th class="px-4 py-3">Nama Panitia</th>
                        <th class="px-4 py-3">Seksi / Divisi</th>
                        <th class="px-4 py-3">Sesi Kegiatan</th>
                        <th class="px-4 py-3">Waktu Presensi</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($attendances as $index => $att)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-4 py-3 font-semibold text-slate-400">
                            {{ $attendances->firstItem() + $index }}
                        </td>
                        <td class="px-4 py-3 font-mono font-semibold text-slate-700">
                            {{ $att->nim ?? '-' }}
                        </td>
                        <td class="px-4 py-3 font-extrabold text-slate-900">
                            {{ $att->name }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800">
                                👔 {{ $att->committeeSection->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($att->eventDay)
                                <span class="font-bold text-slate-800">{{ $att->eventDay->full_session_title }}</span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-mono text-[11px] text-slate-500">
                            {{ $att->created_at ? $att->created_at->translatedFormat('d M Y H:i:s') : '-' }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <form method="POST" action="{{ route('committee-sections.destroy-attendance', $att) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus data presensi panitia {{ $att->name }}?')" class="px-2.5 py-1 bg-rose-100 hover:bg-rose-200 text-rose-800 rounded-lg text-[11px] font-bold transition">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400 italic">
                            Belum ada data presensi panitia.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($attendances->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $attendances->links() }}
        </div>
        @endif
    </div>
</x-layouts.app>
