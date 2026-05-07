@extends('layouts.admin')

@section('title', 'Tagihan SPP - SIPontren')
@section('page-title', 'Tagihan SPP')

@section('content')
    @php
        $statusOptions = [
            '' => 'Semua Status',
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

        $selectedKelasId = request('kelas_madrasah_id', '');
        $selectedKelas = $kelasMadrasahs->firstWhere('id', $selectedKelasId);
        $selectedKelasLabel = $selectedKelas ? $selectedKelas->nama_kelas : 'Semua Kelas';

        $selectedStatus = request('status', '');
        $selectedStatusLabel = $statusOptions[$selectedStatus] ?? 'Semua Status';

        $totalBelumDibayar = $totalTagihan - $totalLunas - $totalMenungguVerifikasi - $totalMenunggak;
        $totalBelumDibayar = max($totalBelumDibayar, 0);
    @endphp

    <div class="space-y-5 sm:space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-[#191C1E] leading-tight">
                    Tagihan SPP Santri
                </h2>

                <p class="text-sm sm:text-base text-slate-500 mt-1 leading-relaxed">
                    Kelola dan pantau tagihan SPP santri berdasarkan bulan, kelas, status pembayaran, dan pencarian.
                </p>
            </div>

            <a
                href="{{ route('admin.pengaturan-spp.index') }}"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-700 rounded-2xl text-sm sm:text-base font-semibold hover:bg-slate-50 transition-colors shadow-[0_10px_28px_rgba(15,23,42,0.07)]"
            >
                <span class="material-symbols-outlined text-[20px]">
                    settings
                </span>
                Pengaturan SPP
            </a>
        </div>

        {{-- Generate Tagihan --}}
        <div class="bg-[#D9E2FF] border border-[#B0C6FF] rounded-[28px] p-5 sm:p-6 shadow-[0_10px_28px_rgba(37,99,235,0.08)]">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#004199] text-white flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[26px]">
                            autorenew
                        </span>
                    </div>

                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-[#004199]">
                            Generate Tagihan SPP
                        </h3>

                        <p class="text-sm text-[#004199] mt-1 leading-relaxed">
                            Tombol ini digunakan untuk demo. Secara konsep, tagihan akan dibuat otomatis setiap bulan sesuai pengaturan SPP.
                        </p>
                    </div>
                </div>

                <button
                    type="button"
                    onclick="openGenerateModal()"
                    class="w-full lg:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#004199] text-white rounded-2xl text-sm sm:text-base font-semibold hover:bg-[#00377F] transition-colors shadow-[0_8px_20px_rgba(0,65,153,0.22)] whitespace-nowrap"
                >
                    <span class="material-symbols-outlined text-[20px]">
                        add_card
                    </span>
                    Generate Tagihan
                </button>
            </div>
        </div>

        {{-- Ringkasan --}}
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-6">
            <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs sm:text-sm font-semibold text-slate-500">
                            Total Tagihan
                        </p>

                        <p class="text-3xl font-bold text-[#191C1E] mt-3">
                            {{ number_format($totalTagihan) }}
                        </p>
                    </div>

                    <div class="w-11 h-11 rounded-full bg-[#D9E2FF] text-[#004199] flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined">
                            receipt_long
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-[#DFF5A6] rounded-[28px] border border-[#CDEB7C] shadow-[0_10px_28px_rgba(74,95,0,0.08)] p-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs sm:text-sm font-semibold text-[#4A5F00]">
                            Lunas
                        </p>

                        <p class="text-3xl font-bold text-[#4A5F00] mt-3">
                            {{ number_format($totalLunas) }}
                        </p>
                    </div>

                    <div class="w-11 h-11 rounded-full bg-white/40 text-[#4A5F00] flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined">
                            check_circle
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 rounded-[28px] border border-yellow-200 shadow-[0_10px_28px_rgba(234,179,8,0.08)] p-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs sm:text-sm font-semibold text-yellow-700">
                            Verifikasi
                        </p>

                        <p class="text-3xl font-bold text-yellow-700 mt-3">
                            {{ number_format($totalMenungguVerifikasi) }}
                        </p>
                    </div>

                    <div class="w-11 h-11 rounded-full bg-yellow-100 text-yellow-700 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined">
                            pending
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-red-50 rounded-[28px] border border-red-200 shadow-[0_10px_28px_rgba(239,68,68,0.10)] p-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs sm:text-sm font-semibold text-red-700">
                            Menunggak
                        </p>

                        <p class="text-3xl font-bold text-red-600 mt-3">
                            {{ number_format($totalMenunggak) }}
                        </p>
                    </div>

                    <div class="w-11 h-11 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined">
                            warning
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-5 col-span-2 lg:col-span-1">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs sm:text-sm font-semibold text-slate-500">
                            Belum Dibayar
                        </p>

                        <p class="text-3xl font-bold text-slate-700 mt-3">
                            {{ number_format($totalBelumDibayar) }}
                        </p>
                    </div>

                    <div class="w-11 h-11 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined">
                            payments
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-5 sm:p-6 overflow-visible">
            <form id="tagihanFilterForm" method="GET" action="{{ route('admin.tagihan-spp.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-[180px_240px_220px_minmax(0,1fr)_auto] gap-3 xl:items-center">
                    {{-- Bulan --}}
                    <div>
                        <x-sipontren-date-picker
    name="bulan"
    mode="month"
    :value="request('bulan')"
    placeholder="Pilih Bulan"
/>
                    </div>

                    {{-- Kelas Madrasah --}}
                    <div class="relative" id="kelasDropdownWrapper">
                        <input
                            type="hidden"
                            name="kelas_madrasah_id"
                            id="kelas_madrasah_id"
                            value="{{ $selectedKelasId }}"
                        >

                        <button
                            type="button"
                            id="kelasDropdownButton"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] flex items-center justify-between gap-3 text-left"
                        >
                            <span id="kelasDropdownLabel" class="truncate">
                                {{ $selectedKelasLabel }}
                            </span>

                            <span id="kelasDropdownIcon" class="material-symbols-outlined text-slate-400 transition-transform duration-200 shrink-0">
                                expand_more
                            </span>
                        </button>

                        <div
                            id="kelasDropdownMenu"
                            class="hidden absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-[0_16px_40px_rgba(15,23,42,0.14)] overflow-hidden z-50"
                        >
                            <button
                                type="button"
                                class="js-kelas-option w-full px-4 py-3 text-left text-sm sm:text-base transition-colors flex items-center justify-between gap-3 {{ $selectedKelasId === '' ? 'bg-[#D9E2FF] text-[#004199] font-semibold' : 'text-slate-700 hover:bg-[#F8FAFC]' }}"
                                data-value=""
                                data-label="Semua Kelas"
                            >
                                <span>Semua Kelas</span>

                                @if ($selectedKelasId === '')
                                    <span class="material-symbols-outlined text-[18px] dropdown-check-icon">
                                        check
                                    </span>
                                @endif
                            </button>

                            @foreach ($kelasMadrasahs as $kelas)
                                <button
                                    type="button"
                                    class="js-kelas-option w-full px-4 py-3 text-left text-sm sm:text-base transition-colors flex items-center justify-between gap-3 {{ $selectedKelasId == $kelas->id ? 'bg-[#D9E2FF] text-[#004199] font-semibold' : 'text-slate-700 hover:bg-[#F8FAFC]' }}"
                                    data-value="{{ $kelas->id }}"
                                    data-label="{{ $kelas->nama_kelas }}"
                                >
                                    <span class="truncate">{{ $kelas->nama_kelas }}</span>

                                    @if ($selectedKelasId == $kelas->id)
                                        <span class="material-symbols-outlined text-[18px] dropdown-check-icon shrink-0">
                                            check
                                        </span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="relative" id="statusDropdownWrapper">
                        <input
                            type="hidden"
                            name="status"
                            id="status"
                            value="{{ $selectedStatus }}"
                        >

                        <button
                            type="button"
                            id="statusDropdownButton"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] flex items-center justify-between gap-3 text-left"
                        >
                            <span id="statusDropdownLabel" class="truncate">
                                {{ $selectedStatusLabel }}
                            </span>

                            <span id="statusDropdownIcon" class="material-symbols-outlined text-slate-400 transition-transform duration-200 shrink-0">
                                expand_more
                            </span>
                        </button>

                        <div
                            id="statusDropdownMenu"
                            class="hidden absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-[0_16px_40px_rgba(15,23,42,0.14)] overflow-hidden z-50"
                        >
                            @foreach ($statusOptions as $value => $label)
                                <button
                                    type="button"
                                    class="js-status-option w-full px-4 py-3 text-left text-sm sm:text-base transition-colors flex items-center justify-between gap-3 {{ $selectedStatus === $value ? 'bg-[#D9E2FF] text-[#004199] font-semibold' : 'text-slate-700 hover:bg-[#F8FAFC]' }}"
                                    data-value="{{ $value }}"
                                    data-label="{{ $label }}"
                                >
                                    <span>{{ $label }}</span>

                                    @if ($selectedStatus === $value)
                                        <span class="material-symbols-outlined text-[18px] dropdown-check-icon">
                                            check
                                        </span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Search --}}
                    <div class="relative min-w-0">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[22px]">
                            search
                        </span>

                        <input
                            id="searchInput"
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama santri / NIS..."
                            autocomplete="off"
                            class="w-full pl-12 pr-12 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] placeholder:text-slate-400"
                        >

                        @if (request('search'))
                            <a
                                href="{{ route('admin.tagihan-spp.index', request()->except('search')) }}"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-600 transition-colors"
                                title="Hapus pencarian"
                            >
                                <span class="material-symbols-outlined text-[20px]">
                                    close
                                </span>
                            </a>
                        @endif
                    </div>

                    {{-- Reset --}}
                    <a
                        href="{{ route('admin.tagihan-spp.index') }}"
                        class="w-full xl:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-slate-100 text-slate-700 rounded-2xl text-sm sm:text-base font-semibold hover:bg-slate-200 transition-colors whitespace-nowrap"
                    >
                        <span class="material-symbols-outlined text-[18px]">
                            refresh
                        </span>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Mobile / Tablet Card List --}}
        <div class="lg:hidden space-y-4">
            @forelse ($tagihans as $tagihan)
                @php
                    $statusLabel = $statusOptions[$tagihan->status] ?? '-';
                    $statusClass = $statusBadgeClasses[$tagihan->status] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                    $bulanTagihan = str_pad($tagihan->bulan, 2, '0', STR_PAD_LEFT) . '-' . $tagihan->tahun;
                @endphp

                <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
                    <div class="bg-[#001847] px-5 py-4 text-white flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-xs uppercase tracking-wider text-white/70 mb-1">
                                Nama Santri
                            </p>

                            <h3 class="text-lg font-bold leading-tight">
                                {{ $tagihan->santri->nama_santri ?? '-' }}
                            </h3>

                            <p class="text-sm text-white/70 mt-1">
                                {{ $tagihan->santri->kelasMadrasah->nama_kelas ?? '-' }}
                            </p>
                        </div>

                        <span class="shrink-0 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <div class="p-5 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                    Bulan
                                </p>

                                <p class="text-sm font-semibold text-slate-700">
                                    {{ $bulanTagihan }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                    Nominal
                                </p>

                                <p class="text-sm font-bold text-[#004199]">
                                    Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-4">
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
                        Belum ada data tagihan SPP.
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Desktop Table --}}
        <div class="hidden lg:block bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
            <table class="w-full table-fixed text-left border-collapse">
                <colgroup>
                    <col class="w-[6%]">
                    <col class="w-[20%]">
                    <col class="w-[17%]">
                    <col class="w-[11%]">
                    <col class="w-[16%]">
                    <col class="w-[14%]">
                    <col class="w-[16%]">
                </colgroup>

                <thead>
                    <tr class="bg-[#001847] text-white text-sm">
                        <th class="py-4 px-4 font-semibold">No</th>
                        <th class="py-4 px-4 font-semibold">Nama Santri</th>
                        <th class="py-4 px-4 font-semibold">Kelas</th>
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
                                {{ $tagihans->firstItem() + $loop->index }}
                            </td>

                            <td class="py-4 px-4 font-semibold text-[#001847] break-words">
                                {{ $tagihan->santri->nama_santri ?? '-' }}
                            </td>

                            <td class="py-4 px-4 text-slate-500 break-words">
                                {{ $tagihan->santri->kelasMadrasah->nama_kelas ?? '-' }}
                            </td>

                            <td class="py-4 px-4 text-slate-500">
                                {{ $bulanTagihan }}
                            </td>

                            <td class="py-4 px-4 font-semibold text-[#004199]">
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
                            <td colspan="7" class="py-10 px-6 text-center text-slate-500">
                                Belum ada data tagihan SPP.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-5">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="text-sm text-slate-500 text-center lg:text-left">
                    Menampilkan
                    <span class="font-semibold text-slate-700">{{ $tagihans->firstItem() ?? 0 }}</span>
                    -
                    <span class="font-semibold text-slate-700">{{ $tagihans->lastItem() ?? 0 }}</span>
                    dari
                    <span class="font-semibold text-slate-700">{{ $tagihans->total() }}</span>
                    tagihan
                </div>

                <div class="flex justify-center lg:justify-end">
                    {{ $tagihans->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Generate Modal --}}
    <div id="generateModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeGenerateModal()"></div>

        <div class="relative z-10 w-full max-w-md bg-white rounded-[28px] border border-slate-200 shadow-[0_24px_60px_rgba(15,23,42,0.25)] overflow-hidden">
            <div class="bg-[#001847] px-6 py-4 text-white flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white">
                        add_card
                    </span>
                </div>

                <h3 class="text-lg font-bold">
                    Generate Tagihan
                </h3>
            </div>

            <div class="p-6">
                <p class="text-slate-600 leading-relaxed">
                    Generate tagihan SPP bulan ini untuk seluruh santri aktif?
                </p>

                <p class="text-sm text-[#004199] mt-3">
                    Pastikan pengaturan SPP sudah benar sebelum melanjutkan.
                </p>

                <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <button
                        type="button"
                        onclick="closeGenerateModal()"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 text-sm font-semibold hover:bg-slate-200 transition-colors"
                    >
                        Batal
                    </button>

                    <form action="{{ route('admin.tagihan-spp.generate') }}" method="POST" class="w-full sm:w-auto">
                        @csrf

                        <button
                            type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-[#004199] text-white text-sm font-semibold hover:bg-[#00377F] transition-colors"
                        >
                            <span class="material-symbols-outlined text-[18px]">
                                add_card
                            </span>
                            Ya, Generate
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const filterForm = document.getElementById('tagihanFilterForm');
        const bulanInput = document.getElementById('bulanInput');
        const searchInput = document.getElementById('searchInput');

        let searchTimer = null;

        if (bulanInput && filterForm) {
            bulanInput.addEventListener('change', function () {
                filterForm.submit();
            });
        }

        if (searchInput && filterForm) {
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimer);

                searchTimer = setTimeout(function () {
                    filterForm.submit();
                }, 500);
            });
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
                closeAllDropdowns(config.menuId);

                menu.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');
            });

            options.forEach(function (option) {
                option.addEventListener('click', function () {
                    const value = option.dataset.value ?? '';
                    const text = option.dataset.label ?? '';

                    input.value = value;
                    label.textContent = text;

                    menu.classList.add('hidden');
                    icon.classList.remove('rotate-180');

                    if (filterForm) {
                        filterForm.submit();
                    }
                });
            });

            document.addEventListener('click', function (event) {
                if (!wrapper.contains(event.target)) {
                    menu.classList.add('hidden');
                    icon.classList.remove('rotate-180');
                }
            });
        }

        function closeAllDropdowns(exceptMenuId = null) {
            const dropdowns = [
                {
                    menu: 'kelasDropdownMenu',
                    icon: 'kelasDropdownIcon',
                },
                {
                    menu: 'statusDropdownMenu',
                    icon: 'statusDropdownIcon',
                },
            ];

            dropdowns.forEach(function (dropdown) {
                if (dropdown.menu === exceptMenuId) {
                    return;
                }

                const menu = document.getElementById(dropdown.menu);
                const icon = document.getElementById(dropdown.icon);

                if (menu) {
                    menu.classList.add('hidden');
                }

                if (icon) {
                    icon.classList.remove('rotate-180');
                }
            });
        }

        setupDropdown({
            wrapperId: 'kelasDropdownWrapper',
            buttonId: 'kelasDropdownButton',
            menuId: 'kelasDropdownMenu',
            iconId: 'kelasDropdownIcon',
            inputId: 'kelas_madrasah_id',
            labelId: 'kelasDropdownLabel',
            optionSelector: '.js-kelas-option',
        });

        setupDropdown({
            wrapperId: 'statusDropdownWrapper',
            buttonId: 'statusDropdownButton',
            menuId: 'statusDropdownMenu',
            iconId: 'statusDropdownIcon',
            inputId: 'status',
            labelId: 'statusDropdownLabel',
            optionSelector: '.js-status-option',
        });

        function openGenerateModal() {
            const modal = document.getElementById('generateModal');

            if (!modal) {
                return;
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeGenerateModal() {
            const modal = document.getElementById('generateModal');

            if (!modal) {
                return;
            }

            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeGenerateModal();
            }
        });
    </script>
@endsection