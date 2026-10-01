<x-layouts.app title="Rekapitulasi Kehadiran - PKKMB">
    <!-- Header Title & Export Button -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Rekapitulasi Kehadiran</h1>
            <p class="text-slate-500 text-sm mt-1">Kalkulasi dan ringkasan persentase kehadiran peserta PKKMB</p>
        </div>
        <div>
            <a href="{{ route('recap.export', ['group_id' => $selectedGroupId]) }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export Excel / CSV
            </a>
        </div>
    </div>

    <!-- Summary Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Peserta Terekap</span>
                <div class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($summary['total_students']) }} Mahasiswa</div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xl">
                👥
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Sesi Acara</span>
                <div class="text-3xl font-extrabold text-indigo-600 mt-1">{{ $totalSessions }} Sesi</div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-xl">
                📅
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs mb-6">
        <form method="GET" action="{{ route('recap.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <!-- Search -->
            <div>
                <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1">Cari Mahasiswa</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Nama / NIM / Prodi..." class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:bg-white focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- Group Filter -->
            <div>
                <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1">Filter Gugus</label>
                <select name="group_id" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:bg-white focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                    <option value="">-- Semua Gugus --</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}" {{ $selectedGroupId == $group->id ? 'selected' : '' }}>
                            {{ $group->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit Filter Button -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                    Terapkan Filter
                </button>
                <a href="{{ route('recap.index') }}" class="px-3.5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-xl transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Main Recap Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase tracking-wider text-[11px] font-bold">
                    <tr>
                        <th class="py-3.5 px-4 w-12 text-center">No</th>
                        <th class="py-3.5 px-4">NIM &amp; Nama</th>
                        <th class="py-3.5 px-4">Prodi &amp; Gugus</th>
                        <th class="py-3.5 px-4 text-center">Total Hadir</th>
                        <th class="py-3.5 px-4 min-w-[200px]">Persentase Kehadiran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($recap as $index => $item)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3.5 px-4 text-center text-slate-400 font-bold">
                            {{ $index + 1 }}
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="font-bold text-slate-900 text-sm">{{ $item['name'] }}</div>
                            <div class="font-mono text-slate-500 text-[11px]">{{ $item['nim'] }}</div>
                        </td>
                        <td class="py-3.5 px-4 text-slate-600">
                            <div class="font-semibold text-slate-800">{{ $item['study_program'] }}</div>
                            <div class="text-[10px] text-indigo-600 font-bold">{{ $item['group_name'] }}</div>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold text-xs">
                                {{ $item['hadir_count'] }} Sesi
                            </span>
                        </td>

                        <!-- Visual Progress Bar -->
                        <td class="py-3.5 px-4">
                            <div class="flex items-center justify-between text-xs font-bold mb-1">
                                <span class="text-slate-800">{{ $item['percentage'] }}%</span>
                                <span class="text-[10px] text-slate-400 font-normal">dari {{ $item['total_sessions'] }} Sesi</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden border border-slate-200">
                                <div class="h-2.5 rounded-full transition-all duration-500 {{ $item['percentage'] >= 80 ? 'bg-emerald-500' : ($item['percentage'] >= 50 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ min(100, $item['percentage']) }}%"></div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400 italic">
                            Tidak ada data mahasiswa ditemukan untuk filter ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
