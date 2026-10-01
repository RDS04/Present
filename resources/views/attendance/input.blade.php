<x-layouts.app title="Input Presensi Massal - PKKMB">
    <!-- Breadcrumb & Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('attendance.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 mb-1">
                &larr; Kembali Pilih Sesi &amp; Gugus
            </a>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Form Presensi Massal</h1>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" onclick="setAllStatus('hadir')" class="px-3.5 py-2 bg-emerald-100 hover:bg-emerald-200 text-emerald-800 font-bold text-xs rounded-xl transition border border-emerald-300 flex items-center gap-1.5 shadow-xs">
                <span>✓</span> Set Semua Hadir
            </button>
            <button type="button" onclick="setAllStatus('alpa')" class="px-3.5 py-2 bg-rose-100 hover:bg-rose-200 text-rose-800 font-bold text-xs rounded-xl transition border border-rose-300 flex items-center gap-1.5 shadow-xs">
                <span>&times;</span> Set Semua Alpa
            </button>
        </div>
    </div>

    <!-- Info Detail Card -->
    <div class="mb-6 bg-white p-5 rounded-2xl border border-slate-200 shadow-xs grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-lg">
                📆
            </div>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Sesi Acara</span>
                <span class="text-sm font-bold text-slate-900">{{ $eventDay->full_session_title }}</span>
                <span class="text-xs text-slate-500 block">{{ \Carbon\Carbon::parse($eventDay->date)->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-lg">
                👥
            </div>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Gugus PKKMB</span>
                <span class="text-sm font-bold text-slate-900">{{ $group->name }}</span>
                <span class="text-xs text-slate-500 block">PJ: {{ $group->pjUser->name ?? '-' }}</span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 font-bold flex items-center justify-center text-lg">
                🎓
            </div>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Mahasiswa</span>
                <span class="text-sm font-bold text-slate-900">{{ $students->count() }} Mahasiswa</span>
                <span class="text-xs text-slate-500 block">Siap Absen</span>
            </div>
        </div>
    </div>

    <!-- Main Form Table -->
    <form method="POST" action="{{ route('attendance.bulk') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="event_day_id" value="{{ $eventDay->id }}">
        <input type="hidden" name="group_id" value="{{ $group->id }}">

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase tracking-wider text-[11px] font-bold">
                        <tr>
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4 w-32">NIM</th>
                            <th class="py-3.5 px-4">Nama Mahasiswa</th>
                            <th class="py-3.5 px-4 hidden lg:table-cell">Prodi / Fakultas</th>
                            <th class="py-3.5 px-4 min-w-[320px]">Status Kehadiran</th>
                            <th class="py-3.5 px-4 min-w-[220px]">Catatan / Bukti File</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($students as $index => $student)
                            @php
                                $current = $attendancesMap[$student->id] ?? ['status' => 'hadir', 'notes' => '', 'proof_file_path' => null];
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4 text-center text-slate-400 font-bold">
                                    {{ $index + 1 }}
                                </td>
                                <td class="py-3.5 px-4 font-mono font-bold text-slate-800">
                                    {{ $student->nim }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900 text-sm">{{ $student->name }}</div>
                                    <div class="text-[11px] text-slate-400 lg:hidden">{{ $student->study_program }}</div>
                                </td>
                                <td class="py-3.5 px-4 hidden lg:table-cell text-slate-600">
                                    <div>{{ $student->study_program }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $student->faculty }}</div>
                                </td>

                                <!-- Status Radio Group Custom Badges -->
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <!-- Hadir -->
                                        <label class="cursor-pointer">
                                            <input type="radio" 
                                                   name="attendances[{{ $student->id }}][status]" 
                                                   value="hadir" 
                                                   class="peer sr-only status-radio-{{ $student->id }}"
                                                   data-student="{{ $student->id }}"
                                                   data-value="hadir"
                                                   {{ $current['status'] === 'hadir' ? 'checked' : '' }}>
                                            <span class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 text-xs font-bold peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 peer-checked:shadow-xs transition inline-block">
                                                Hadir
                                            </span>
                                        </label>

                                        <!-- Izin -->
                                        <label class="cursor-pointer">
                                            <input type="radio" 
                                                   name="attendances[{{ $student->id }}][status]" 
                                                   value="izin" 
                                                   class="peer sr-only status-radio-{{ $student->id }}"
                                                   data-student="{{ $student->id }}"
                                                   data-value="izin"
                                                   {{ $current['status'] === 'izin' ? 'checked' : '' }}>
                                            <span class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 text-xs font-bold peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-500 peer-checked:shadow-xs transition inline-block">
                                                Izin
                                            </span>
                                        </label>

                                        <!-- Sakit -->
                                        <label class="cursor-pointer">
                                            <input type="radio" 
                                                   name="attendances[{{ $student->id }}][status]" 
                                                   value="sakit" 
                                                   class="peer sr-only status-radio-{{ $student->id }}"
                                                   data-student="{{ $student->id }}"
                                                   data-value="sakit"
                                                   {{ $current['status'] === 'sakit' ? 'checked' : '' }}>
                                            <span class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 text-xs font-bold peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 peer-checked:shadow-xs transition inline-block">
                                                Sakit
                                            </span>
                                        </label>

                                        <!-- Alpa -->
                                        <label class="cursor-pointer">
                                            <input type="radio" 
                                                   name="attendances[{{ $student->id }}][status]" 
                                                   value="alpa" 
                                                   class="peer sr-only status-radio-{{ $student->id }}"
                                                   data-student="{{ $student->id }}"
                                                   data-value="alpa"
                                                   {{ $current['status'] === 'alpa' ? 'checked' : '' }}>
                                            <span class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 text-xs font-bold peer-checked:bg-rose-600 peer-checked:text-white peer-checked:border-rose-600 peer-checked:shadow-xs transition inline-block">
                                                Alpa
                                            </span>
                                        </label>
                                    </div>
                                </td>

                                <!-- Notes & File Upload -->
                                <td class="py-3.5 px-4 space-y-2">
                                    <input type="text" 
                                           name="attendances[{{ $student->id }}][notes]" 
                                           value="{{ old("attendances.{$student->id}.notes", $current['notes']) }}" 
                                           placeholder="Keterangan / Alasan (Opsional)" 
                                           class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-normal focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">

                                    <div class="flex items-center gap-2">
                                        <input type="file" 
                                               name="attendances[{{ $student->id }}][proof_file]" 
                                               class="text-[10px] text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-[10px] file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                        @if($current['proof_file_path'])
                                            <a href="{{ asset('storage/' . $current['proof_file_path']) }}" target="_blank" class="text-[10px] text-indigo-600 underline font-bold shrink-0">
                                                Lihat File
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400 italic">
                                    Belum ada mahasiswa terdaftar dalam gugus ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($students->isNotEmpty())
        <div class="sticky bottom-4 z-40 bg-white/90 backdrop-blur-md p-4 rounded-2xl border border-slate-300 shadow-xl flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-600">Pastikan status presensi seluruh mahasiswa telah sesuai sebelum menyimpan.</span>
            <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-sm rounded-xl shadow-lg transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Presensi Massal
            </button>
        </div>
        @endif
    </form>

    <script>
        function setAllStatus(targetStatus) {
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                if (radio.value === targetStatus) {
                    radio.checked = true;
                }
            });
        }
    </script>
</x-layouts.app>
