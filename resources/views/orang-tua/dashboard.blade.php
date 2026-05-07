@extends('layouts.orang-tua')

@section('title', 'Dashboard Orang Tua - SIPontren')
@section('page-title', 'Dashboard Utama')

@section('content')
    @php
        $santri = auth()->user()->santris()->with('kelasMadrasah')->first();

        $absensiTerakhir = $santri
            ? \App\Models\Absensi::where('santri_id', $santri->id)->latest('tanggal')->first()
            : null;

        $hadirBulanIni = $santri
            ? \App\Models\Absensi::where('santri_id', $santri->id)
                ->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->where('status', 'hadir')
                ->count()
            : 0;

        $izinBulanIni = $santri
            ? \App\Models\Absensi::where('santri_id', $santri->id)
                ->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->where('status', 'izin')
                ->count()
            : 0;

        $sakitBulanIni = $santri
            ? \App\Models\Absensi::where('santri_id', $santri->id)
                ->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->where('status', 'sakit')
                ->count()
            : 0;

        $alfaBulanIni = $santri
            ? \App\Models\Absensi::where('santri_id', $santri->id)
                ->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->where('status', 'alfa')
                ->count()
            : 0;

        $totalTunggakan = $santri
            ? \App\Models\TagihanSpp::where('santri_id', $santri->id)
                ->whereIn('status', ['menunggak', 'belum_dibayar'])
                ->sum('nominal')
            : 0;

        $totalNotifikasi = \App\Models\Notifikasi::where('user_id', auth()->id())
            ->where('dibaca', false)
            ->count();

        $pelanggaranBulanIni = $santri
            ? \App\Models\Pelanggaran::where('santri_id', $santri->id)
                ->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->count()
            : 0;

        $waliSantri = auth()->user()->name;
    @endphp

    @if (! $santri)
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-[28px] p-5 sm:p-6 shadow-[0_10px_28px_rgba(15,23,42,0.07)]">
            Akun Anda belum terhubung dengan data santri. Silakan hubungi admin pesantren.
        </div>
    @else
        <div class="space-y-5 sm:space-y-6">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-[#191C1E] leading-tight">
                    Dashboard Orang Tua
                </h2>

                <p class="text-sm sm:text-base text-slate-500 mt-1 leading-relaxed">
                    Pantau informasi akademik, absensi, pembayaran, dan kedisiplinan anak.
                </p>
            </div>

            <div class="bg-[#D9E2FF] border border-[#B0C6FF] rounded-[28px] p-5 shadow-[0_10px_28px_rgba(37,99,235,0.08)] flex items-start gap-3">
                <span class="material-symbols-outlined text-[#004199] mt-0.5 shrink-0" style="font-variation-settings: 'FILL' 1;">
                    info
                </span>

                <p class="text-sm text-[#00429B] leading-relaxed">
                    <strong>Pemberitahuan Privasi:</strong>
                    Data yang ditampilkan hanya memuat data akademik dan administratif anak Anda.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-6">
                <div class="lg:col-span-2 bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
                    <div class="bg-[#001847] px-5 sm:px-6 py-4 text-white">
                        <h3 class="text-base sm:text-xl font-semibold flex items-center gap-2">
                            <span class="material-symbols-outlined text-white text-[20px]">
                                person
                            </span>
                            Profil Santri
                        </h3>
                    </div>

                    <div class="p-5 sm:p-6 flex flex-col sm:flex-row gap-5 md:gap-6 items-center sm:items-start relative overflow-hidden">
                        <div class="absolute -top-10 -left-10 w-40 h-40 bg-[#DAE2FF]/50 rounded-full blur-2xl"></div>

                        <div class="w-20 h-20 md:w-28 md:h-28 rounded-2xl overflow-hidden border-4 border-white shadow-sm shrink-0 z-10 bg-[#001847] flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-4xl md:text-5xl">
                                school
                            </span>
                        </div>

                        <div class="flex-1 text-center sm:text-left z-10">
                            <div class="inline-block px-3 py-1 bg-slate-100 rounded-full text-xs font-medium text-slate-600 mb-3">
                                Santri Aktif
                            </div>

                            <h2 class="text-xl md:text-2xl font-semibold text-[#001945] mb-1 leading-tight">
                                {{ $santri->nama_santri }}
                            </h2>

                            <p class="text-sm md:text-base text-slate-500 mb-4 leading-snug">
                                {{ $santri->kelasMadrasah->nama_kelas ?? '-' }}

                                @if ($santri->asrama)
                                    (Asrama {{ $santri->asrama }})
                                @endif
                            </p>

                            <div class="grid grid-cols-2 gap-3 sm:gap-4 border-t border-slate-200 pt-4">
                                <div>
                                    <p class="text-[11px] sm:text-xs text-slate-400 uppercase tracking-wider mb-1">
                                        NIS
                                    </p>
                                    <p class="text-sm font-semibold text-slate-800 leading-snug">
                                        {{ $santri->nis }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-[11px] sm:text-xs text-slate-400 uppercase tracking-wider mb-1">
                                        Wali Santri
                                    </p>
                                    <p class="text-sm font-semibold text-slate-800 leading-snug">
                                        {{ $waliSantri }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden flex flex-col">
                    <div class="bg-[#001847] px-5 sm:px-6 py-4 text-white">
                        <h3 class="text-base sm:text-xl font-semibold flex items-center gap-2">
                            <span class="material-symbols-outlined text-white text-[20px]">
                                today
                            </span>
                            Status Hari Ini
                        </h3>
                    </div>

                    <div class="p-5 sm:p-6 flex-1 flex flex-col">
                        <div class="flex-1 flex flex-col justify-center items-center text-center py-2">
                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-[#EEF8D8] text-[#394D00] text-xl sm:text-2xl font-semibold rounded-xl mb-3">
                                <span class="w-3 h-3 rounded-full bg-[#394D00] animate-pulse"></span>
                                {{ $absensiTerakhir ? ucfirst($absensiTerakhir->status) : 'Belum Ada' }}
                            </span>

                            <p class="text-sm text-slate-500">
                                {{ $absensiTerakhir ? date('d-m-Y', strtotime($absensiTerakhir->tanggal)) : 'Belum ada data absensi' }}
                            </p>

                            <p class="text-sm text-slate-500 mt-1">
                                Pengajian Harian
                            </p>
                        </div>

                        <a href="{{ route('orang-tua.rekap-absensi.index') }}"
                           class="mt-4 w-full py-2.5 border border-slate-300 rounded-xl text-sm font-semibold text-center text-[#004199] hover:bg-slate-50 transition-colors">
                            Lihat Rekap Absensi
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 sm:gap-6">
                <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
                    <div class="bg-[#001847] px-5 sm:px-6 py-4 text-white flex items-center justify-between gap-3">
                        <h3 class="text-base sm:text-xl font-semibold flex items-center gap-2">
                            <span class="material-symbols-outlined text-white text-[20px]">
                                event_available
                            </span>
                            Kehadiran Bulan Ini
                        </h3>

                        <span class="text-xs font-semibold text-white/80 shrink-0">
                            {{ now()->translatedFormat('F Y') }}
                        </span>
                    </div>

                    <div class="p-5 sm:p-6 grid grid-cols-2 gap-3 sm:gap-4">
                        <div class="bg-[#EEF8D8]/70 p-4 rounded-2xl text-center border border-[#DCE8BE]">
                            <p class="text-3xl sm:text-4xl font-bold text-[#394D00] mb-1">
                                {{ $hadirBulanIni }}
                            </p>
                            <p class="text-xs text-slate-500 uppercase font-semibold">
                                Hadir
                            </p>
                        </div>

                        <div class="bg-[#D9E2FF]/60 p-4 rounded-2xl text-center border border-[#B0C6FF]">
                            <p class="text-3xl sm:text-4xl font-bold text-[#004199] mb-1">
                                {{ $izinBulanIni }}
                            </p>
                            <p class="text-xs text-slate-500 uppercase font-semibold">
                                Izin
                            </p>
                        </div>

                        <div class="bg-slate-100 p-4 rounded-2xl text-center border border-slate-200">
                            <p class="text-3xl sm:text-4xl font-bold text-slate-500 mb-1">
                                {{ $sakitBulanIni }}
                            </p>
                            <p class="text-xs text-slate-500 uppercase font-semibold">
                                Sakit
                            </p>
                        </div>

                        <div class="bg-red-50 p-4 rounded-2xl text-center border border-red-200">
                            <p class="text-3xl sm:text-4xl font-bold text-red-600 mb-1">
                                {{ $alfaBulanIni }}
                            </p>
                            <p class="text-xs text-slate-500 uppercase font-semibold">
                                Alpha
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
                    <div class="bg-[#001847] px-5 sm:px-6 py-4 text-white flex items-center justify-between gap-3">
                        <h3 class="text-base sm:text-xl font-semibold flex items-center gap-2">
                            <span class="material-symbols-outlined text-white text-[20px]">
                                payments
                            </span>
                            Status Pembayaran SPP
                        </h3>

                        @if ($totalTunggakan > 0)
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold shrink-0">
                                Menunggak
                            </span>
                        @else
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold shrink-0">
                                Aman
                            </span>
                        @endif
                    </div>

                    <div class="p-5 sm:p-6">
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Tagihan berjalan dan tunggakan
                        </p>

                        <div class="my-6">
                            <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">
                                Total Tunggakan
                            </p>

                            <p class="text-3xl sm:text-4xl font-bold text-[#191C1E] leading-tight">
                                Rp {{ number_format($totalTunggakan, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="flex gap-3 mt-auto">
                            <a href="{{ route('orang-tua.pembayaran-spp.index') }}"
                               class="flex-1 bg-[#004199] hover:bg-[#00377F] text-white text-sm font-semibold py-3 rounded-xl transition-colors flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-sm">
                                    payments
                                </span>
                                Bayar Sekarang
                            </a>

                            <a href="{{ route('orang-tua.riwayat-pembayaran.index') }}"
                               class="px-4 py-3 border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-xl transition-colors flex items-center justify-center">
                                <span class="material-symbols-outlined">
                                    history
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 sm:gap-6">
                <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
                    <div class="bg-[#001847] px-5 sm:px-6 py-4 text-white">
                        <h3 class="text-base sm:text-xl font-semibold flex items-center gap-2">
                            <span class="material-symbols-outlined text-white text-[20px]">
                                gavel
                            </span>
                            Pelanggaran Anak
                        </h3>
                    </div>

                    <div class="p-5 sm:p-6">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-sm text-slate-500">
                                    Pelanggaran Bulan Ini
                                </p>

                                <h3 class="text-3xl font-bold mt-2 text-orange-500">
                                    {{ $pelanggaranBulanIni }}
                                </h3>

                                <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                                    Catatan kedisiplinan bulan berjalan
                                </p>
                            </div>

                            <div class="w-12 h-12 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined">
                                    gavel
                                </span>
                            </div>
                        </div>

                        <a href="{{ route('orang-tua.pelanggaran-anak.index') }}"
                           class="block mt-5 w-full py-2.5 border border-slate-300 rounded-xl text-sm font-semibold text-center text-[#004199] hover:bg-slate-50 transition-colors">
                            Lihat Pelanggaran
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
                    <div class="bg-[#001847] px-5 sm:px-6 py-4 text-white">
                        <h3 class="text-base sm:text-xl font-semibold flex items-center gap-2">
                            <span class="material-symbols-outlined text-white text-[20px]">
                                notifications
                            </span>
                            Notifikasi
                        </h3>
                    </div>

                    <div class="p-5 sm:p-6">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-sm text-slate-500">
                                    Notifikasi Baru
                                </p>

                                <h3 class="text-3xl font-bold mt-2 text-[#004199]">
                                    {{ $totalNotifikasi }}
                                </h3>

                                <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                                    Pemberitahuan belum dibaca
                                </p>
                            </div>

                            <div class="w-12 h-12 rounded-full bg-blue-50 text-[#004199] flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined">
                                    notifications
                                </span>
                            </div>
                        </div>

                        <a href="{{ route('orang-tua.notifikasi.index') }}"
                           class="block mt-5 w-full py-2.5 border border-slate-300 rounded-xl text-sm font-semibold text-center text-[#004199] hover:bg-slate-50 transition-colors">
                            Lihat Notifikasi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection