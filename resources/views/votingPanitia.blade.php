<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Voting Kakak Panitia Terfavorit - PKKMB 2026</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (Vite + CDN fallback) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-panel {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .card-glow:hover {
            box-shadow: 0 0 30px -5px rgba(124, 58, 237, 0.3);
        }
        .gold-glow {
            box-shadow: 0 0 35px 2px rgba(245, 158, 11, 0.35);
        }
    </style>
</head>
<body class="min-h-full bg-slate-950 text-slate-100 flex flex-col antialiased selection:bg-purple-500 selection:text-white">

    <!-- Background Image & Decorative Gradients -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat transition-transform duration-1000 scale-105"
             style="background-image: url('{{ asset('gedung.png') }}');">
            <div class="absolute inset-0 bg-gradient-to-b from-slate-950/90 via-slate-950/85 to-slate-950 backdrop-blur-[4px]"></div>
        </div>
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl"></div>
        <div class="absolute top-1/3 -right-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 left-1/3 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Header Navigation -->
    <header class="sticky top-0 z-40 glass-panel border-b border-white/10 shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <!-- Brand Title -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-gradient-to-tr from-purple-600 to-indigo-500 flex items-center justify-center font-black text-xl text-yellow-300 shadow-lg shadow-purple-500/30">
                        🏆
                    </div>
                    <div>
                        <h1 class="font-extrabold text-base sm:text-xl tracking-tight text-white flex items-center gap-2">
                            VOTING <span class="bg-gradient-to-r from-yellow-300 via-amber-400 to-yellow-500 bg-clip-text text-transparent">PANITIA TERFAVORIT</span>
                        </h1>
                        <p class="text-[11px] sm:text-xs text-indigo-300 font-medium">PKKMB 2026 &bull; Suarakan Apresiasimu untuk Kakak Panitia Terbaik</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10">

        <!-- Status Banner: Jika Sudah Voting -->
        <div id="votedNoticeBanner" class="{{ $hasVoted ? '' : 'hidden' }} mb-8 p-5 sm:p-6 rounded-3xl bg-gradient-to-r from-amber-950/70 via-slate-900 to-slate-900 border border-amber-500/40 shadow-2xl backdrop-blur-md gold-glow">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/20 border border-amber-500/40 text-amber-400 flex items-center justify-center font-black text-2xl shrink-0 shadow-inner">
                        ✅
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-amber-500/20 text-amber-300 font-extrabold text-[11px] uppercase tracking-wider mb-1 border border-amber-500/30">
                            Hak Suara Telah Digunakan
                        </div>
                        <h2 class="text-base sm:text-lg font-bold text-white">
                            Terima Kasih atas Partisipasimu!
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-300 mt-0.5">
                            Kamu telah memberikan voting untuk: 
                            <strong id="votedCandidateNameDisplay" class="text-amber-300 font-extrabold">{{ $votedCandidate ? $votedCandidate->name : 'Kandidat Pilihanmu' }}</strong>
                            @if($votedCandidate && $votedCandidate->section)
                                <span class="text-slate-400">({{ $votedCandidate->section }})</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="px-4 py-2 rounded-xl bg-slate-950/80 border border-white/10 text-xs font-semibold text-slate-400 text-center sm:text-right shrink-0">
                    🔒 1 Perangkat = 1 Kali Voting
                </div>
            </div>
        </div>

        <!-- Banner Hero Intro (Jika Belum Voting) -->
        <div id="heroBanner" class="{{ $hasVoted ? 'hidden' : '' }} mb-8 p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-purple-900/60 via-indigo-950/80 to-slate-950 border border-purple-500/30 shadow-2xl relative overflow-hidden backdrop-blur-xl">
            <div class="relative z-10 max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/20 text-purple-300 font-extrabold text-xs uppercase tracking-widest border border-purple-500/30 mb-3">
                    🔥 Voting Resmi Panitia Terfavorit
                </div>
                <h2 class="text-2xl sm:text-4xl font-black text-white tracking-tight leading-tight">
                    Pilih Kakak Panitia Impian &amp; Terfavoritmu!
                </h2>
                <p class="text-xs sm:text-base text-slate-300 mt-2 font-medium leading-relaxed">
                    Siapakah Kakak Panitia yang paling ramah, seru, dan berkesan selama pelaksanaan PKKMB 2026? Klik foto atau tombol voting pada kandidat pilihanmu. <span class="text-amber-400 font-bold">Setiap pengguna hanya dapat memilih 1 kali.</span>
                </p>
            </div>
            <!-- Decorative Icon -->
            <div class="absolute right-4 bottom-0 opacity-15 hidden md:block pointer-events-none">
                <span class="text-[160px] leading-none">👑</span>
            </div>
        </div>

        <!-- Filter & Search Controls Bar -->
        <div class="mb-8 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
            <!-- Search Input -->
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" 
                       id="searchInput" 
                       placeholder="Cari nama atau divisi kakak panitia..." 
                       onkeyup="filterCandidates()"
                       class="w-full pl-10 pr-4 py-3 bg-slate-900/90 border border-white/15 rounded-2xl text-xs sm:text-sm text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition shadow-inner">
            </div>

            <!-- Seksi / Divisi Pills Filter -->
            <div class="flex items-center gap-1.5 overflow-x-auto py-1 scrollbar-none">
                <button type="button" 
                        onclick="filterBySection('all')" 
                        id="filter-pill-all"
                        class="section-filter-btn px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap bg-purple-600 text-white shadow-lg transition">
                    Semua Panitia
                </button>
                @foreach($sections as $sec)
                    <button type="button" 
                            onclick="filterBySection('{{ Str::slug($sec) }}')" 
                            id="filter-pill-{{ Str::slug($sec) }}"
                            class="section-filter-btn px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap bg-slate-900 border border-white/10 text-slate-300 hover:bg-white/10 hover:text-white transition">
                        {{ $sec }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Grid Cards Candidates -->
        @if($candidates->isEmpty())
            <div class="text-center py-16 px-4 glass-panel rounded-3xl border border-white/10">
                <div class="text-6xl mb-4">📷</div>
                <h3 class="text-lg font-bold text-white">Belum Ada Kandidat Panitia</h3>
                <p class="text-xs text-slate-400 mt-1">Admin belum menginputkan foto dan data kandidat Panitia Terfavorit.</p>
            </div>
        @else
            <div id="candidatesGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($candidates as $candidate)
                    @php
                        $isThisVoted = ($hasVoted && $votedCandidateId === $candidate->id);
                        $sectionSlug = Str::slug($candidate->section ?? 'lainnya');
                    @endphp

                    <div class="candidate-card group relative bg-slate-900/80 rounded-3xl border {{ $isThisVoted ? 'border-amber-400 ring-2 ring-amber-400/50 gold-glow' : 'border-white/10 hover:border-purple-500/50' }} overflow-hidden transition-all duration-300 card-glow flex flex-col"
                         data-name="{{ strtolower($candidate->name) }}"
                         data-section="{{ strtolower($candidate->section) }}"
                         data-section-slug="{{ $sectionSlug }}">
                        
                        <!-- Top Ribbon Badge jika dipilih -->
                        @if($isThisVoted)
                            <div class="absolute top-3 right-3 z-20 px-3 py-1 bg-gradient-to-r from-amber-500 to-yellow-400 text-slate-950 font-black text-[10px] uppercase tracking-wider rounded-full shadow-lg border border-amber-300 flex items-center gap-1 animate-pulse">
                                ⭐ PILIHAN ANDA
                            </div>
                        @endif

                        <!-- Divisi / Seksi Pill Badge -->
                        <div class="absolute top-3 left-3 z-20 px-3 py-1 bg-slate-950/80 backdrop-blur-md text-purple-300 font-extrabold text-[10px] rounded-full border border-purple-500/30">
                            {{ $candidate->section ?? 'Panitia' }}
                        </div>

                        <!-- Image Container (Clickable for Modal Preview) -->
                        <div class="relative aspect-[3/4] overflow-hidden bg-slate-950 cursor-pointer group" onclick="openCandidateModal({{ json_encode($candidate) }})">
                            <img src="{{ $candidate->photo_url }}" 
                                 alt="{{ $candidate->name }}" 
                                 class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500">
                            
                            <!-- Overlay Gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent opacity-80 group-hover:opacity-70 transition-opacity"></div>
                            
                            <!-- Quick View Hint on Hover -->
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-slate-950/40 backdrop-blur-[2px]">
                                <span class="px-4 py-2 rounded-2xl bg-white/20 border border-white/30 text-white font-extrabold text-xs shadow-xl flex items-center gap-1.5">
                                    🔍 Lihat Detail Foto
                                </span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 flex-1 flex flex-col justify-between relative z-10 bg-slate-900/90">
                            <div>
                                <h3 class="text-lg font-black text-white group-hover:text-purple-300 transition-colors line-clamp-1">
                                    {{ $candidate->name }}
                                </h3>
                                <p class="text-xs text-purple-300/80 font-semibold mb-2">
                                    {{ $candidate->section }}
                                </p>
                                
                                @if($candidate->description)
                                    <p class="text-xs text-slate-400 line-clamp-2 italic font-normal mb-4">
                                        &ldquo;{{ $candidate->description }}&rdquo;
                                    </p>
                                @else
                                    <p class="text-xs text-slate-500 italic mb-4">
                                        Nominasi Panitia Terfavorit PKKMB 2026.
                                    </p>
                                @endif
                            </div>

                            <!-- Vote Action Button -->
                            <div class="pt-3 border-t border-white/10 mt-auto">
                                @if($isThisVoted)
                                    <button type="button" 
                                            disabled 
                                            class="w-full py-3 px-4 rounded-2xl bg-amber-500 text-slate-950 font-black text-xs uppercase tracking-wider cursor-default shadow-lg flex items-center justify-center gap-2">
                                        ✓ Telah Kamu Vote
                                    </button>
                                @elseif($hasVoted)
                                    <button type="button" 
                                            disabled 
                                            class="w-full py-3 px-4 rounded-2xl bg-slate-800 text-slate-500 font-bold text-xs cursor-not-allowed border border-white/5 opacity-60">
                                        Voting Selesai
                                    </button>
                                @else
                                    <button type="button" 
                                            onclick="openVoteConfirmation({{ json_encode($candidate) }})"
                                            class="vote-btn-action w-full py-3 px-4 rounded-2xl bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-700 hover:from-purple-500 hover:to-indigo-500 text-white font-extrabold text-xs uppercase tracking-wider transition-all transform hover:-translate-y-0.5 active:translate-y-0 shadow-lg shadow-purple-600/30 flex items-center justify-center gap-2">
                                        <span>🗳️ Vote Kakak Ini</span>
                                    </button>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
            
            <!-- Empty state when searching -->
            <div id="noCandidatesFound" class="hidden text-center py-16 px-4 glass-panel rounded-3xl border border-white/10 mt-6">
                <div class="text-5xl mb-3">🔍</div>
                <h3 class="text-base font-bold text-white">Panitia Tidak Ditemukan</h3>
                <p class="text-xs text-slate-400 mt-1">Tidak ada nama panitia yang cocok dengan kata kunci pencarian Anda.</p>
            </div>
        @endif

    </main>

    <!-- Modal Preview Detail Candidate -->
    <div id="candidateModal" class="fixed inset-0 z-50 hidden bg-slate-955/90 backdrop-blur-md flex items-center justify-center p-4 transition-opacity duration-300">
        <div class="bg-slate-900 border border-white/20 rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl relative text-white animate-fade-in">
            <!-- Close Button -->
            <button type="button" onclick="closeCandidateModal()" class="absolute top-4 right-4 z-30 w-9 h-9 rounded-full bg-slate-950/70 border border-white/20 text-slate-300 hover:text-white flex items-center justify-center text-lg font-bold transition">
                &times;
            </button>

            <!-- Image Header -->
            <div class="relative aspect-[4/3] bg-slate-950 overflow-hidden">
                <img id="modalPhoto" src="" alt="" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>
                
                <div class="absolute bottom-4 left-5 right-5">
                    <span id="modalSection" class="inline-block px-3 py-1 rounded-full bg-purple-600 text-white text-[10px] font-extrabold uppercase tracking-wider mb-1 shadow">
                        Divisi
                    </span>
                    <h3 id="modalName" class="text-2xl font-black text-white leading-tight drop-shadow-md">
                        Nama Panitia
                    </h3>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-4">
                <div>
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-purple-400 mb-1">
                        Tentang Kakak Panitia
                    </h4>
                    <p id="modalDescription" class="text-xs sm:text-sm text-slate-300 leading-relaxed italic bg-slate-950/60 p-4 rounded-2xl border border-white/5">
                        Deskripsi
                    </p>
                </div>

                <div id="modalVoteActionContainer" class="pt-2">
                    <!-- Button will be populated dynamically -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Vote & Input Form Nama/NIM -->
    <div id="confirmVoteModal" class="fixed inset-0 z-50 hidden bg-slate-950/90 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-purple-500/40 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl relative text-white text-left">
            <div class="w-16 h-16 rounded-3xl bg-purple-600/20 border border-purple-500/40 text-purple-400 flex items-center justify-center font-black text-3xl mx-auto mb-4 shadow-inner">
                👑
            </div>

            <h3 class="text-xl font-black text-white text-center mb-1">
                Formulir Voting Kakak Panitia
            </h3>
            <p class="text-xs text-slate-300 text-center mb-5">
                Pilihan Anda: <strong id="confirmCandidateName" class="text-amber-300 font-extrabold">--</strong> (<span id="confirmCandidateSection">--</span>)
            </p>

            <form id="voteForm" onsubmit="event.preventDefault(); submitVoteProcess();" class="space-y-4">
                <div>
                    <label for="voterNameInput" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
                        Nama Lengkap <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" 
                           id="voterNameInput" 
                           required 
                           placeholder="Masukkan Nama Lengkap Anda" 
                           class="w-full px-4 py-3 bg-slate-950 border border-white/15 rounded-2xl text-xs sm:text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                    <p id="voterNameError" class="hidden text-rose-400 text-[11px] mt-1 font-semibold"></p>
                </div>

                <div>
                    <label for="voterNimInput" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
                        NIM (Nomor Induk Mahasiswa) <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" 
                           id="voterNimInput" 
                           required 
                           placeholder="Masukkan NIM Anda" 
                           class="w-full px-4 py-3 bg-slate-950 border border-white/15 rounded-2xl text-xs sm:text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                    <p id="voterNimError" class="hidden text-rose-400 text-[11px] mt-1 font-semibold"></p>
                </div>

                <div class="p-3 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-300 text-[11px] leading-relaxed">
                    ⚠️ <strong>Perhatian:</strong> Pastikan Nama &amp; NIM terisi dengan benar. Hak suara hanya dapat digunakan 1 kali dan pilihan tidak dapat diubah setelah dikirim.
                </div>

                <div class="flex items-center justify-center gap-3 pt-2">
                    <button type="button" onclick="closeVoteConfirmation()" class="w-1/2 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs rounded-2xl transition">
                        Batal
                    </button>
                    <button type="submit" id="btnSubmitVote" class="w-1/2 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-extrabold text-xs rounded-2xl shadow-lg transition flex items-center justify-center gap-2">
                        <span id="btnSubmitVoteText">Kirim Vote 🚀</span>
                        <span id="btnSubmitVoteSpinner" class="hidden animate-spin">⏳</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="border-t border-white/10 py-6 mt-12 relative z-10 glass-panel">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} Panitia PKKMB &bull; Pemilihan Kakak Panitia Terfavorit
        </div>
    </footer>

    <!-- JavaScript Handlers & AJAX Voting Logic -->
    <script>
        const HAS_VOTED_INITIAL = @json($hasVoted);
        let currentSelectedCandidate = null;

        // Reset LocalStorage jika di server status voting sudah di-reset oleh admin
        if (!HAS_VOTED_INITIAL) {
            localStorage.removeItem('voted_candidate_id');
            localStorage.removeItem('voted_candidate_name');
        }

        // Mendapatkan UUID perangkat voter unik
        function getDeviceUuid() {
            let uuid = localStorage.getItem('voter_device_uuid');
            if (!uuid) {
                uuid = 'dev_' + Math.random().toString(36).substring(2, 15) + Date.now().toString(36);
                localStorage.setItem('voter_device_uuid', uuid);
            }
            return uuid;
        }

        // Modal Preview Candidate
        function openCandidateModal(candidate) {
            document.getElementById('modalPhoto').src = candidate.photo_url || candidate.photo;
            document.getElementById('modalName').textContent = candidate.name;
            document.getElementById('modalSection').textContent = candidate.section || 'Panitia';
            document.getElementById('modalDescription').textContent = candidate.description ? `"${candidate.description}"` : 'Nominasi Panitia Terfavorit PKKMB 2026.';

            const actionContainer = document.getElementById('modalVoteActionContainer');
            
            if (HAS_VOTED_INITIAL) {
                actionContainer.innerHTML = `
                    <button disabled class="w-full py-3 rounded-2xl bg-slate-800 text-slate-500 font-bold text-xs cursor-not-allowed">
                        🔒 Anda Sudah Menggunakan Hak Suara
                    </button>
                `;
            } else {
                actionContainer.innerHTML = `
                    <button onclick='closeCandidateModal(); openVoteConfirmation(${JSON.stringify(candidate)})' class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-extrabold text-xs uppercase tracking-wider hover:from-purple-500 hover:to-indigo-500 transition shadow-lg">
                        🗳️ Pilih Kakak ${candidate.name}
                    </button>
                `;
            }

            document.getElementById('candidateModal').classList.remove('hidden');
        }

        function closeCandidateModal() {
            document.getElementById('candidateModal').classList.add('hidden');
        }

        // Modal Confirmation Vote
        function openVoteConfirmation(candidate) {
            if (HAS_VOTED_INITIAL) return;
            currentSelectedCandidate = candidate;
            document.getElementById('confirmCandidateName').textContent = candidate.name;
            document.getElementById('confirmCandidateSection').textContent = candidate.section || 'Panitia';
            
            // Clear input fields and errors
            document.getElementById('voterNameInput').value = '';
            document.getElementById('voterNimInput').value = '';
            document.getElementById('voterNameError').classList.add('hidden');
            document.getElementById('voterNimError').classList.add('hidden');

            document.getElementById('confirmVoteModal').classList.remove('hidden');
        }

        function closeVoteConfirmation() {
            document.getElementById('confirmVoteModal').classList.add('hidden');
            currentSelectedCandidate = null;
        }

        // Submit Vote AJAX Process
        async function submitVoteProcess() {
            if (!currentSelectedCandidate) return;

            const voterNameInput = document.getElementById('voterNameInput');
            const voterNimInput = document.getElementById('voterNimInput');
            const voterNameError = document.getElementById('voterNameError');
            const voterNimError = document.getElementById('voterNimError');

            const voterName = voterNameInput.value.trim();
            const voterNim = voterNimInput.value.trim();

            let hasError = false;

            if (!voterName) {
                voterNameError.textContent = 'Nama lengkap wajib diisi.';
                voterNameError.classList.remove('hidden');
                hasError = true;
            } else {
                voterNameError.classList.add('hidden');
            }

            if (!voterNim) {
                voterNimError.textContent = 'NIM wajib diisi.';
                voterNimError.classList.remove('hidden');
                hasError = true;
            } else {
                voterNimError.classList.add('hidden');
            }

            if (hasError) return;

            const btnText = document.getElementById('btnSubmitVoteText');
            const btnSpinner = document.getElementById('btnSubmitVoteSpinner');
            const btnSubmit = document.getElementById('btnSubmitVote');

            btnSubmit.disabled = true;
            btnText.textContent = 'Mengirim...';
            btnSpinner.classList.remove('hidden');

            try {
                const response = await fetch("{{ route('voting.panitia.vote') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        candidate_id: currentSelectedCandidate.id,
                        voter_name: voterName,
                        voter_nim: voterNim,
                        device_uuid: getDeviceUuid()
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Simpan status vote di LocalStorage
                    localStorage.setItem('voted_candidate_id', data.voted_candidate_id);
                    localStorage.setItem('voted_candidate_name', data.candidate_name);

                    closeVoteConfirmation();
                    
                    // SweetAlert2 Terima Kasih sudah voting & reload halaman agar UI terupdate penuh
                    Swal.fire({
                        title: '🎉 Terima Kasih!',
                        text: data.message || `Suara Anda untuk Kakak ${data.candidate_name} berhasil disimpan! Terima kasih telah berpartisipasi.`,
                        icon: 'success',
                        confirmButtonText: 'Sama-sama & Selesai',
                        confirmButtonColor: '#9333ea',
                        background: '#0f172a',
                        color: '#f8fafc',
                        customClass: {
                            popup: 'rounded-3xl border border-purple-500/40 shadow-2xl',
                            title: 'text-white font-black text-2xl',
                            htmlContainer: 'text-slate-300 text-sm font-medium',
                            confirmButton: 'rounded-2xl font-extrabold px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white shadow-lg shadow-purple-600/30'
                        }
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: data.already_voted ? '🔒 Hak Suara Telah Digunakan' : '⚠️ Perhatian',
                        text: data.message || 'Gagal mengirim voting.',
                        icon: data.already_voted ? 'info' : 'warning',
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#9333ea',
                        background: '#0f172a',
                        color: '#f8fafc',
                        customClass: {
                            popup: 'rounded-3xl border border-purple-500/40 shadow-2xl',
                            title: 'text-white font-bold text-xl',
                            htmlContainer: 'text-slate-300 text-sm',
                            confirmButton: 'rounded-2xl font-bold px-6 py-2.5 bg-purple-600 text-white'
                        }
                    }).then(() => {
                        if (data.already_voted) {
                            window.location.reload();
                        }
                    });
                }
            } catch (err) {
                console.error(err);
                Swal.fire({
                    title: '⚠️ Kesalahan Jaringan',
                    text: 'Terjadi kesalahan koneksi internet. Silakan periksa jaringan Anda dan coba lagi.',
                    icon: 'error',
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#e11d48',
                    background: '#0f172a',
                    color: '#f8fafc',
                    customClass: {
                        popup: 'rounded-3xl border border-rose-500/40 shadow-2xl'
                    }
                });
            } finally {
                btnSubmit.disabled = false;
                btnText.textContent = 'Ya, Kirim Vote';
                btnSpinner.classList.add('hidden');
            }
        }

        // Search & Filter Candidates
        function filterCandidates() {
            const query = document.getElementById('searchInput').value.toLowerCase().trim();
            const cards = document.querySelectorAll('.candidate-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const name = card.getAttribute('data-name');
                const section = card.getAttribute('data-section');
                
                if (name.includes(query) || section.includes(query)) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            const noFound = document.getElementById('noCandidatesFound');
            if (noFound) {
                if (visibleCount === 0 && cards.length > 0) {
                    noFound.classList.remove('hidden');
                } else {
                    noFound.classList.add('hidden');
                }
            }
        }

        function filterBySection(slug) {
            // Update active pill button style
            document.querySelectorAll('.section-filter-btn').forEach(btn => {
                btn.classList.remove('bg-purple-600', 'text-white');
                btn.classList.add('bg-slate-900', 'text-slate-300', 'border', 'border-white/10');
            });

            const activeBtn = document.getElementById('filter-pill-' + slug);
            if (activeBtn) {
                activeBtn.classList.remove('bg-slate-900', 'text-slate-300', 'border', 'border-white/10');
                activeBtn.classList.add('bg-purple-600', 'text-white');
            }

            const cards = document.querySelectorAll('.candidate-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const cardSecSlug = card.getAttribute('data-section-slug');
                if (slug === 'all' || cardSecSlug === slug) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            const noFound = document.getElementById('noCandidatesFound');
            if (noFound) {
                if (visibleCount === 0 && cards.length > 0) {
                    noFound.classList.remove('hidden');
                } else {
                    noFound.classList.add('hidden');
                }
            }
        }
    </script>
</body>
</html>
