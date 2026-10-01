<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>

<div class="bg-slate-900/90 border border-slate-800/90 rounded-3xl p-6 sm:p-8 shadow-2xl backdrop-blur-xl relative">
    
    <!-- Top Header & Logo -->
    <div class="text-center mb-8">
        <div class="inline-flex p-3 rounded-2xl bg-white/10 border border-white/15 backdrop-blur mb-4 shadow-lg">
            <img src="{{ asset('image.png') }}" alt="Logo Metamedia" class="w-14 h-14 object-contain filter drop-shadow">
        </div>
        <h2 class="text-2xl font-black text-white tracking-tight">Login Panitia PKKMB</h2>
        <p class="text-xs text-slate-400 mt-1">Masukkan Email dan Password akun Panitia / Sekretariat</p>
    </div>

    <!-- Error Flash or Session Status -->
    @if (session('status'))
        <div class="mb-4 p-3 rounded-xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-xs font-semibold text-center">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="login" class="space-y-5">
        <!-- Email Input -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">
                Email Panitia
            </label>
            <div class="relative">
                <input wire:model="email" 
                       type="email" 
                       id="email" 
                       required 
                       autofocus 
                       placeholder="admin@ppkmb.ac.id" 
                       class="w-full px-4 py-3.5 bg-slate-950/90 border border-slate-800 rounded-xl text-slate-100 text-sm font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition placeholder-slate-600">
            </div>
            @error('email')
                <span class="text-xs text-rose-400 mt-1 font-semibold block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password Input -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                    Password
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-sky-400 hover:underline">
                        Lupa password?
                    </a>
                @endif
            </div>
            <input wire:model="password" 
                   type="password" 
                   id="password" 
                   required 
                   placeholder="••••••••" 
                   class="w-full px-4 py-3.5 bg-slate-950/90 border border-slate-800 rounded-xl text-slate-100 text-sm font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition placeholder-slate-600">
            @error('password')
                <span class="text-xs text-rose-400 mt-1 font-semibold block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Remember Me Checkbox -->
        <div class="flex items-center justify-between py-1">
            <label class="flex items-center gap-2 cursor-pointer">
                <input wire:model="remember" type="checkbox" class="w-4 h-4 text-indigo-600 rounded border-slate-800 bg-slate-950 focus:ring-indigo-500">
                <span class="text-xs text-slate-300 font-medium">Ingat Saya</span>
            </label>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="w-full py-4 bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-extrabold text-sm rounded-2xl shadow-xl shadow-indigo-600/30 transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer">
                <svg class="w-4 h-4 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                Masuk ke System
            </button>
        </div>
    </form>

    <!-- Bottom Link -->
    <div class="mt-6 pt-6 border-t border-slate-800 text-center text-xs text-slate-400">
        Mahasiswa Baru yang ingin melakukan presensi? 
        <a href="{{ route('present') }}" class="text-sky-400 font-bold hover:underline">
            Klik di sini untuk Portal Presensi
        </a>
    </div>
</div>
