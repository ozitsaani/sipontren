@extends('layouts.admin')

@section('title', 'Pengaturan SPP - SIPontren')
@section('page-title', 'Pengaturan SPP')

@section('content')
    <div class="space-y-5 sm:space-y-6">
        {{-- Header --}}
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-[#191C1E] leading-tight">
                Pengaturan SPP
            </h2>

            <p class="text-sm sm:text-base text-slate-500 mt-1 leading-relaxed">
                Atur nominal SPP bulanan, tanggal pembuatan tagihan, dan tanggal jatuh tempo pembayaran.
            </p>
        </div>

        {{-- Pengaturan Aktif --}}
        <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
            <div class="bg-[#001847] px-5 sm:px-6 py-4 text-white flex items-center justify-between gap-3">
                <h3 class="text-base sm:text-xl font-semibold flex items-center gap-2">
                    <span class="material-symbols-outlined text-white text-[20px]">
                        payments
                    </span>
                    Pengaturan SPP Aktif
                </h3>

                @if ($pengaturan)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#DFF5A6] text-[#4A5F00]">
                        {{ ucfirst($pengaturan->status) }}
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                        Belum Ada
                    </span>
                @endif
            </div>

            @if ($pengaturan)
                <div class="p-5 sm:p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-[#F8FAFC] rounded-3xl border border-slate-100 p-5">
                            <div class="w-12 h-12 rounded-full bg-[#D9E2FF] text-[#004199] flex items-center justify-center mb-4">
                                <span class="material-symbols-outlined">
                                    attach_money
                                </span>
                            </div>

                            <p class="text-xs uppercase tracking-wider text-slate-400 mb-2">
                                Nominal SPP Bulanan
                            </p>

                            <p class="text-xl sm:text-2xl font-bold text-[#001847] leading-tight">
                                Rp {{ number_format($pengaturan->nominal_spp, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="bg-[#F8FAFC] rounded-3xl border border-slate-100 p-5">
                            <div class="w-12 h-12 rounded-full bg-[#D9E2FF] text-[#004199] flex items-center justify-center mb-4">
                                <span class="material-symbols-outlined">
                                    calendar_month
                                </span>
                            </div>

                            <p class="text-xs uppercase tracking-wider text-slate-400 mb-2">
                                Tagihan Dibuat
                            </p>

                            <p class="text-xl sm:text-2xl font-bold text-[#001847] leading-tight">
                                Tanggal {{ $pengaturan->tanggal_tagihan }}
                            </p>
                        </div>

                        <div class="bg-[#F8FAFC] rounded-3xl border border-slate-100 p-5">
                            <div class="w-12 h-12 rounded-full bg-red-50 text-red-600 flex items-center justify-center mb-4">
                                <span class="material-symbols-outlined">
                                    event_busy
                                </span>
                            </div>

                            <p class="text-xs uppercase tracking-wider text-slate-400 mb-2">
                                Jatuh Tempo
                            </p>

                            <p class="text-xl sm:text-2xl font-bold text-[#001847] leading-tight">
                                Tanggal {{ $pengaturan->tanggal_jatuh_tempo }}
                            </p>
                        </div>

                        <div class="bg-[#F8FAFC] rounded-3xl border border-slate-100 p-5">
                            <div class="w-12 h-12 rounded-full bg-[#DFF5A6] text-[#4A5F00] flex items-center justify-center mb-4">
                                <span class="material-symbols-outlined">
                                    verified
                                </span>
                            </div>

                            <p class="text-xs uppercase tracking-wider text-slate-400 mb-2">
                                Status
                            </p>

                            <p class="text-xl sm:text-2xl font-bold text-[#001847] leading-tight">
                                {{ ucfirst($pengaturan->status) }}
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="p-8 text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-[32px]">
                            payments
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-[#001847]">
                        Belum Ada Pengaturan SPP
                    </h3>

                    <p class="text-sm text-slate-500 mt-2">
                        Silakan tambahkan pengaturan SPP terlebih dahulu.
                    </p>
                </div>
            @endif
        </div>

        {{-- Form Pengaturan --}}
        <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
            <div class="bg-[#001847] px-5 sm:px-6 py-4 text-white">
                <h3 class="text-base sm:text-xl font-semibold flex items-center gap-2">
                    <span class="material-symbols-outlined text-white text-[20px]">
                        settings
                    </span>
                    {{ $pengaturan ? 'Ubah Pengaturan SPP' : 'Tambah Pengaturan SPP' }}
                </h3>
            </div>

            <form action="{{ route('admin.pengaturan-spp.store') }}" method="POST" class="p-5 sm:p-6 space-y-6">
                @csrf

                {{-- Info Box --}}
                <div class="bg-[#D9E2FF] border border-[#B0C6FF] rounded-3xl p-5 flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#004199] text-white flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[26px]">
                            info
                        </span>
                    </div>

                    <div>
                        <h4 class="text-base font-bold text-[#004199]">
                            Informasi Pengaturan
                        </h4>

                        <p class="text-sm text-[#004199] mt-1 leading-relaxed">
                            Pengaturan ini akan menjadi dasar pembuatan tagihan SPP bulanan untuk seluruh santri aktif.
                        </p>
                    </div>
                </div>

                {{-- Input Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    {{-- Nominal SPP --}}
                    <div>
                        <label for="nominal_spp" class="block text-sm font-semibold text-slate-700 mb-2">
                            Nominal SPP Bulanan
                        </label>

                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm font-semibold">
                                Rp
                            </span>

                            <input
                                id="nominal_spp"
                                type="number"
                                name="nominal_spp"
                                value="{{ old('nominal_spp', $pengaturan->nominal_spp ?? 500000) }}"
                                min="0"
                                placeholder="500000"
                                class="w-full pl-11 pr-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] placeholder:text-slate-400 @error('nominal_spp') border-red-400 focus:border-red-500 focus:ring-red-500 @enderror"
                            >
                        </div>

                        @error('nominal_spp')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Tagihan --}}
                    <div>
                        <label for="tanggal_tagihan" class="block text-sm font-semibold text-slate-700 mb-2">
                            Tanggal Tagihan Dibuat
                        </label>

                        <input
                            id="tanggal_tagihan"
                            type="number"
                            name="tanggal_tagihan"
                            min="1"
                            max="28"
                            value="{{ old('tanggal_tagihan', $pengaturan->tanggal_tagihan ?? 1) }}"
                            placeholder="1"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] placeholder:text-slate-400 @error('tanggal_tagihan') border-red-400 focus:border-red-500 focus:ring-red-500 @enderror"
                        >

                        <p class="text-xs text-slate-400 mt-2">
                            Contoh: 1 berarti tagihan dibuat setiap tanggal 1.
                        </p>

                        @error('tanggal_tagihan')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Jatuh Tempo --}}
                    <div>
                        <label for="tanggal_jatuh_tempo" class="block text-sm font-semibold text-slate-700 mb-2">
                            Tanggal Jatuh Tempo
                        </label>

                        <input
                            id="tanggal_jatuh_tempo"
                            type="number"
                            name="tanggal_jatuh_tempo"
                            min="1"
                            max="28"
                            value="{{ old('tanggal_jatuh_tempo', $pengaturan->tanggal_jatuh_tempo ?? 10) }}"
                            placeholder="10"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] placeholder:text-slate-400 @error('tanggal_jatuh_tempo') border-red-400 focus:border-red-500 focus:ring-red-500 @enderror"
                        >

                        <p class="text-xs text-slate-400 mt-2">
                            Contoh: 10 berarti jatuh tempo setiap tanggal 10.
                        </p>

                        @error('tanggal_jatuh_tempo')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-4 border-t border-slate-100">
                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-slate-100 text-slate-700 rounded-2xl text-sm sm:text-base font-semibold hover:bg-slate-200 transition-colors"
                    >
                        <span class="material-symbols-outlined text-[18px]">
                            arrow_back
                        </span>
                        Kembali
                    </a>

                    <button
                        type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#004199] text-white rounded-2xl text-sm sm:text-base font-semibold hover:bg-[#00377F] transition-colors shadow-[0_8px_20px_rgba(0,65,153,0.22)]"
                    >
                        <span class="material-symbols-outlined text-[18px]">
                            save
                        </span>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

        {{-- Informasi --}}
        <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
            <div class="bg-[#001847] px-5 sm:px-6 py-4 text-white">
                <h3 class="text-base sm:text-xl font-semibold flex items-center gap-2">
                    <span class="material-symbols-outlined text-white text-[20px]">
                        help
                    </span>
                    Informasi
                </h3>
            </div>

            <div class="p-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-[#F8FAFC] border border-slate-100 rounded-3xl p-5">
                        <div class="w-12 h-12 rounded-full bg-[#D9E2FF] text-[#004199] flex items-center justify-center mb-4">
                            <span class="material-symbols-outlined">
                                autorenew
                            </span>
                        </div>

                        <h4 class="text-base font-bold text-[#001847]">
                            Otomatis Bulanan
                        </h4>

                        <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                            Tagihan SPP dibuat otomatis setiap bulan untuk seluruh santri aktif.
                        </p>
                    </div>

                    <div class="bg-[#F8FAFC] border border-slate-100 rounded-3xl p-5">
                        <div class="w-12 h-12 rounded-full bg-[#DFF5A6] text-[#4A5F00] flex items-center justify-center mb-4">
                            <span class="material-symbols-outlined">
                                paid
                            </span>
                        </div>

                        <h4 class="text-base font-bold text-[#001847]">
                            Minimal Satu Bulan
                        </h4>

                        <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                            Minimal pembayaran adalah satu bulan penuh sesuai nominal yang sedang aktif.
                        </p>
                    </div>

                    <div class="bg-[#F8FAFC] border border-slate-100 rounded-3xl p-5">
                        <div class="w-12 h-12 rounded-full bg-red-50 text-red-600 flex items-center justify-center mb-4">
                            <span class="material-symbols-outlined">
                                warning
                            </span>
                        </div>

                        <h4 class="text-base font-bold text-[#001847]">
                            Status Menunggak
                        </h4>

                        <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                            Jika tagihan bulan sebelumnya belum lunas, maka statusnya menjadi menunggak.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection