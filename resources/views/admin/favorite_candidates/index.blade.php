<x-layouts.app title="Kelola Voting Panitia Terfavorit - Admin">
    <!-- Header Page Title -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                👑 Kelola Voting Panitia Terfavorit
            </h1>
            <p class="text-slate-500 text-sm mt-1">Upload foto kandidat, kelola nominasi panitia, dan pantau statistik hasil perolehan suara voting.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('voting.panitia') }}" target="_blank" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-extrabold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                <svg class="w-4 h-4 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span>Tampilan Voting Public ↗</span>
            </a>
            <form method="POST" action="{{ route('favorite-candidates.reset-votes') }}" onsubmit="confirmResetAll(event, this)">
                @csrf
                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>🔄 Reset Semua Voting</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Statistic Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <!-- Total Candidates Card -->
        <div class="p-5 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Nominasi</span>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">{{ $totalCandidates }} <span class="text-xs text-slate-500 font-normal">Orang</span></div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xl">
                👥
            </div>
        </div>

        <!-- Total Votes Card -->
        <div class="p-5 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Suara Masuk</span>
                <div class="text-2xl sm:text-3xl font-black text-indigo-600 mt-1">{{ number_format($totalVotes) }} <span class="text-xs text-slate-500 font-normal">Suara</span></div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xl">
                🗳️
            </div>
        </div>

        <!-- Highest Votes Card -->
        <div class="p-5 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Perolehan Suara Tertinggi</span>
                @if($topCandidate && $topCandidate->votes_count > 0)
                    <div class="text-base font-black text-amber-600 truncate mt-1 max-w-[180px]" title="{{ $topCandidate->name }}">
                        🥇 {{ $topCandidate->name }}
                    </div>
                    <div class="text-xs text-slate-500 font-semibold">{{ $topCandidate->votes_count }} Suara ({{ $totalVotes > 0 ? round(($topCandidate->votes_count / $totalVotes) * 100, 1) : 0 }}%)</div>
                @else
                    <div class="text-sm font-semibold text-slate-400 mt-1">Belum ada suara</div>
                @endif
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center font-bold text-xl">
                🏆
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Form Tambah Kandidat (4 Columns) -->
        <div class="lg:col-span-4 space-y-6">
            <div class="p-6 bg-white rounded-3xl border border-slate-200 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100 flex items-center gap-2">
                    ➕ Tambah Kakak Panitia Baru
                </h2>

                <form method="POST" action="{{ route('favorite-candidates.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <!-- Nama Kakak Panitia -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Nama Panggilan Kakak Panitia <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name') }}"
                               placeholder="Contoh: Kak Budi Santoso" 
                               required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition">
                    </div>

                    <!-- Seksi / Divisi -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Seksi / Divisi Panitia <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="section" 
                               value="{{ old('section') }}"
                               placeholder="Contoh: Seksi Acara / Pendamping Gugus / Humas" 
                               required 
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition">
                    </div>

                    <!-- Upload Foto -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Upload Foto Panitia <span class="text-rose-500">*</span>
                        </label>
                        <input type="file" 
                               name="photo" 
                               id="photoInput"
                               accept="image/*" 
                               onchange="previewPhoto(event)"
                               required 
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200 cursor-pointer">
                        <p class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG, WEBP. Maksimal 3MB.</p>
                        
                        <!-- Image Preview Box -->
                        <div id="imagePreviewContainer" class="hidden mt-3 p-2 bg-slate-50 rounded-2xl border border-slate-200 text-center">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Preview Foto:</span>
                            <img id="imagePreview" src="" alt="Preview" class="h-44 w-full object-cover rounded-xl mx-auto border border-slate-200">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white font-extrabold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2">
                        💾 Simpan Nominasi Panitia
                    </button>
                </form>
            </div>
        </div>

        <!-- Daftar Kandidat & Real-time Vote Stats (8 Columns) -->
        <div class="lg:col-span-8">
            <div class="p-6 bg-white rounded-3xl border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        📋 Daftar Candidate &amp; Hasil Perolehan Suara
                    </h2>
                    <span class="text-xs text-slate-500 font-semibold">Urut Suara Terbanyak</span>
                </div>

                @if($candidates->isEmpty())
                    <div class="text-center py-12 px-4 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                        <div class="text-5xl mb-2">📸</div>
                        <h3 class="text-sm font-bold text-slate-800">Belum Ada Kandidat Panitia</h3>
                        <p class="text-xs text-slate-500 mt-1">Gunakan form di sebelah kiri untuk menambahkan nominasi Kakak Panitia baru beserta fotonya.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($candidates as $index => $candidate)
                            @php
                                $percent = $totalVotes > 0 ? round(($candidate->votes_count / $totalVotes) * 100, 1) : 0;
                            @endphp

                            <div class="p-4 bg-slate-50 hover:bg-slate-100/80 rounded-2xl border border-slate-200/80 transition flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4 flex-1 w-full sm:w-auto">
                                    <!-- Rank Badge -->
                                    <div class="w-8 h-8 rounded-full {{ $index === 0 && $candidate->votes_count > 0 ? 'bg-amber-400 text-slate-950 font-black' : ($index === 1 && $candidate->votes_count > 0 ? 'bg-slate-300 text-slate-900 font-bold' : ($index === 2 && $candidate->votes_count > 0 ? 'bg-amber-700 text-white font-bold' : 'bg-slate-200 text-slate-600 font-semibold')) }} flex items-center justify-center text-xs shrink-0 shadow-sm">
                                        #{{ $index + 1 }}
                                    </div>

                                    <!-- Candidate Photo -->
                                    <img src="{{ $candidate->photo_url }}" 
                                         alt="{{ $candidate->name }}" 
                                         class="w-14 h-14 rounded-2xl object-cover border border-slate-200 shadow-sm shrink-0">

                                    <!-- Details -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <h3 class="font-extrabold text-sm text-slate-900 truncate">
                                                {{ $candidate->name }}
                                            </h3>
                                            <span class="px-2 py-0.5 rounded-full bg-purple-100 text-purple-700 text-[10px] font-bold">
                                                {{ $candidate->section }}
                                            </span>
                                        </div>

                                        @if($candidate->description)
                                            <p class="text-xs text-slate-500 line-clamp-1 italic mt-0.5">
                                                &ldquo;{{ $candidate->description }}&rdquo;
                                            </p>
                                        @endif

                                        <!-- Progress Bar Vote -->
                                        <div class="mt-2 w-full bg-slate-200 rounded-full h-2 overflow-hidden flex">
                                            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 h-2 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Vote Count & Actions -->
                                <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto gap-4 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-200/60">
                                    <div class="text-left sm:text-right">
                                        <div class="font-black text-lg text-slate-900 leading-none">
                                            {{ number_format($candidate->votes_count) }} <span class="text-xs font-semibold text-slate-500">Suara</span>
                                        </div>
                                        <div class="text-[11px] font-bold text-indigo-600 mt-0.5">
                                            {{ $percent }}% Dari Total
                                        </div>
                                    </div>

                                    <!-- Reset Single Candidate Vote Button -->
                                    @if($candidate->votes_count > 0)
                                        <form method="POST" action="{{ route('favorite-candidates.reset-candidate', $candidate->id) }}" onsubmit="confirmResetCandidate(event, this, '{{ addslashes($candidate->name) }}', {{ $candidate->votes_count }})">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200/80 rounded-xl transition flex items-center gap-1 font-extrabold text-xs shadow-sm" title="Reset Suara Kandidat Ini">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                <span>Reset Suara</span>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Delete Button -->
                                    <form method="POST" action="{{ route('favorite-candidates.destroy', $candidate->id) }}" onsubmit="confirmDeleteCandidate(event, this, '{{ addslashes($candidate->name) }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-xl transition" title="Hapus Kandidat">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>

    <!-- SweetAlert2 Library & Custom Dialog Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function previewPhoto(event) {
            const input = event.target;
            const container = document.getElementById('imagePreviewContainer');
            const preview = document.getElementById('imagePreview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                container.classList.add('hidden');
            }
        }

        // SweetAlert2 Confirmation: Reset All Votes
        function confirmResetAll(event, form) {
            event.preventDefault();
            Swal.fire({
                title: 'Reset Semua Voting?',
                text: 'Apakah Anda yakin ingin MERESET SEMUA HAK SUARA DARI SELURUH KANDIDAT kembali ke 0? Seluruh peserta dapat melakukan voting ulang.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Reset Semua!',
                cancelButtonText: 'Tidak / Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl font-bold px-4 py-2',
                    cancelButton: 'rounded-xl font-bold px-4 py-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        // SweetAlert2 Confirmation: Reset Per-Candidate Vote
        function confirmResetCandidate(event, form, candidateName, votesCount) {
            event.preventDefault();
            Swal.fire({
                title: 'Reset Suara Kandidat?',
                text: `Apakah Anda yakin ingin mereset ${votesCount} suara kandidat "${candidateName}" kembali ke 0?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d97706',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Reset Suara!',
                cancelButtonText: 'Tidak / Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl font-bold px-4 py-2',
                    cancelButton: 'rounded-xl font-bold px-4 py-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        // SweetAlert2 Confirmation: Delete Candidate
        function confirmDeleteCandidate(event, form, candidateName) {
            event.preventDefault();
            Swal.fire({
                title: 'Hapus Kandidat?',
                text: `Apakah Anda yakin ingin menghapus kandidat "${candidateName}"? Foto dan data perolehan suara akan terhapus secara permanen.`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Tidak / Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl font-bold px-4 py-2',
                    cancelButton: 'rounded-xl font-bold px-4 py-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3500,
                showConfirmButton: false,
                customClass: { popup: 'rounded-2xl' }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                customClass: { popup: 'rounded-2xl' }
            });
        @endif
    </script>
</x-layouts.app>
