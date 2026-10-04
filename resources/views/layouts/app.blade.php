<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Sistem Presensi PKKMB' }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (Vite + CDN fallback for instantaneous preview) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
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
    </style>
</head>
<body class="h-full bg-slate-50 text-slate-800 flex flex-col antialiased">
    <!-- Navbar Top Banner -->
    <header class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-blue-900 text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Brand & Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/30 backdrop-blur border border-indigo-400/40 flex items-center justify-center font-black text-xl text-yellow-400 shadow-inner">
                        P
                    </div>
                    <div>
                        <a href="{{ route('dashboard') }}" class="font-extrabold text-lg sm:text-xl tracking-tight text-white flex items-center gap-2">
                            PRESENSI <span class="bg-gradient-to-r from-yellow-300 to-amber-400 bg-clip-text text-transparent">PKKMB</span>
                        </a>
                        <p class="text-[11px] text-indigo-200 hidden sm:block">Sistem Presensi & Rekapitulasi Kehadiran Mahasiswa Baru</p>
                    </div>
                </div>

                <!-- Right Side User Info & Logout -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Live Digital Clock Badge -->
                    <div class="hidden sm:flex items-center gap-2 bg-indigo-950/50 backdrop-blur-md px-3.5 py-1.5 rounded-xl border border-white/15 text-right shadow-inner">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <div>
                            <div id="navLiveClock" class="font-mono font-bold text-xs text-yellow-300 tracking-wider leading-none">00:00:00 WIB</div>
                            <div id="navLiveDate" class="text-[10px] text-indigo-200 font-medium leading-tight mt-0.5">--</div>
                        </div>
                    </div>

                    @auth
                    <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md px-3 py-1.5 rounded-full border border-white/15">
                        <div class="w-8 h-8 rounded-full bg-yellow-400 text-indigo-950 font-bold flex items-center justify-center text-xs shadow">
                            {{ auth()->user()->initials() }}
                        </div>
                        <div class="hidden md:block text-left">
                            <div class="text-xs font-semibold leading-none text-white">{{ auth()->user()->name }}</div>
                            <div class="mt-1">
                                @if(auth()->user()->isAdminSekretariat())
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-purple-500/80 text-white tracking-wide">
                                        ADMIN SEKRETARIAT
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-500/80 text-white tracking-wide">
                                        PENDAMPING GUGUS
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 text-indigo-200 hover:text-white hover:bg-white/10 rounded-lg transition" title="Keluar / Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Navigation Tabs Bar -->
        <nav class="bg-indigo-950/60 border-t border-white/10 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex space-x-1 sm:space-x-4 overflow-x-auto py-2 scrollbar-none">
                    <a href="{{ route('dashboard') }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition flex items-center gap-2 {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>

                    <a href="{{ route('attendance.index') }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition flex items-center gap-2 {{ request()->routeIs('attendance.*') ? 'bg-indigo-600 text-white shadow' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        Presensi Massal
                    </a>

                    <a href="{{ route('recap.index') }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition flex items-center gap-2 {{ request()->routeIs('recap.*') ? 'bg-indigo-600 text-white shadow' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Rekap
                    </a>

                    @if(auth()->user() && auth()->user()->isAdminSekretariat())
                    <a href="{{ route('event-days.index') }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition flex items-center gap-2 {{ request()->routeIs('event-days.*') ? 'bg-indigo-600 text-white shadow' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Kelola Sesi Acara
                    </a>
                    <a href="{{ route('groups.index') }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition flex items-center gap-2 {{ request()->routeIs('groups.*') ? 'bg-indigo-600 text-white shadow' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Kelola Gugus
                    </a>
                    <a href="{{ route('favorite-candidates.index') }}" 
                       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition flex items-center gap-2 {{ request()->routeIs('favorite-candidates.*') ? 'bg-indigo-600 text-white shadow' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        Kelola Voting Panitia
                    </a>
                    <!-- Dropdown Sub-Menu Seksi / Divisi Panitia -->
                    <div class="relative group">
                        <button type="button" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition flex items-center gap-2 {{ request()->routeIs('committee-sections.*') ? 'bg-indigo-600 text-white shadow' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>Seksi / Divisi Panitia</span>
                            <svg class="w-3.5 h-3.5 text-indigo-300 group-hover:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <!-- Dropdown Panel Sub-Menu -->
                        <div class="absolute left-0 mt-1 w-64 bg-slate-900 border border-slate-700/80 rounded-2xl shadow-2xl py-2 hidden group-hover:block group-focus-within:block z-50 backdrop-blur-md">
                            <!-- Header & Quick Action -->
                            <div class="px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wider text-purple-400 border-b border-slate-800 flex items-center justify-between">
                                <span>DIVISI / SEKSI PANITIA</span>
                                <button type="button" onclick="openGlobalAddSectionModal()" class="text-white hover:text-purple-200 bg-purple-600 hover:bg-purple-500 px-2 py-0.5 rounded-md text-[10px] font-extrabold transition cursor-pointer shadow">
                                    + Seksi Baru
                                </button>
                            </div>

                            <!-- Link Kelola Utama -->
                            <a href="{{ route('committee-sections.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-bold text-slate-100 hover:bg-purple-600/30 hover:text-white transition border-b border-slate-800">
                                📋 <span>Kelola &amp; Rekap Presensi</span>
                            </a>

                            <!-- Sub-Menu List Seksi Panitia -->
                            <div class="max-h-60 overflow-y-auto divide-y divide-slate-800/40 py-1 scrollbar-thin">
                                @if(isset($globalCommitteeSections) && count($globalCommitteeSections) > 0)
                                    @foreach($globalCommitteeSections as $sec)
                                        <a href="{{ route('committee-sections.index', ['section_id' => $sec->id]) }}" class="flex items-center justify-between px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 hover:text-purple-300 transition">
                                            <span class="truncate font-medium">👔 {{ $sec->name }}</span>
                                            <span class="px-1.5 py-0.5 rounded-full bg-purple-500/20 text-purple-300 text-[10px] font-bold">
                                                {{ $sec->attendances_count }}
                                            </span>
                                        </a>
                                    @endforeach
                                @else
                                    <div class="px-3 py-2 text-xs text-slate-500 italic">Belum ada seksi panitia</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </nav>
    </header>

    <!-- Alert Success / Error Toast Banners -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-sm animate-fade-in">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-sm shrink-0">
                        ✓
                    </div>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold text-lg">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center justify-between shadow-sm animate-fade-in">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-rose-500 text-white flex items-center justify-center font-bold text-sm shrink-0">
                        !
                    </div>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 font-bold text-lg">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 shadow-sm">
                <div class="font-bold text-sm mb-1">Terdapat kesalahan pengisian form:</div>
                <ul class="list-disc list-inside text-xs space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 mt-8">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} Sistem Presensi PKKMB &bull; Tim Panitia Sekretariat &amp; IT Support
        </div>
    </footer>
    <!-- Global Quick Modal: Tambah Seksi Panitia Baru -->
    <div id="globalAddSectionModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-purple-500/40 rounded-3xl p-6 max-w-md w-full shadow-2xl relative text-white">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800 mb-4">
                <h3 class="font-extrabold text-base text-white flex items-center gap-2">
                    👔 Tambah Seksi / Divisi Panitia Baru
                </h3>
                <button type="button" onclick="closeGlobalAddSectionModal()" class="text-slate-400 hover:text-white font-bold text-xl">&times;</button>
            </div>

            <form method="POST" action="{{ route('committee-sections.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Nama Seksi / Divisi Panitia <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           placeholder="Contoh: Acara, Perlengkapan, Humas" 
                           required 
                           class="w-full px-4 py-3 bg-slate-950 border border-slate-800 rounded-xl text-xs font-medium text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition">
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-800">
                    <button type="button" onclick="closeGlobalAddSectionModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-purple-600 hover:bg-purple-500 text-white font-extrabold text-xs rounded-xl shadow-lg transition">
                        ➕ Simpan Seksi Baru
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Real-time Digital Clock Script & Global Modal Handlers -->
    <script>
        function updateNavClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            const clock = document.getElementById('navLiveClock');
            if (clock) clock.textContent = `${hours}:${minutes}:${seconds} WIB`;

            const options = { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' };
            const date = document.getElementById('navLiveDate');
            if (date) date.textContent = now.toLocaleDateString('id-ID', options);
        }
        setInterval(updateNavClock, 1000);
        updateNavClock();

        function openGlobalAddSectionModal() {
            const modal = document.getElementById('globalAddSectionModal');
            if (modal) modal.classList.remove('hidden');
        }

        function closeGlobalAddSectionModal() {
            const modal = document.getElementById('globalAddSectionModal');
            if (modal) modal.classList.add('hidden');
        }
    </script>
</body>
</html>
