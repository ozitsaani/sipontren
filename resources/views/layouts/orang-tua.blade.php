<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Orang Tua SIPontren')</title>

    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>

<body class="text-[#191C1E] antialiased min-h-screen bg-white overflow-x-hidden">
    <div class="min-h-screen flex">
        <div
            id="sidebarOverlay"
            onclick="closeSidebar()"
            class="fixed inset-0 bg-black/40 z-30 md:hidden hidden"
        ></div>

        <aside
            id="sidebarMenu"
            class="fixed left-0 top-0 h-full z-40 overflow-y-auto px-4 py-6 bg-white border-r border-slate-100 shadow-sm w-64 flex flex-col transition-transform duration-200 ease-in-out -translate-x-full md:translate-x-0"
        >
            <div class="flex items-center justify-between gap-4 mb-8 px-2">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-[#004199] flex items-center justify-center text-white shrink-0">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">
                            school
                        </span>
                    </div>

                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">
                            SIPontren
                        </h2>
                        <p class="text-xs text-slate-500 leading-tight">
                            Portal Orang Tua
                        </p>
                    </div>
                </div>

                <button
                    type="button"
                    onclick="closeSidebar()"
                    class="md:hidden w-9 h-9 rounded-full hover:bg-slate-100 flex items-center justify-center"
                >
                    <span class="material-symbols-outlined text-slate-500">
                        close
                    </span>
                </button>
            </div>

            <nav class="flex-1 space-y-1 text-sm">
                <a href="{{ route('orang-tua.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors
                   {{ request()->routeIs('orang-tua.dashboard') ? 'text-blue-700 bg-blue-50 font-semibold' : 'text-slate-600 hover:bg-slate-50 font-semibold' }}">
                    <span class="material-symbols-outlined" style="{{ request()->routeIs('orang-tua.dashboard') ? "font-variation-settings: 'FILL' 1;" : '' }}">
                        dashboard
                    </span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('orang-tua.rekap-absensi.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors
                   {{ request()->routeIs('orang-tua.rekap-absensi.*') ? 'text-blue-700 bg-blue-50 font-semibold' : 'text-slate-600 hover:bg-slate-50 font-semibold' }}">
                    <span class="material-symbols-outlined">
                        event_available
                    </span>
                    <span>Rekap Absensi</span>
                </a>

                <a href="{{ route('orang-tua.pembayaran-spp.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors
                   {{ request()->routeIs('orang-tua.pembayaran-spp.*') ? 'text-blue-700 bg-blue-50 font-semibold' : 'text-slate-600 hover:bg-slate-50 font-semibold' }}">
                    <span class="material-symbols-outlined">
                        payments
                    </span>
                    <span>Pembayaran SPP</span>
                </a>

                <a href="{{ route('orang-tua.riwayat-pembayaran.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors
                   {{ request()->routeIs('orang-tua.riwayat-pembayaran.*') ? 'text-blue-700 bg-blue-50 font-semibold' : 'text-slate-600 hover:bg-slate-50 font-semibold' }}">
                    <span class="material-symbols-outlined">
                        history
                    </span>
                    <span>Riwayat Pembayaran</span>
                </a>

                <a href="{{ route('orang-tua.pelanggaran-anak.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors
                   {{ request()->routeIs('orang-tua.pelanggaran-anak.*') ? 'text-blue-700 bg-blue-50 font-semibold' : 'text-slate-600 hover:bg-slate-50 font-semibold' }}">
                    <span class="material-symbols-outlined">
                        gavel
                    </span>
                    <span>Pelanggaran Anak</span>
                </a>

                <a href="{{ route('orang-tua.notifikasi.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors
                   {{ request()->routeIs('orang-tua.notifikasi.*') ? 'text-blue-700 bg-blue-50 font-semibold' : 'text-slate-600 hover:bg-slate-50 font-semibold' }}">
                    <span class="material-symbols-outlined">
                        notifications
                    </span>
                    <span>Notifikasi</span>
                </a>
            </nav>

            <div class="mt-auto pt-6 border-t border-slate-100">
                <div class="flex items-center gap-3 px-2 mb-4">
                    <div class="w-10 h-10 rounded-full bg-[#001847] text-white flex items-center justify-center font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-900 truncate">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-xs text-slate-500 truncate">
                            Orang Tua / Wali
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button class="w-full px-4 py-2.5 rounded-xl bg-red-600 text-white text-sm font-semibold hover:bg-red-700">
                        Keluar
                    </button>
                </form>

                <p class="text-xs text-slate-400 mt-5 px-2">
                    v1.0.0
                </p>
            </div>
        </aside>

        <div class="flex-1 min-h-screen flex flex-col w-full md:ml-64">
            <header class="flex justify-between items-center w-full px-4 sm:px-6 py-3 sticky top-0 bg-[#001847] border-b border-[#001847] shadow-[0_8px_24px_rgba(0,24,71,0.16)] z-20">
                <div class="flex items-center gap-3 sm:gap-4">
                    <button
                        type="button"
                        onclick="openSidebar()"
                        class="md:hidden w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white"
                    >
                        <span class="material-symbols-outlined">
                            menu
                        </span>
                    </button>

                    <button
                        type="button"
                        onclick="openSidebar()"
                        class="hidden md:flex w-10 h-10 rounded-full hover:bg-white/10 items-center justify-center text-white"
                    >
                        <span class="material-symbols-outlined">
                            menu
                        </span>
                    </button>

                    <h1 class="text-lg sm:text-xl font-semibold text-white">
                        @yield('page-title', 'Dashboard Utama')
                    </h1>
                </div>

                <div class="flex items-center gap-3 sm:gap-4">
                    <a href="{{ route('orang-tua.notifikasi.index') }}"
                       class="relative text-white/80 hover:text-white hover:bg-white/10 rounded-full p-2 transition-colors">
                        <span class="material-symbols-outlined">
                            notifications
                        </span>
                        <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-600 rounded-full border-2 border-[#001847]"></span>
                    </a>

                    <div class="hidden sm:flex items-center gap-3 hover:bg-white/15 rounded-full p-1 pr-4 transition-colors border border-white/20 bg-white/10 text-white">
                        <div class="w-8 h-8 rounded-full bg-white text-[#001847] flex items-center justify-center text-sm font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>

                        <span class="text-sm font-semibold text-white">
                            {{ auth()->user()->name }}
                        </span>
                    </div>

                    <div class="sm:hidden w-10 h-10 rounded-full bg-white text-[#001847] flex items-center justify-center text-sm font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 sip-pattern-bg">
                <div class="mx-auto w-full max-w-[350px] sm:max-w-none lg:max-w-[1280px] space-y-5 sm:space-y-6">
                    @if (session('success'))
                        <div class="rounded-2xl bg-green-50 border border-green-200 text-green-700 px-4 py-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="rounded-2xl bg-red-50 border border-red-200 text-red-700 px-4 py-3">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        function openSidebar() {
            const sidebar = document.getElementById('sidebarMenu');
            const overlay = document.getElementById('sidebarOverlay');

            if (sidebar) {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
            }

            if (overlay) {
                overlay.classList.remove('hidden');
            }
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebarMenu');
            const overlay = document.getElementById('sidebarOverlay');

            if (sidebar) {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
            }

            if (overlay) {
                overlay.classList.add('hidden');
            }
        }
    </script>
@stack('scripts')
</body>
</html>