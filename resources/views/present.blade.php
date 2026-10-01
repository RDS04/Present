<!DOCTYPE html>
<html lang="id" class="h-full bg-[#0B0F17]">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Portal Presensi PKKMB 2026 - Universitas Metamedia</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS CDN & SweetAlert2 -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .bg-grid-pattern {
            background-image: radial-gradient(rgba(255, 255, 255, 0.07) 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>

<body
    class="min-h-screen bg-[#0B0F17] text-slate-100 relative overflow-x-hidden flex flex-col justify-between selection:bg-sky-500 selection:text-white">

    <!-- Fullscreen Gedung Background with Elegant Dark Overlay -->
    <div class="fixed inset-0 z-0 bg-cover bg-center bg-no-repeat transition-transform duration-1000 scale-105"
        style="background-image: url('{{ asset('gedung.png') }}');">
        <!-- Professional Dark Gradient Overlay -->
        <div
            class="absolute inset-0 bg-gradient-to-b from-[#0B0F17]/90 via-[#0B0F17]/85 to-[#0B0F17] backdrop-blur-[4px]">
        </div>
        <div class="absolute inset-0 bg-grid-pattern opacity-40"></div>
    </div>

    <!-- Ambient Subtle Glow Lights (Professional Soft Radial) -->
    <div
        class="fixed -top-32 left-1/2 -translate-x-1/2 w-[600px] h-[350px] bg-sky-600/15 rounded-full blur-[140px] pointer-events-none z-0">
    </div>
    <div
        class="fixed bottom-0 right-0 w-[450px] h-[450px] bg-blue-700/10 rounded-full blur-[160px] pointer-events-none z-0">
    </div>

    <!-- Header Navigation Bar -->
    <header class="relative z-20 w-full border-b border-slate-800/70 bg-[#0B0F17]/70 backdrop-blur-xl">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Brand Logo & Institutional Title -->
            <div class="flex items-center gap-3">
                <img src="{{ asset('image.png') }}" alt="Logo Metamedia" class="w-10 h-10 object-contain">
                <div>
                    <span class="font-extrabold text-sm tracking-tight text-white block leading-none">UNIVERSITAS
                        METAMEDIA</span>
                    <span class="text-[10px] font-semibold text-sky-400 tracking-wider block mt-1 uppercase">Portal
                        Presensi PKKMB 2026</span>
                </div>
            </div>

            <!-- Right Side: Live Digital Clock -->
            <div class="flex items-center gap-3">
                <div
                    class="flex items-center gap-2.5 px-3.5 py-1.5 rounded-xl bg-slate-900/80 border border-slate-800 backdrop-blur-md shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <div class="text-right">
                        <div id="liveClock"
                            class="font-mono font-bold text-xs text-sky-300 tracking-wider leading-none">00:00:00 WIB
                        </div>
                        <div id="liveDate"
                            class="text-[10px] text-slate-400 font-medium leading-tight mt-0.5 hidden sm:block">--</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main
        class="relative z-10 flex-1 max-w-3xl w-full mx-auto px-4 sm:px-6 py-10 flex flex-col justify-center items-center">

        <!-- ================= STEP 1: HALAMAN WELCOME ================= -->
        <div id="welcomeScreen"
            class="w-full text-center flex flex-col items-center justify-center transition-all duration-500 transform opacity-100 scale-100">

            <!-- Campus Logo -->
            <div class="mb-6">
                <img src="{{ asset('image.png') }}" alt="Logo Metamedia"
                    class="w-32 h-32 sm:w-44 sm:h-44 object-contain mx-auto">
            </div>

            <!-- Tagline Badge -->
            <div
                class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-sky-500/10 border border-sky-500/20 text-sky-300 text-xs font-bold tracking-wide uppercase mb-5">
                <svg class="w-3.5 h-3.5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Portal Presensi Kehadiran Mahasiswa Baru
            </div>

            <!-- Main Title -->
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-white leading-tight mb-3">
                Pengenalan Kehidupan Kampus <br>
                <span class="bg-gradient-to-r from-sky-300 via-blue-300 to-indigo-300 bg-clip-text text-transparent">
                    PKKMB 2026
                </span>
            </h1>

            <div class="max-w-xl mx-auto mb-8 px-5 py-3.5 rounded-2xl bg-slate-900/80 border border-slate-700/60 backdrop-blur-md shadow-xl text-center">
                <p class="text-slate-200 text-xs sm:text-sm font-medium leading-relaxed">
                    Selamat datang Mahasiswa Baru Universitas Metamedia. <br class="hidden sm:inline"> Silakan melakukan verifikasi dan pengisian absensi kehadiran Anda.
                </p>
            </div>

            <!-- Active Session Status Card -->
            @if($activeSession)
                <div
                    class="w-full max-w-md mb-8 p-4 rounded-2xl bg-slate-900/80 border border-slate-800 backdrop-blur-xl flex items-center gap-4 text-left shadow-xl ring-1 ring-white/5">
                    <div
                        class="w-10 h-10 rounded-xl bg-sky-500/10 border border-sky-500/20 text-sky-400 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-sky-400">SESI AKTIF
                                BERLANGSUNG</span>
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                        </div>
                        <span
                            class="text-sm font-bold text-white block truncate mt-0.5">{{ $activeSession->full_session_title }}</span>
                    </div>
                </div>
            @else
                <div
                    class="w-full max-w-md mb-8 p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 backdrop-blur-xl flex items-center gap-4 text-left shadow-xl">
                    <div
                        class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-amber-400 block">STATUS SESI
                            PRESENSI</span>
                        <span class="text-sm font-bold text-amber-200 block mt-0.5">Sesi Presensi Belum Dibuka /
                            Selesai</span>
                    </div>
                </div>
            @endif

            <!-- Main CTA Button -->
            <div>
                <button id="ctaBtn" onclick="goToFormScreen()"
                    class="px-8 py-3.5 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-sky-500/25 transition-all duration-200 transform hover:-translate-y-0.5 flex items-center gap-2.5 cursor-pointer border border-sky-400/30">
                    <svg class="w-4 h-4 text-sky-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 210.3H3v-3.572L16.732 3.732z" />
                    </svg>
                    <span>Isi Absensi Kehadiran</span>
                    <svg class="w-4 h-4 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
                <div id="thankYouMsg"
                    class="hidden px-6 py-3.5 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 font-bold text-sm sm:text-base flex items-center justify-center gap-2.5 shadow-lg backdrop-blur-md">
                    <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Terima kasih sudah mengisi absen</span>
                </div>
            </div>

        </div>

        <!-- ================= STEP 2: HALAMAN FORM ABSENSI ================= -->
        <div id="formScreen" class="w-full hidden transition-all duration-500 transform opacity-0 scale-95">

            <!-- Navigation Back Link -->
            <div class="mb-5 flex items-center justify-between">
                <button onclick="goToWelcomeScreen()"
                    class="text-xs font-semibold text-slate-400 hover:text-white flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900/60 border border-slate-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali</span>
                </button>
                <span class="text-xs text-slate-500 font-medium">Langkah 2 dari 2</span>
            </div>

            <!-- Form Card Container -->
            <div
                class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 sm:p-9 shadow-2xl backdrop-blur-2xl relative ring-1 ring-white/5">

                <div class="flex items-center gap-4 mb-7 border-b border-slate-800/80 pb-5">
                    <div
                        class="w-11 h-11 rounded-2xl bg-sky-500/10 border border-sky-500/20 text-sky-400 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-xl font-bold text-white tracking-tight">Formulir Presensi Mahasiswa
                            Baru</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Masukkan data diri lengkap Anda sesuai identitas
                            pendaftaran</p>
                    </div>
                </div>

                <form id="attendanceForm" onsubmit="handleFormSubmit(event)" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- NIM (Opsional) -->
                        <div>
                            <label for="nim" class="block text-xs font-semibold text-slate-300 mb-1.5">
                                NIM (Nomor Induk Mahasiswa)
                            </label>
                            <input type="text" id="nim" name="nim" placeholder="Contoh: 2026101001 (opsional)"
                                class="w-full px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl text-slate-100 text-xs sm:text-sm font-medium focus:ring-2 focus:ring-sky-500/40 focus:border-sky-500 outline-none transition placeholder-slate-600">
                        </div>

                        <!-- Nama Lengkap -->
                        <div>
                            <label for="name" class="block text-xs font-semibold text-slate-300 mb-1.5">
                                Nama Lengkap <span class="text-rose-400">*</span>
                            </label>
                            <input type="text" id="name" name="name" required placeholder="Masukkan nama lengkap Anda"
                                class="w-full px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl text-slate-100 text-xs sm:text-sm font-medium focus:ring-2 focus:ring-sky-500/40 focus:border-sky-500 outline-none transition placeholder-slate-600">
                        </div>

                        <!-- No Telepon / WA -->
                        <div>
                            <label for="phone" class="block text-xs font-semibold text-slate-300 mb-1.5">
                                No. Telepon / WhatsApp
                            </label>
                            <input type="tel" id="phone" name="phone" placeholder="Contoh: 081234567890"
                                class="w-full px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl text-slate-100 text-xs sm:text-sm font-medium focus:ring-2 focus:ring-sky-500/40 focus:border-sky-500 outline-none transition placeholder-slate-600">
                        </div>

                        <!-- Jurusan / Prodi -->
                        <div>
                            <label for="study_program" class="block text-xs font-semibold text-slate-300 mb-1.5">
                                Jurusan / Program Studi <span class="text-rose-400">*</span>
                            </label>
                            <select id="study_program" name="study_program" required
                                class="w-full px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl text-slate-100 text-xs sm:text-sm font-medium focus:ring-2 focus:ring-sky-500/40 focus:border-sky-500 outline-none transition cursor-pointer">
                                <option value="">-- Pilih Program Studi --</option>
                                <option value="Informatika">Informatika</option>
                                <option value="Sistem Informasi">Sistem Informasi</option>
                                <option value="Bisnis Digital">Bisnis Digital</option>
                                <option value="Desain Komunikasi Visual">Desain Komunikasi Visual</option>
                                <option value="Manajemen Ritel">Manajemen Ritel</option>
                                <option value="Pendidikan Teknologi Informasi">Pendidikan Teknologi Informasi</option>
                            </select>
                        </div>
                    </div>

                    <!-- Gugus Select -->
                    <div>
                        <label for="group_id"
                            class="block text-xs font-semibold text-slate-300 mb-1.5 flex items-center justify-between">
                            <span>Gugus PKKMB</span>
                            <span class="text-[11px] text-slate-500 font-normal">(kosongkan jika belum memiliki
                                gugus)</span>
                        </label>
                        <select id="group_id" name="group_id"
                            class="w-full px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl text-slate-100 text-xs sm:text-sm font-medium focus:ring-2 focus:ring-sky-500/40 focus:border-sky-500 outline-none transition cursor-pointer">
                            <option value="">-- Belum Ada / Kosongkan --</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-3">
                        <button type="submit" id="submitBtn"
                            class="w-full py-3.5 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-sky-500/20 transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer border border-sky-400/30">
                            <svg class="w-5 h-5 text-sky-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Kirim Presensi Kehadiran</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer
        class="relative z-10 w-full border-t border-slate-800/60 py-4 text-center text-xs text-slate-500 backdrop-blur-md bg-[#0B0F17]/60">
        &copy; 2026 Universitas Metamedia &bull; Panitia PKKMB 2026
    </footer>

    <!-- JavaScript Handling Step Switcher & SweetAlert2 -->
    <script>
        const welcomeScreen = document.getElementById('welcomeScreen');
        const formScreen = document.getElementById('formScreen');
        const hasActiveSession = @json((bool) $activeSession);
        const activeSessionId = @json($activeSession ? $activeSession->id : null);

        function checkSubmissionStatus() {
            const ctaBtn = document.getElementById('ctaBtn');
            const thankYouMsg = document.getElementById('thankYouMsg');

            if (activeSessionId && localStorage.getItem('absen_submitted_session_' + activeSessionId) === 'true') {
                if (ctaBtn) ctaBtn.classList.add('hidden');
                if (thankYouMsg) thankYouMsg.classList.remove('hidden');
            } else {
                if (ctaBtn) ctaBtn.classList.remove('hidden');
                if (thankYouMsg) thankYouMsg.classList.add('hidden');
            }
        }

        checkSubmissionStatus();

        function goToFormScreen() {
            if (!hasActiveSession) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Sesi Belum Dibuka',
                    text: 'Mohon maaf, sesi presensi saat ini telah selesai atau belum dibuka oleh panitia. Silakan tunggu instruksi selanjutnya.',
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#0284c7',
                    background: '#0f172a',
                    color: '#f8fafc',
                    customClass: {
                        popup: 'border border-amber-500/30 rounded-2xl shadow-2xl'
                    }
                });
                return;
            }

            // Fade out welcome screen dan tampilkan form
            welcomeScreen.classList.remove('opacity-100', 'scale-100');
            welcomeScreen.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                welcomeScreen.classList.add('hidden');
                formScreen.classList.remove('hidden');

                void formScreen.offsetWidth;

                formScreen.classList.remove('opacity-0', 'scale-95');
                formScreen.classList.add('opacity-100', 'scale-100');
            }, 300);
        }

        function goToWelcomeScreen() {
            formScreen.classList.remove('opacity-100', 'scale-100');
            formScreen.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                formScreen.classList.add('hidden');
                welcomeScreen.classList.remove('hidden');

                void welcomeScreen.offsetWidth;

                welcomeScreen.classList.remove('opacity-0', 'scale-95');
                welcomeScreen.classList.add('opacity-100', 'scale-100');
            }, 300);
        }

        async function handleFormSubmit(e) {
            e.preventDefault();

            const form = document.getElementById('attendanceForm');
            const name = document.getElementById('name').value.trim();
            const studyProgram = document.getElementById('study_program').value.trim();

            if (!name || !studyProgram) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Form Belum Lengkap',
                    text: 'Mohon isi Nama Lengkap dan Jurusan secara lengkap.',
                    confirmButtonColor: '#0284c7'
                });
                return;
            }

            Swal.fire({
                title: 'Memproses Presensi...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData(form);

            try {
                const response = await fetch("{{ route('present.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    if (activeSessionId) {
                        localStorage.setItem('absen_submitted_session_' + activeSessionId, 'true');
                    }
                    checkSubmissionStatus();

                    Swal.fire({
                        icon: 'success',
                        title: 'Presensi Berhasil!',
                        text: 'Terima kasih, data kehadiran Anda telah tersimpan.',
                        confirmButtonText: 'Selesai',
                        confirmButtonColor: '#0284c7',
                        background: '#0f172a',
                        color: '#f8fafc',
                        customClass: {
                            popup: 'border border-sky-500/30 rounded-2xl shadow-2xl'
                        }
                    }).then(() => {
                        form.reset();
                        goToWelcomeScreen();
                    });
                } else if (result.already_submitted) {
                    if (activeSessionId) {
                        localStorage.setItem('absen_submitted_session_' + activeSessionId, 'true');
                    }
                    checkSubmissionStatus();

                    Swal.fire({
                        icon: 'info',
                        title: 'Presensi Terverifikasi',
                        text: result.message,
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#0284c7',
                        background: '#0f172a',
                        color: '#f8fafc',
                        customClass: {
                            popup: 'border border-amber-500/30 rounded-2xl shadow-2xl'
                        }
                    }).then(() => {
                        goToWelcomeScreen();
                    });
                } else if (result.no_active_session) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sesi Belum Dibuka',
                        text: result.message,
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#0284c7',
                        background: '#0f172a',
                        color: '#f8fafc',
                        customClass: {
                            popup: 'border border-amber-500/30 rounded-2xl shadow-2xl'
                        }
                    }).then(() => {
                        goToWelcomeScreen();
                    });
                } else {
                    let errMsg = result.message || 'Terjadi kesalahan saat menyimpan presensi.';
                    if (result.errors) {
                        errMsg = Object.values(result.errors).flat().join('\n');
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menyimpan',
                        text: errMsg,
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (err) {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Jaringan',
                    text: 'Gagal terhubung ke server. Silakan coba lagi.',
                    confirmButtonColor: '#ef4444'
                });
            }
        }

        function updateLiveClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            const clockElem = document.getElementById('liveClock');
            if (clockElem) {
                clockElem.textContent = `${hours}:${minutes}:${seconds} WIB`;
            }

            const options = { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric' };
            const dateElem = document.getElementById('liveDate');
            if (dateElem) {
                dateElem.textContent = now.toLocaleDateString('id-ID', options);
            }
        }
        setInterval(updateLiveClock, 1000);
        updateLiveClock();
    </script>
</body>

</html>