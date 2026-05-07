@extends('layouts.orang-tua')

@section('title', 'Pembayaran SPP - SIPontren')
@section('page-title', 'Pembayaran SPP')

@section('content')
    @php
        $statusOptions = [
            'belum_dibayar' => 'Belum Dibayar',
            'menunggak' => 'Menunggak',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'lunas' => 'Lunas',
            'ditolak' => 'Ditolak',
        ];

        $statusBadgeClasses = [
            'belum_dibayar' => 'bg-slate-100 text-slate-600 border-slate-200',
            'menunggak' => 'bg-red-100 text-red-700 border-red-200',
            'menunggu_verifikasi' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
            'lunas' => 'bg-[#DFF5A6] text-[#4A5F00] border-[#CDEB7C]',
            'ditolak' => 'bg-red-100 text-red-700 border-red-200',
        ];

        $nominalPerBulan = $jumlahTagihanBelumLunas > 0
            ? floor($totalTagihanBelumLunas / $jumlahTagihanBelumLunas)
            : 0;

        $jumlahOptions = [];

        if ($jumlahTagihanBelumLunas > 0) {
            for ($i = 1; $i <= min(3, $jumlahTagihanBelumLunas); $i++) {
                $jumlahOptions[$i] = 'Bayar ' . $i . ' Bulan';
            }

            if ($jumlahTagihanBelumLunas > 3) {
                $jumlahOptions[$jumlahTagihanBelumLunas] = 'Bayar Semua Tagihan';
            }
        }

        $selectedJumlahBulan = old('jumlah_bulan', '');
        $selectedJumlahLabel = $jumlahOptions[$selectedJumlahBulan] ?? 'Pilih jumlah bulan';
    @endphp

    <div class="space-y-5 sm:space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-[#191C1E] leading-tight">
                    Pembayaran SPP
                </h2>

                <p class="text-sm sm:text-base text-slate-500 mt-1 leading-relaxed">
                    Lihat tagihan SPP anak dan kirim bukti pembayaran untuk diverifikasi admin.
                </p>
            </div>

            <a
                href="{{ route('orang-tua.riwayat-pembayaran.index') }}"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-700 rounded-2xl text-sm sm:text-base font-semibold hover:bg-slate-50 transition-colors shadow-[0_10px_28px_rgba(15,23,42,0.07)]"
            >
                <span class="material-symbols-outlined text-[20px]">
                    history
                </span>
                Riwayat Pembayaran
            </a>
        </div>

        {{-- Privacy Notice --}}
        <div class="bg-[#D9E2FF] border border-[#B0C6FF] rounded-[28px] p-5 shadow-[0_10px_28px_rgba(37,99,235,0.08)] flex items-start gap-3">
            <span class="material-symbols-outlined text-[#004199] mt-0.5 shrink-0" style="font-variation-settings: 'FILL' 1;">
                shield
            </span>

            <p class="text-sm text-[#004199] leading-relaxed">
                <strong>Data Privasi:</strong>
                Data yang ditampilkan hanya tagihan SPP anak Anda.
            </p>
        </div>

        {{-- Profil Santri --}}
        <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
            <div class="bg-[#001847] px-5 sm:px-6 py-4 text-white">
                <h3 class="text-base sm:text-xl font-semibold flex items-center gap-2">
                    <span class="material-symbols-outlined text-white text-[20px]">
                        school
                    </span>
                    Profil Santri
                </h3>
            </div>

            <div class="p-5 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center gap-5">
                    <div class="w-20 h-20 rounded-3xl bg-[#001847] text-white flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[42px]">
                            person
                        </span>
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                            Nama Santri
                        </p>

                        <h3 class="text-xl sm:text-2xl font-bold text-[#001847] leading-tight mt-1">
                            {{ $santri->nama_santri }}
                        </h3>

                        <p class="text-sm text-slate-500 mt-1">
                            NIS: {{ $santri->nis }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6 border-t border-slate-100 pt-5">
                    <div class="bg-[#F8FAFC] rounded-2xl border border-slate-100 p-4">
                        <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                            Kelas Madrasah
                        </p>

                        <p class="text-sm font-semibold text-slate-700">
                            {{ $santri->kelasMadrasah->nama_kelas ?? '-' }}
                        </p>
                    </div>

                    <div class="bg-[#F8FAFC] rounded-2xl border border-slate-100 p-4">
                        <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                            Total Belum Lunas
                        </p>

                        <p class="text-sm font-semibold text-red-600">
                            {{ $jumlahTagihanBelumLunas }} Tagihan
                        </p>
                    </div>

                    <div class="bg-[#F8FAFC] rounded-2xl border border-slate-100 p-4">
                        <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                            Nominal Belum Lunas
                        </p>

                        <p class="text-sm font-bold text-[#004199]">
                            Rp {{ number_format($totalTagihanBelumLunas, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ringkasan Pembayaran --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-slate-500">
                            Total Tagihan Belum Lunas
                        </p>

                        <p class="text-3xl font-bold text-red-600 mt-2">
                            {{ $jumlahTagihanBelumLunas }}
                        </p>
                    </div>

                    <div class="w-12 h-12 rounded-full bg-red-50 text-red-600 flex items-center justify-center">
                        <span class="material-symbols-outlined">
                            warning
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-[#D9E2FF] rounded-[28px] border border-[#B0C6FF] shadow-[0_10px_28px_rgba(37,99,235,0.08)] p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-[#004199]">
                            Total Bayar
                        </p>

                        <p class="text-2xl sm:text-3xl font-bold text-[#004199] mt-2">
                            Rp {{ number_format($totalTagihanBelumLunas, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="w-12 h-12 rounded-full bg-white/40 text-[#004199] flex items-center justify-center">
                        <span class="material-symbols-outlined">
                            payments
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-[#DFF5A6] rounded-[28px] border border-[#CDEB7C] shadow-[0_10px_28px_rgba(74,95,0,0.08)] p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-[#4A5F00]">
                            Minimal Pembayaran
                        </p>

                        <p class="text-2xl sm:text-3xl font-bold text-[#4A5F00] mt-2">
                            1 Bulan
                        </p>
                    </div>

                    <div class="w-12 h-12 rounded-full bg-white/40 text-[#4A5F00] flex items-center justify-center">
                        <span class="material-symbols-outlined">
                            check_circle
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Daftar Tagihan Mobile --}}
        <div class="lg:hidden space-y-4">
            @forelse ($tagihans as $tagihan)
                @php
                    $statusLabel = $statusOptions[$tagihan->status] ?? '-';
                    $statusClass = $statusBadgeClasses[$tagihan->status] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                    $bulanTagihan = str_pad($tagihan->bulan, 2, '0', STR_PAD_LEFT) . '-' . $tagihan->tahun;
                @endphp

                <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
                    <div class="bg-[#001847] px-5 py-4 text-white flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-white/70 mb-1">
                                Bulan Tagihan
                            </p>

                            <h3 class="text-lg font-bold leading-tight">
                                {{ $bulanTagihan }}
                            </h3>
                        </div>

                        <span class="shrink-0 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <div class="p-5 grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                Nominal
                            </p>

                            <p class="text-sm font-bold text-[#004199]">
                                Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                Jatuh Tempo
                            </p>

                            <p class="text-sm font-semibold text-slate-700">
                                {{ $tagihan->jatuh_tempo ? date('d-m-Y', strtotime($tagihan->jatuh_tempo)) : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-6 text-center">
                    <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3 text-slate-400">
                        <span class="material-symbols-outlined">
                            receipt_long
                        </span>
                    </div>

                    <p class="text-sm text-slate-500">
                        Belum ada tagihan SPP.
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Daftar Tagihan Desktop --}}
        <div class="hidden lg:block bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
            <div class="bg-[#001847] px-5 sm:px-6 py-4 text-white">
                <h3 class="text-base sm:text-xl font-semibold flex items-center gap-2">
                    <span class="material-symbols-outlined text-white text-[20px]">
                        receipt_long
                    </span>
                    Daftar Tagihan SPP
                </h3>
            </div>

            <table class="w-full table-fixed text-left border-collapse">
                <colgroup>
                    <col class="w-[8%]">
                    <col class="w-[20%]">
                    <col class="w-[25%]">
                    <col class="w-[22%]">
                    <col class="w-[25%]">
                </colgroup>

                <thead>
                    <tr class="bg-[#001847] text-white text-sm border-t border-white/10">
                        <th class="py-4 px-4 font-semibold">No</th>
                        <th class="py-4 px-4 font-semibold">Bulan</th>
                        <th class="py-4 px-4 font-semibold">Nominal</th>
                        <th class="py-4 px-4 font-semibold">Jatuh Tempo</th>
                        <th class="py-4 px-4 font-semibold text-center">Status</th>
                    </tr>
                </thead>

                <tbody class="text-sm text-slate-800">
                    @forelse ($tagihans as $tagihan)
                        @php
                            $statusLabel = $statusOptions[$tagihan->status] ?? '-';
                            $statusClass = $statusBadgeClasses[$tagihan->status] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                            $bulanTagihan = str_pad($tagihan->bulan, 2, '0', STR_PAD_LEFT) . '-' . $tagihan->tahun;
                        @endphp

                        <tr class="hover:bg-[#F8FAFC] transition-colors border-b border-slate-100">
                            <td class="py-4 px-4 font-semibold text-slate-500">
                                {{ $loop->iteration }}
                            </td>

                            <td class="py-4 px-4 font-semibold text-[#001847]">
                                {{ $bulanTagihan }}
                            </td>

                            <td class="py-4 px-4 font-bold text-[#004199]">
                                Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}
                            </td>

                            <td class="py-4 px-4 text-slate-500">
                                {{ $tagihan->jatuh_tempo ? date('d-m-Y', strtotime($tagihan->jatuh_tempo)) : '-' }}
                            </td>

                            <td class="py-4 px-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 px-6 text-center text-slate-500">
                                Belum ada tagihan SPP.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Bayar SPP --}}
        <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-visible">
            <div class="bg-[#001847] px-5 sm:px-6 py-4 text-white rounded-t-[28px]">
                <h3 class="text-base sm:text-xl font-semibold flex items-center gap-2">
                    <span class="material-symbols-outlined text-white text-[20px]">
                        payments
                    </span>
                    Bayar SPP
                </h3>
            </div>

            @if ($jumlahTagihanBelumLunas > 0)
                <form
                    id="paymentForm"
                    action="{{ route('orang-tua.pembayaran-spp.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="p-5 sm:p-6 space-y-6"
                >
                    @csrf

                    <div class="bg-[#D9E2FF] border border-[#B0C6FF] rounded-3xl p-5 flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#004199] text-white flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[26px]">
                                info
                            </span>
                        </div>

                        <div>
                            <h4 class="text-base font-bold text-[#004199]">
                                Informasi Pembayaran
                            </h4>

                            <p class="text-sm text-[#004199] mt-1 leading-relaxed">
                                Minimal pembayaran adalah <strong>1 bulan SPP penuh</strong>. Pembayaran akan diprioritaskan untuk tagihan paling lama.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- Jumlah Bulan --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Pilih Jumlah Bulan yang Dibayar
                            </label>

                            <div class="relative" id="jumlahDropdownWrapper">
                                <input
                                    type="hidden"
                                    name="jumlah_bulan"
                                    id="jumlah_bulan"
                                    value="{{ $selectedJumlahBulan }}"
                                    required
                                >

                                <button
                                    type="button"
                                    id="jumlahDropdownButton"
                                    class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] flex items-center justify-between gap-3 text-left @error('jumlah_bulan') border-red-400 @enderror"
                                >
                                    <span id="jumlahDropdownLabel" class="{{ $selectedJumlahBulan ? 'text-slate-800' : 'text-slate-400' }} truncate">
                                        {{ $selectedJumlahLabel }}
                                    </span>

                                    <span id="jumlahDropdownIcon" class="material-symbols-outlined text-slate-400 transition-transform duration-200 shrink-0">
                                        expand_more
                                    </span>
                                </button>

                                <div
                                    id="jumlahDropdownMenu"
                                    class="hidden absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-[0_16px_40px_rgba(15,23,42,0.14)] overflow-hidden z-50"
                                >
                                    <button
                                        type="button"
                                        class="js-jumlah-option w-full px-4 py-3 text-left text-sm sm:text-base transition-colors flex items-center justify-between gap-3 {{ !$selectedJumlahBulan ? 'bg-[#D9E2FF] text-[#004199] font-semibold' : 'text-slate-700 hover:bg-[#F8FAFC]' }}"
                                        data-value=""
                                        data-label="Pilih jumlah bulan"
                                        data-placeholder="true"
                                        data-total="0"
                                    >
                                        <span>Pilih jumlah bulan</span>

                                        @if (!$selectedJumlahBulan)
                                            <span class="material-symbols-outlined text-[18px] dropdown-check-icon">
                                                check
                                            </span>
                                        @endif
                                    </button>

                                    @foreach ($jumlahOptions as $value => $label)
                                        @php
                                            $isSelected = (string) $selectedJumlahBulan === (string) $value;
                                            $estimasiTotal = $value == $jumlahTagihanBelumLunas
                                                ? $totalTagihanBelumLunas
                                                : $nominalPerBulan * $value;
                                        @endphp

                                        <button
                                            type="button"
                                            class="js-jumlah-option w-full px-4 py-3 text-left text-sm sm:text-base transition-colors flex items-center justify-between gap-3 {{ $isSelected ? 'bg-[#D9E2FF] text-[#004199] font-semibold' : 'text-slate-700 hover:bg-[#F8FAFC]' }}"
                                            data-value="{{ $value }}"
                                            data-label="{{ $label }}"
                                            data-placeholder="false"
                                            data-total="{{ $estimasiTotal }}"
                                        >
                                            <span>
                                                <span class="block font-semibold">{{ $label }}</span>
                                                <span class="block text-xs text-slate-500 mt-1">
                                                    Estimasi: Rp {{ number_format($estimasiTotal, 0, ',', '.') }}
                                                </span>
                                            </span>

                                            @if ($isSelected)
                                                <span class="material-symbols-outlined text-[18px] dropdown-check-icon shrink-0">
                                                    check
                                                </span>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            @error('jumlah_bulan')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror

                            <p class="text-xs text-slate-400 mt-2">
                                Pembayaran akan diproses mulai dari tagihan paling lama.
                            </p>
                        </div>

                        {{-- Upload Bukti --}}
                        <div>
                            <label for="bukti_bayar" class="block text-sm font-semibold text-slate-700 mb-2">
                                Upload Bukti Pembayaran
                            </label>

                            <label
                                for="bukti_bayar"
                                class="cursor-pointer flex flex-col items-center justify-center gap-3 px-4 py-6 bg-[#F8FAFC] border border-dashed border-slate-300 rounded-3xl hover:border-[#004199] transition-colors @error('bukti_bayar') border-red-400 @enderror"
                            >
                                <span class="material-symbols-outlined text-[#004199] text-[36px]">
                                    upload_file
                                </span>

                                <div class="text-center">
                                    <p id="fileNameLabel" class="text-sm font-semibold text-[#001847]">
                                        Pilih file bukti pembayaran
                                    </p>

                                    <p class="text-xs text-slate-400 mt-1">
                                        Format: jpg, jpeg, png, atau pdf. Maksimal 2 MB.
                                    </p>
                                </div>
                            </label>

                            <input
                                id="bukti_bayar"
                                type="file"
                                name="bukti_bayar"
                                accept=".jpg,.jpeg,.png,.pdf"
                                required
                                class="hidden"
                            >

                            @error('bukti_bayar')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Estimasi Bayar --}}
                    <div class="bg-[#F8FAFC] border border-slate-200 rounded-3xl p-5">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-500">
                                    Estimasi Pembayaran
                                </p>

                                <p class="text-xs text-slate-400 mt-1">
                                    Nominal mengikuti jumlah bulan yang dipilih.
                                </p>
                            </div>

                            <p id="estimasiBayar" class="text-2xl font-bold text-[#004199]">
                                Rp 0
                            </p>
                        </div>
                    </div>

                    {{-- Action --}}
                    <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-4 border-t border-slate-100">
                        <a
                            href="{{ route('orang-tua.riwayat-pembayaran.index') }}"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-slate-100 text-slate-700 rounded-2xl text-sm sm:text-base font-semibold hover:bg-slate-200 transition-colors"
                        >
                            <span class="material-symbols-outlined text-[18px]">
                                history
                            </span>
                            Riwayat Pembayaran
                        </a>

                        <button
                            type="button"
                            onclick="validateAndOpenPaymentModal()"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#004199] text-white rounded-2xl text-sm sm:text-base font-semibold hover:bg-[#00377F] transition-colors shadow-[0_8px_20px_rgba(0,65,153,0.22)]"
                        >
                            <span class="material-symbols-outlined text-[18px]">
                                send
                            </span>
                            Kirim Pembayaran
                        </button>
                    </div>
                </form>
            @else
                <div class="p-8 text-center">
                    <div class="w-16 h-16 rounded-full bg-[#DFF5A6] text-[#4A5F00] flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-[32px]">
                            check_circle
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-[#001847]">
                        Tidak Ada Tagihan yang Perlu Dibayar
                    </h3>

                    <p class="text-sm text-slate-500 mt-2 max-w-md mx-auto leading-relaxed">
                        Semua tagihan sudah lunas atau sedang menunggu verifikasi.
                    </p>

                    <a
                        href="{{ route('orang-tua.riwayat-pembayaran.index') }}"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#004199] text-white rounded-2xl text-sm font-semibold hover:bg-[#00377F] transition-colors shadow-[0_8px_20px_rgba(0,65,153,0.22)] mt-5"
                    >
                        <span class="material-symbols-outlined text-[18px]">
                            history
                        </span>
                        Lihat Riwayat
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Payment Modal --}}
    <div id="paymentModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closePaymentModal()"></div>

        <div class="relative z-10 w-full max-w-md bg-white rounded-[28px] border border-slate-200 shadow-[0_24px_60px_rgba(15,23,42,0.25)] overflow-hidden">
            <div class="bg-[#001847] px-6 py-4 text-white flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white">
                        payments
                    </span>
                </div>

                <h3 class="text-lg font-bold">
                    Kirim Pembayaran
                </h3>
            </div>

            <div class="p-6">
                <p class="text-slate-600 leading-relaxed">
                    Kirim bukti pembayaran SPP untuk diverifikasi admin?
                </p>

                <p class="text-sm text-[#004199] mt-3">
                    Pastikan jumlah bulan dan bukti pembayaran sudah benar.
                </p>

                <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <button
                        type="button"
                        onclick="closePaymentModal()"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 text-sm font-semibold hover:bg-slate-200 transition-colors"
                    >
                        Batal
                    </button>

                    <button
                        type="button"
                        onclick="submitPaymentForm()"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-[#004199] text-white text-sm font-semibold hover:bg-[#00377F] transition-colors"
                    >
                        <span class="material-symbols-outlined text-[18px]">
                            send
                        </span>
                        Ya, Kirim
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function formatRupiah(value) {
            const number = Number(value || 0);

            return 'Rp ' + number.toLocaleString('id-ID');
        }

        function setupDropdown(config) {
            const wrapper = document.getElementById(config.wrapperId);
            const button = document.getElementById(config.buttonId);
            const menu = document.getElementById(config.menuId);
            const icon = document.getElementById(config.iconId);
            const input = document.getElementById(config.inputId);
            const label = document.getElementById(config.labelId);
            const options = document.querySelectorAll(config.optionSelector);

            if (!wrapper || !button || !menu || !icon || !input || !label) {
                return;
            }

            button.addEventListener('click', function () {
                menu.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');
            });

            options.forEach(function (option) {
                option.addEventListener('click', function () {
                    const value = option.dataset.value ?? '';
                    const text = option.dataset.label ?? '';
                    const total = option.dataset.total ?? 0;
                    const isPlaceholder = option.dataset.placeholder === 'true';

                    input.value = value;
                    label.textContent = text;

                    if (isPlaceholder) {
                        label.classList.remove('text-slate-800');
                        label.classList.add('text-slate-400');
                    } else {
                        label.classList.remove('text-slate-400');
                        label.classList.add('text-slate-800');
                    }

                    const estimasiBayar = document.getElementById('estimasiBayar');

                    if (estimasiBayar) {
                        estimasiBayar.textContent = formatRupiah(total);
                    }

                    resetDropdownOptions(options);
                    activateDropdownOption(option);

                    menu.classList.add('hidden');
                    icon.classList.remove('rotate-180');
                });
            });

            document.addEventListener('click', function (event) {
                if (!wrapper.contains(event.target)) {
                    menu.classList.add('hidden');
                    icon.classList.remove('rotate-180');
                }
            });
        }

        function resetDropdownOptions(options) {
            options.forEach(function (option) {
                option.classList.remove('bg-[#D9E2FF]', 'text-[#004199]', 'font-semibold');
                option.classList.add('text-slate-700', 'hover:bg-[#F8FAFC]');

                const oldCheck = option.querySelector('.dropdown-check-icon');

                if (oldCheck) {
                    oldCheck.remove();
                }
            });
        }

        function activateDropdownOption(option) {
            option.classList.remove('text-slate-700', 'hover:bg-[#F8FAFC]');
            option.classList.add('bg-[#D9E2FF]', 'text-[#004199]', 'font-semibold');

            const checkIcon = document.createElement('span');
            checkIcon.className = 'material-symbols-outlined text-[18px] dropdown-check-icon shrink-0';
            checkIcon.textContent = 'check';

            option.appendChild(checkIcon);
        }

        setupDropdown({
            wrapperId: 'jumlahDropdownWrapper',
            buttonId: 'jumlahDropdownButton',
            menuId: 'jumlahDropdownMenu',
            iconId: 'jumlahDropdownIcon',
            inputId: 'jumlah_bulan',
            labelId: 'jumlahDropdownLabel',
            optionSelector: '.js-jumlah-option',
        });

        const buktiInput = document.getElementById('bukti_bayar');
        const fileNameLabel = document.getElementById('fileNameLabel');

        if (buktiInput && fileNameLabel) {
            buktiInput.addEventListener('change', function () {
                if (this.files && this.files.length > 0) {
                    fileNameLabel.textContent = this.files[0].name;
                } else {
                    fileNameLabel.textContent = 'Pilih file bukti pembayaran';
                }
            });
        }

        function validateAndOpenPaymentModal() {
            const form = document.getElementById('paymentForm');

            if (!form) {
                return;
            }

            if (!form.reportValidity()) {
                return;
            }

            openPaymentModal();
        }

        function openPaymentModal() {
            const modal = document.getElementById('paymentModal');

            if (!modal) {
                return;
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closePaymentModal() {
            const modal = document.getElementById('paymentModal');

            if (!modal) {
                return;
            }

            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        function submitPaymentForm() {
            const form = document.getElementById('paymentForm');

            if (!form) {
                return;
            }

            if (form.requestSubmit) {
                form.requestSubmit();
            } else {
                form.submit();
            }
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closePaymentModal();
            }
        });
    </script>
@endsection