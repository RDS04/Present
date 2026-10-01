<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login Panitia - PKKMB 2026 Universitas Metamedia</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Tailwind CSS & Scripts -->
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
    <body class="min-h-screen bg-slate-950 text-slate-100 relative antialiased flex flex-col justify-between selection:bg-indigo-500 selection:text-white">
        
        <!-- Fullscreen Gedung Background with Dark Gradient Overlay -->
        <div class="fixed inset-0 z-0 bg-cover bg-center bg-no-repeat transition-all duration-1000 scale-105" 
             style="background-image: url('{{ asset('gedung.png') }}');">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/90 to-slate-900/75 backdrop-blur-[4px]"></div>
        </div>

        <!-- Glowing Decorative Blobs -->
        <div class="fixed top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-indigo-600/20 rounded-full blur-[120px] pointer-events-none z-0"></div>

        <!-- Header -->
        <header class="relative z-20 w-full border-b border-white/10 bg-slate-900/40 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="{{ route('present') }}" class="flex items-center gap-3">
                    <img src="{{ asset('image.png') }}" alt="Logo Metamedia" class="w-9 h-9 object-contain drop-shadow">
                    <div>
                        <span class="font-extrabold text-sm sm:text-base tracking-tight text-white block leading-none">UNIVERSITAS METAMEDIA</span>
                        <span class="text-[10px] font-bold text-sky-400 tracking-wider">PKKMB 2026</span>
                    </div>
                </a>

                <a href="{{ route('present') }}" class="px-3.5 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/15 text-xs font-bold text-slate-200 transition backdrop-blur flex items-center gap-1.5">
                    &larr; <span class="hidden sm:inline">Portal Presensi</span>
                </a>
            </div>
        </header>

        <!-- Main Form Slot -->
        <main class="relative z-10 flex-1 flex flex-col items-center justify-center p-4 sm:p-6 my-8">
            <div class="w-full max-w-md">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer -->
        <footer class="relative z-10 w-full border-t border-white/10 py-4 text-center text-xs text-slate-400 backdrop-blur-md bg-slate-950/40">
            &copy; 2026 Universitas Metamedia &bull; Panitia Sekretariat &amp; IT Support
        </footer>

        @fluxScripts
    </body>
</html>
