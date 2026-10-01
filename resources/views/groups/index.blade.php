<x-layouts.app title="Kelola Gugus PKKMB - Admin">
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Kelola Gugus PKKMB</h1>
        <p class="text-slate-500 text-sm mt-1">Tambah, edit, dan atur daftar kelompok / gugus PKKMB yang tampil di dropdown portal presensi mahasiswa</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- List Gugus Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-4 bg-slate-50 border-b border-slate-200 font-bold text-sm text-slate-800 flex items-center justify-between">
                    <span>Daftar Gugus PKKMB</span>
                    <span class="text-xs font-normal text-slate-500">Total: {{ $groups->count() }} Gugus</span>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($groups as $group)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50/50 transition">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-sm shrink-0">
                                👥
                            </div>
                            <div>
                                <h4 class="font-extrabold text-slate-900 text-sm sm:text-base">{{ $group->name }}</h4>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Mahasiswa Terdaftar: <span class="font-bold text-slate-800">{{ $group->students_count }} Orang</span>
                                    @if($group->pjUser)
                                        &bull; PJ: <span class="font-semibold text-indigo-600">{{ $group->pjUser->name }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('groups.destroy', $group) }}" class="shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus {{ $group->name }}?')" class="px-3 py-1.5 bg-rose-100 hover:bg-rose-200 text-rose-800 rounded-xl text-xs font-bold transition flex items-center gap-1">
                                🗑️ Hapus
                            </button>
                        </form>
                    </div>
                    @empty
                    <div class="p-8 text-center text-slate-400 italic">
                        Belum ada gugus PKKMB yang dibuat. Silakan tambah gugus melalui form di samping.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Add Group Form Card -->
        <div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                <h3 class="font-bold text-slate-900 text-sm mb-4 border-b border-slate-100 pb-3 flex items-center gap-2">
                    ➕ Tambah Gugus Baru
                </h3>

                <form method="POST" action="{{ route('groups.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">
                            Nama Gugus <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               placeholder="Contoh: Gugus 01 - Garuda" 
                               required 
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    </div>

                    @if(count($pendampings) > 0)
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">
                            Penanggung Jawab / Pendamping (Opsional)
                        </label>
                        <select name="pj_user_id" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                            <option value="">-- Tanpa PJ Pendamping --</option>
                            @foreach($pendampings as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-1.5">
                        ➕ Simpan Gugus Baru
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
