<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth-sipontren')] class extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function login(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
        ], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        Session::regenerate();

        $user = Auth::user();

        if ($user->role === 'admin') {
            $this->redirectIntended(
                default: route('admin.dashboard', absolute: false),
                navigate: true
            );

            return;
        }

        if ($user->role === 'orang_tua') {
            $this->redirectIntended(
                default: route('orang-tua.dashboard', absolute: false),
                navigate: true
            );

            return;
        }

        $this->redirectIntended(
            default: route('dashboard', absolute: false),
            navigate: true
        );
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
};

?>

<div class="min-h-dvh w-full relative overflow-hidden bg-[#001847] text-white">
    {{-- Background Pattern --}}
    <div
        class="absolute inset-0 opacity-[0.16] pointer-events-none"
        style="background-image: radial-gradient(#DFF5A6 1.2px, transparent 1.2px); background-size: 30px 30px;"
    ></div>

    {{-- Glow --}}
    <div class="absolute -top-32 -right-32 w-[360px] h-[360px] rounded-full bg-[#004199]/60 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-36 -left-36 w-[420px] h-[420px] rounded-full bg-[#DFF5A6]/20 blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 w-[360px] h-[360px] rounded-full bg-[#D9E2FF]/10 blur-3xl pointer-events-none"></div>

    <div class="relative z-10 min-h-dvh flex items-center justify-center px-4 py-5 sm:px-6 lg:px-10">
        <div class="w-full max-w-7xl grid grid-cols-1 lg:grid-cols-[1fr_500px] gap-8 lg:gap-14 items-center">
            {{-- Brand / Hero --}}
            <section class="hidden lg:block">
                <div class="max-w-2xl">
                    <div class="flex items-center gap-4">
                        @if (file_exists(public_path('images/logo.png')))
                            <div class="w-16 h-16 rounded-3xl bg-white flex items-center justify-center shadow-[0_18px_42px_rgba(0,0,0,0.22)] overflow-hidden">
                                <img
                                    src="{{ asset('images/logo.png') }}"
                                    alt="Logo SIPontren"
                                    class="w-12 h-12 object-contain"
                                >
                            </div>
                        @else
                            <div class="w-16 h-16 rounded-3xl bg-[#004199] flex items-center justify-center shadow-[0_18px_42px_rgba(0,0,0,0.22)]">
                                <span class="material-symbols-outlined text-white text-[36px]" style="font-variation-settings: 'FILL' 1;">
                                    mosque
                                </span>
                            </div>
                        @endif

                        <div>
                            <h1 class="text-4xl font-bold leading-tight">
                                SIPontren
                            </h1>

                            <p class="text-white/70 text-lg mt-1">
                                Sistem Informasi Pesantren Online
                            </p>
                        </div>
                    </div>

                    <div class="mt-16">
                        <p class="text-sm uppercase tracking-[0.32em] text-[#DFF5A6] font-semibold">
                            Portal Pesantren
                        </p>

                        <h2 class="text-5xl xl:text-6xl font-bold leading-tight mt-5">
                            Kelola santri lebih mudah, rapi, dan terpantau.
                        </h2>

                        <p class="text-white/72 text-xl leading-relaxed mt-6 max-w-xl">
                            Dashboard terpadu untuk absensi, pembayaran SPP, notifikasi, dan kedisiplinan santri dalam satu sistem.
                        </p>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mt-14 max-w-2xl">
                        <div class="rounded-3xl bg-white/10 border border-white/10 p-5 backdrop-blur">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center mb-4">
                                <span class="material-symbols-outlined text-[#DFF5A6] text-[28px]">
                                    fact_check
                                </span>
                            </div>

                            <p class="font-bold">
                                Absensi
                            </p>

                            <p class="text-sm text-white/60 mt-1 leading-relaxed">
                                Rekap kehadiran santri.
                            </p>
                        </div>

                        <div class="rounded-3xl bg-white/10 border border-white/10 p-5 backdrop-blur">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center mb-4">
                                <span class="material-symbols-outlined text-[#DFF5A6] text-[28px]">
                                    payments
                                </span>
                            </div>

                            <p class="font-bold">
                                SPP
                            </p>

                            <p class="text-sm text-white/60 mt-1 leading-relaxed">
                                Tagihan dan pembayaran.
                            </p>
                        </div>

                        <div class="rounded-3xl bg-white/10 border border-white/10 p-5 backdrop-blur">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center mb-4">
                                <span class="material-symbols-outlined text-[#DFF5A6] text-[28px]">
                                    shield
                                </span>
                            </div>

                            <p class="font-bold">
                                Pembinaan
                            </p>

                            <p class="text-sm text-white/60 mt-1 leading-relaxed">
                                Catatan kedisiplinan.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Login Card --}}
            <section class="w-full flex justify-center lg:justify-end">
                <main class="w-full max-w-[430px] sm:max-w-[460px] lg:max-w-[500px] bg-white text-[#191C1E] rounded-[28px] sm:rounded-[34px] border border-white/70 shadow-[0_26px_70px_rgba(0,0,0,0.28)] overflow-hidden">
                    <div class="h-2 w-full bg-[#DFF5A6]"></div>

                    <div class="px-5 py-5 sm:px-8 sm:py-8 lg:px-10 lg:py-9">
                        {{-- Mobile Brand --}}
                        <div class="lg:hidden text-center mb-5">
                            <div class="mx-auto w-14 h-14 rounded-2xl bg-[#001847] text-white flex items-center justify-center shadow-lg">
                                <span class="material-symbols-outlined text-[32px]" style="font-variation-settings: 'FILL' 1;">
                                    mosque
                                </span>
                            </div>

                            <h1 class="text-2xl font-bold text-[#001847] mt-3">
                                SIPontren
                            </h1>

                            <p class="text-sm font-semibold text-[#004199] mt-1">
                                Sistem Informasi Pesantren Online
                            </p>
                        </div>

                        {{-- Desktop Form Header --}}
                        <div class="hidden lg:block mb-7">
                            <p class="text-sm uppercase tracking-[0.22em] text-[#004199] font-bold">
                                Masuk Akun
                            </p>

                            <h2 class="text-3xl font-bold text-[#001847] mt-2">
                                Selamat Datang
                            </h2>

                            <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                                Masuk untuk mengakses dashboard SIPontren sesuai peran akun Anda.
                            </p>
                        </div>

                        {{-- Session Status --}}
                        @if (session('status'))
                            <div class="mb-4 rounded-2xl bg-[#DFF5A6] border border-[#CDEB7C] px-4 py-3 text-sm font-semibold text-[#4A5F00]">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{-- Global Errors --}}
                        @if ($errors->any())
                            <div class="mb-4 rounded-2xl bg-red-50 border border-red-200 px-4 py-3">
                                <ul class="space-y-1 text-sm text-red-600">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form wire:submit="login" class="flex flex-col gap-4 sm:gap-5">
                            {{-- Email --}}
                            <div class="flex flex-col gap-1.5">
                                <label class="text-sm font-semibold text-[#001847]" for="email">
                                    Email
                                </label>

                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-[#737785] text-[22px]">
                                        person
                                    </span>

                                    <input
                                        wire:model="email"
                                        id="email"
                                        name="email"
                                        type="email"
                                        autocomplete="email"
                                        placeholder="Masukkan email Anda"
                                        class="w-full bg-[#F7F9FC] text-[#191C1E] text-sm sm:text-base border border-[#C2C6D6] rounded-2xl pl-11 pr-4 py-3 sm:py-3.5 focus:border-[#004199] focus:ring-1 focus:ring-[#004199] outline-none transition-all placeholder:text-[#737785]"
                                        required
                                        autofocus
                                    >
                                </div>

                                @error('email')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="flex flex-col gap-1.5">
                                <label class="text-sm font-semibold text-[#001847]" for="password">
                                    Password
                                </label>

                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-[#737785] text-[22px]">
                                        lock
                                    </span>

                                    <input
                                        wire:model="password"
                                        id="password"
                                        name="password"
                                        type="password"
                                        autocomplete="current-password"
                                        placeholder="Masukkan password Anda"
                                        class="w-full bg-[#F7F9FC] text-[#191C1E] text-sm sm:text-base border border-[#C2C6D6] rounded-2xl pl-11 pr-11 py-3 sm:py-3.5 focus:border-[#004199] focus:ring-1 focus:ring-[#004199] outline-none transition-all placeholder:text-[#737785]"
                                        required
                                    >

                                    <button
                                        type="button"
                                        onclick="toggleLoginPassword()"
                                        class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#737785] hover:text-[#004199] transition-colors flex items-center justify-center"
                                        aria-label="Tampilkan atau sembunyikan password"
                                    >
                                        <span id="passwordIcon" class="material-symbols-outlined text-[21px]">
                                            visibility_off
                                        </span>
                                    </button>
                                </div>

                                @error('password')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Remember / Forgot --}}
                            <div class="flex items-center justify-between gap-3">
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input
                                        wire:model="remember"
                                        type="checkbox"
                                        class="rounded border-[#C2C6D6] text-[#004199] focus:ring-[#004199] h-4 w-4 transition-colors cursor-pointer"
                                    >

                                    <span class="text-sm text-[#424653] group-hover:text-[#191C1E] transition-colors">
                                        Ingat saya
                                    </span>
                                </label>

                                @if (Route::has('password.request'))
                                    <a
                                        href="{{ route('password.request') }}"
                                        wire:navigate
                                        class="text-sm font-semibold text-[#004199] hover:text-[#00429B] hover:underline transition-all whitespace-nowrap"
                                    >
                                        Lupa Password?
                                    </a>
                                @endif
                            </div>

                            {{-- Submit --}}
                            <button
                                type="submit"
                                class="w-full bg-[#004199] text-white text-sm sm:text-base font-semibold py-3 sm:py-3.5 px-4 rounded-2xl hover:bg-[#00377F] transition-colors flex items-center justify-center gap-2 shadow-[0_8px_20px_rgba(0,65,153,0.22)] disabled:opacity-70"
                                wire:loading.attr="disabled"
                            >
                                <span wire:loading.remove wire:target="login" class="flex items-center gap-2">
                                    Masuk ke Dashboard
                                    <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                                </span>

                                <span wire:loading wire:target="login">
                                    Memproses...
                                </span>
                            </button>
                        </form>

                        {{-- Footer --}}
                        <div class="mt-5 sm:mt-7 pt-4 sm:pt-5 border-t border-[#E0E3E6] text-center">
                            <p class="text-xs sm:text-sm text-[#424653] flex items-center justify-center gap-2 flex-wrap">
                                <span class="material-symbols-outlined text-[17px]">help</span>
                                Kendala akses?
                                <span class="text-[#004199] font-semibold">
                                    Hubungi Administrator
                                </span>
                            </p>
                        </div>
                    </div>
                </main>
            </section>
        </div>
    </div>

    <script>
        function toggleLoginPassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('passwordIcon');

            if (!input || !icon) {
                return;
            }

            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility_off';
            }
        }
    </script>
</div>