@extends('layouts.admin')

@section('title', 'Dashboard Admin - SIPontren')
@section('page-title', 'Dashboard Utama')
@section('page-subtitle', 'Ringkasan aktivitas pesantren hari ini.')

@section('content')
    @php
        $totalSantri = \App\Models\Santri::count();

        $santriAktif = \App\Models\Santri::where('status', 'aktif')->count();

        $hadirHariIni = \App\Models\Absensi::whereDate('tanggal', now())
            ->where('status', 'hadir')
            ->count();

        $alfaHariIni = \App\Models\Absensi::whereDate('tanggal', now())
            ->where('status', 'alfa')
            ->count();

        $pembayaranMenunggu = \App\Models\PembayaranSpp::where('status_verifikasi', 'menunggu')->count();

        $santriMenunggak = \App\Models\TagihanSpp::where('status', 'menunggak')
            ->distinct('santri_id')
            ->count('santri_id');

        $pelanggaranBulanIni = \App\Models\Pelanggaran::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        $notifikasiBaru = \App\Models\Notifikasi::where('dibaca', false)->count();

        $sakitIzinHariIni = \App\Models\Absensi::whereDate('tanggal', now())
            ->whereIn('status', ['sakit', 'izin'])
            ->count();

        $totalAbsensiHariIni = $hadirHariIni + $sakitIzinHariIni + $alfaHariIni;

        $persenHadir = $totalAbsensiHariIni > 0
            ? round(($hadirHariIni / $totalAbsensiHariIni) * 100)
            : 0;

        $aktivitasTerbaru = \App\Models\Notifikasi::with(['user', 'santri'])
            ->latest()
            ->take(5)
            ->get();
    @endphp

    <div class="space-y-5 sm:space-y-6">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-[#191C1E] leading-tight">
                Dashboard Admin
            </h2>

            <p class="text-sm sm:text-base text-slate-500 mt-1 leading-relaxed">
                Ringkasan aktivitas pesantren hari ini.
            </p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            <div class="bg-white rounded-[28px] p-4 sm:p-6 border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-start justify-between gap-2 mb-4">
                        <h3 class="text-xs sm:text-sm font-semibold text-slate-700 leading-tight">
                            Total Santri
                        </h3>

                        <span class="w-9 h-9 rounded-full bg-[#D9E2FF] flex items-center justify-center text-[#001945] shrink-0">
                            <span class="material-symbols-outlined text-[20px]">
                                group
                            </span>
                        </span>
                    </div>

                    <p class="text-3xl sm:text-4xl font-bold text-[#191C1E]">
                        {{ number_format($totalSantri) }}
                    </p>

                    <div class="flex items-center gap-1 mt-3 text-[#394D00]">
                        <span class="material-symbols-outlined text-[15px]">
                            trending_up
                        </span>
                        <span class="text-[11px] sm:text-xs font-semibold leading-tight">
                            Data terdaftar
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[28px] p-4 sm:p-6 border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-start justify-between gap-2 mb-4">
                        <h3 class="text-xs sm:text-sm font-semibold text-slate-700 leading-tight">
                            Santri Aktif
                        </h3>

                        <span class="w-9 h-9 rounded-full bg-[#C2F435] flex items-center justify-center text-[#151F00] shrink-0">
                            <span class="material-symbols-outlined text-[20px]">
                                how_to_reg
                            </span>
                        </span>
                    </div>

                    <p class="text-3xl sm:text-4xl font-bold text-[#191C1E]">
                        {{ number_format($santriAktif) }}
                    </p>

                    <p class="text-[11px] sm:text-sm text-slate-500 mt-3 leading-tight">
                        Status aktif
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-[28px] p-4 sm:p-6 border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-start justify-between gap-2 mb-4">
                        <h3 class="text-xs sm:text-sm font-semibold text-slate-700 leading-tight">
                            Hadir Hari Ini
                        </h3>

                        <span class="w-9 h-9 rounded-full bg-[#DAE2FF] flex items-center justify-center text-[#001847] shrink-0">
                            <span class="material-symbols-outlined text-[20px]">
                                event_available
                            </span>
                        </span>
                    </div>

                    <p class="text-3xl sm:text-4xl font-bold text-[#191C1E]">
                        {{ number_format($hadirHariIni) }}
                    </p>

                    <div class="flex items-center gap-1 mt-3 text-[#394D00]">
                        <span class="material-symbols-outlined text-[15px]">
                            check_circle
                        </span>
                        <span class="text-[11px] sm:text-xs font-semibold">
                            Hadir
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-red-50 rounded-[28px] p-4 sm:p-6 border border-red-200 shadow-[0_10px_28px_rgba(239,68,68,0.10)] relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-start justify-between gap-2 mb-4">
                        <h3 class="text-xs sm:text-sm font-semibold text-red-700 leading-tight">
                            Alfa Hari Ini
                        </h3>

                        <span class="w-9 h-9 rounded-full bg-red-100 flex items-center justify-center text-red-700 shrink-0">
                            <span class="material-symbols-outlined text-[20px]">
                                event_busy
                            </span>
                        </span>
                    </div>

                    <p class="text-3xl sm:text-4xl font-bold text-red-600">
                        {{ number_format($alfaHariIni) }}
                    </p>

                    <div class="flex items-center gap-1 mt-3 text-red-600">
                        <span class="material-symbols-outlined text-[15px]">
                            warning
                        </span>
                        <span class="text-[11px] sm:text-xs font-semibold leading-tight">
                            Perlu tindakan
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            <a href="{{ route('admin.verifikasi-pembayaran.index') }}"
               class="bg-white rounded-[28px] p-4 sm:p-6 border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 hover:shadow-[0_14px_36px_rgba(15,23,42,0.10)] transition">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-slate-100 flex items-center justify-center text-[#004199] shrink-0">
                    <span class="material-symbols-outlined text-[22px] sm:text-[24px]">
                        pending_actions
                    </span>
                </div>

                <div class="min-w-0">
                    <h3 class="text-[10px] sm:text-xs text-slate-600 uppercase tracking-wider mb-1 font-semibold leading-tight">
                        Menunggu Verifikasi
                    </h3>

                    <p class="text-2xl font-bold text-[#191C1E]">
                        {{ number_format($pembayaranMenunggu) }}
                    </p>
                </div>
            </a>

            <a href="{{ route('admin.tagihan-spp.index') }}"
               class="bg-white rounded-[28px] p-4 sm:p-6 border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 hover:shadow-[0_14px_36px_rgba(15,23,42,0.10)] transition">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-red-50 flex items-center justify-center text-red-600 shrink-0">
                    <span class="material-symbols-outlined text-[22px] sm:text-[24px]">
                        money_off
                    </span>
                </div>

                <div class="min-w-0">
                    <h3 class="text-[10px] sm:text-xs text-slate-600 uppercase tracking-wider mb-1 font-semibold leading-tight">
                        Santri Menunggak
                    </h3>

                    <p class="text-2xl font-bold text-[#191C1E]">
                        {{ number_format($santriMenunggak) }}
                    </p>
                </div>
            </a>

            <a href="{{ route('admin.pelanggaran.index') }}"
               class="bg-white rounded-[28px] p-4 sm:p-6 border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 hover:shadow-[0_14px_36px_rgba(15,23,42,0.10)] transition">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-slate-100 flex items-center justify-center text-[#191C1E] shrink-0">
                    <span class="material-symbols-outlined text-[22px] sm:text-[24px]">
                        gavel
                    </span>
                </div>

                <div class="min-w-0">
                    <h3 class="text-[10px] sm:text-xs text-slate-600 uppercase tracking-wider mb-1 font-semibold leading-tight">
                        Pelanggaran Bulan Ini
                    </h3>

                    <p class="text-2xl font-bold text-[#191C1E]">
                        {{ number_format($pelanggaranBulanIni) }}
                    </p>
                </div>
            </a>

            <a href="{{ route('admin.notifikasi.index') }}"
               class="bg-blue-50 rounded-[28px] p-4 sm:p-6 border border-blue-200 shadow-[0_10px_28px_rgba(37,99,235,0.10)] flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 hover:shadow-[0_14px_36px_rgba(37,99,235,0.14)] transition">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#004199] flex items-center justify-center text-white shrink-0">
                    <span class="material-symbols-outlined text-[22px] sm:text-[24px]">
                        notifications_active
                    </span>
                </div>

                <div class="min-w-0">
                    <h3 class="text-[10px] sm:text-xs text-[#004199] uppercase tracking-wider mb-1 font-semibold leading-tight">
                        Notifikasi Baru
                    </h3>

                    <p class="text-2xl font-bold text-[#004199]">
                        {{ number_format($notifikasiBaru) }}
                    </p>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-6">
            <div class="lg:col-span-2 bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden flex flex-col">
                <div class="bg-[#001847] px-5 sm:px-6 py-4 border-b border-[#001847] flex justify-between items-center text-white">
                    <h3 class="text-base sm:text-xl font-semibold text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-white text-[20px]">
                            history
                        </span>
                        Aktivitas Terbaru
                    </h3>

                    <a href="{{ route('admin.notifikasi.index') }}"
                       class="text-xs sm:text-sm font-semibold text-white/90 hover:text-white">
                        Lihat Semua
                    </a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse ($aktivitasTerbaru as $aktivitas)
                        <div class="p-5 sm:p-6 flex gap-3 sm:gap-4 hover:bg-[#F8FAFC] transition-colors">
                            <div class="w-10 h-10 rounded-full
                                {{ $aktivitas->jenis === 'pembayaran' ? 'bg-[#D9E2FF] text-[#001945]' : '' }}
                                {{ $aktivitas->jenis === 'pelanggaran' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $aktivitas->jenis === 'absensi' ? 'bg-[#C2F435] text-[#151F00]' : '' }}
                                {{ $aktivitas->jenis === 'sistem' ? 'bg-slate-100 text-slate-700' : '' }}
                                flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[20px]">
                                    @switch($aktivitas->jenis)
                                        @case('pembayaran') payments @break
                                        @case('pelanggaran') report @break
                                        @case('absensi') event_available @break
                                        @default notifications
                                    @endswitch
                                </span>
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-sm sm:text-base text-[#191C1E] leading-snug">
                                    <span class="font-bold">
                                        {{ $aktivitas->judul }}
                                    </span>

                                    @if ($aktivitas->santri)
                                        untuk
                                        <span class="font-bold">
                                            {{ $aktivitas->santri->nama_santri }}
                                        </span>
                                    @endif
                                </p>

                                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                                    {{ $aktivitas->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-sm text-slate-500">
                            Belum ada aktivitas terbaru.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden flex flex-col">
                <div class="bg-[#001847] px-5 sm:px-6 py-4 border-b border-[#001847] text-white">
                    <h3 class="text-base sm:text-xl font-semibold text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-white text-[20px]">
                            fact_check
                        </span>
                        Kehadiran Hari Ini
                    </h3>
                </div>

                <div class="p-5 sm:p-6 flex-1 flex flex-col">
                    <div class="mb-6 flex items-center justify-center">
                        <div class="relative w-36 h-36 sm:w-44 sm:h-44 rounded-full border-8 border-slate-100 flex items-center justify-center">
                            <div class="absolute inset-0 rounded-full border-8 border-[#394D00]"></div>

                            <div class="text-center relative z-10 bg-white rounded-full w-24 h-24 sm:w-32 sm:h-32 flex flex-col items-center justify-center">
                                <span class="text-3xl sm:text-4xl font-bold text-[#191C1E]">
                                    {{ $persenHadir }}%
                                </span>
                                <span class="text-xs text-slate-500">
                                    Hadir
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3 mt-auto">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-[#394D00]"></span>
                                <span class="text-sm text-slate-600">
                                    Hadir
                                </span>
                            </div>

                            <span class="text-sm font-semibold">
                                {{ number_format($hadirHariIni) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-[#495D91]"></span>
                                <span class="text-sm text-slate-600">
                                    Sakit/Izin
                                </span>
                            </div>

                            <span class="text-sm font-semibold">
                                {{ number_format($sakitIzinHariIni) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-red-600"></span>
                                <span class="text-sm text-slate-600">
                                    Alfa
                                </span>
                            </div>

                            <span class="text-sm font-semibold">
                                {{ number_format($alfaHariIni) }}
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('admin.rekap-absensi.index') }}"
                       class="w-full mt-6 py-2.5 px-4 border border-slate-300 rounded-xl text-sm font-semibold text-[#004199] hover:bg-slate-50 transition-colors text-center">
                        Lihat Detail Kehadiran
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection