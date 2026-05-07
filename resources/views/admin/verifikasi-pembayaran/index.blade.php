@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran - SIPontren')
@section('page-title', 'Verifikasi Pembayaran')

@section('content')
    @php
        $statusOptions = [
            '' => 'Semua Status',
            'menunggu' => 'Menunggu',
            'diterima' => 'Diterima',
            'ditolak' => 'Ditolak',
        ];

        $statusLabels = [
            'menunggu' => 'Menunggu Verifikasi',
            'diterima' => 'Diterima',
            'ditolak' => 'Ditolak',
        ];

        $statusBadgeClasses = [
            'menunggu' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
            'diterima' => 'bg-[#DFF5A6] text-[#4A5F00] border-[#CDEB7C]',
            'ditolak' => 'bg-red-100 text-red-700 border-red-200',
        ];

        $selectedStatus = request('status', '');
        $selectedStatusLabel = $statusOptions[$selectedStatus] ?? 'Semua Status';

        $pembayaranItems = $pembayarans->getCollection();

        $totalMenunggu = $pembayaranItems->where('status_verifikasi', 'menunggu')->count();
        $totalDiterima = $pembayaranItems->where('status_verifikasi', 'diterima')->count();
        $totalDitolak = $pembayaranItems->where('status_verifikasi', 'ditolak')->count();
        $totalNominalHalaman = $pembayaranItems->sum('total_bayar');
    @endphp

    <div class="space-y-5 sm:space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-[#191C1E] leading-tight">
                    Verifikasi Pembayaran SPP
                </h2>

                <p class="text-sm sm:text-base text-slate-500 mt-1 leading-relaxed">
                    Periksa bukti pembayaran wali santri, lalu terima atau tolak pembayaran SPP yang masuk.
                </p>
            </div>

            <a
                href="{{ route('admin.tagihan-spp.index') }}"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-700 rounded-2xl text-sm sm:text-base font-semibold hover:bg-slate-50 transition-colors shadow-[0_10px_28px_rgba(15,23,42,0.07)]"
            >
                <span class="material-symbols-outlined text-[20px]">
                    receipt_long
                </span>
                Tagihan SPP
            </a>
        </div>

        {{-- Ringkasan --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-5">
                <p class="text-xs sm:text-sm font-semibold text-slate-500">
                    Data Halaman Ini
                </p>

                <p class="text-3xl font-bold text-[#191C1E] mt-3">
                    {{ number_format($pembayarans->count()) }}
                </p>
            </div>

            <div class="bg-yellow-50 rounded-[28px] border border-yellow-200 shadow-[0_10px_28px_rgba(234,179,8,0.08)] p-5">
                <p class="text-xs sm:text-sm font-semibold text-yellow-700">
                    Menunggu
                </p>

                <p class="text-3xl font-bold text-yellow-700 mt-3">
                    {{ number_format($totalMenunggu) }}
                </p>
            </div>

            <div class="bg-[#DFF5A6] rounded-[28px] border border-[#CDEB7C] shadow-[0_10px_28px_rgba(74,95,0,0.08)] p-5">
                <p class="text-xs sm:text-sm font-semibold text-[#4A5F00]">
                    Diterima
                </p>

                <p class="text-3xl font-bold text-[#4A5F00] mt-3">
                    {{ number_format($totalDiterima) }}
                </p>
            </div>

            <div class="bg-red-50 rounded-[28px] border border-red-200 shadow-[0_10px_28px_rgba(239,68,68,0.10)] p-5">
                <p class="text-xs sm:text-sm font-semibold text-red-700">
                    Ditolak
                </p>

                <p class="text-3xl font-bold text-red-600 mt-3">
                    {{ number_format($totalDitolak) }}
                </p>
            </div>
        </div>

        {{-- Total Nominal Halaman --}}
        <div class="bg-[#D9E2FF] border border-[#B0C6FF] rounded-[28px] p-5 sm:p-6 shadow-[0_10px_28px_rgba(37,99,235,0.08)]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#004199] text-white flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[26px]">
                            payments
                        </span>
                    </div>

                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-[#004199]">
                            Total Nominal Pada Halaman Ini
                        </h3>

                        <p class="text-sm text-[#004199] mt-1 leading-relaxed">
                            Jumlah ini mengikuti data pembayaran yang sedang tampil pada halaman saat ini.
                        </p>
                    </div>
                </div>

                <p class="text-2xl sm:text-3xl font-bold text-[#004199]">
                    Rp {{ number_format($totalNominalHalaman, 0, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-5 sm:p-6 overflow-visible">
            <form id="verifikasiFilterForm" method="GET" action="{{ route('admin.verifikasi-pembayaran.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-[240px_minmax(0,1fr)_auto] gap-3 xl:items-center">
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
                                href="{{ route('admin.verifikasi-pembayaran.index', request()->except('search')) }}"
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
                        href="{{ route('admin.verifikasi-pembayaran.index') }}"
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
            @forelse ($pembayarans as $pembayaran)
                @php
                    $statusLabel = $statusLabels[$pembayaran->status_verifikasi] ?? '-';
                    $statusClass = $statusBadgeClasses[$pembayaran->status_verifikasi] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                    $namaSantri = $pembayaran->santri->nama_santri ?? '-';
                @endphp

                <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
                    <div class="bg-[#001847] px-5 py-4 text-white flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-xs uppercase tracking-wider text-white/70 mb-1">
                                Nama Santri
                            </p>

                            <h3 class="text-lg font-bold leading-tight">
                                {{ $namaSantri }}
                            </h3>

                            <p class="text-sm text-white/70 mt-1">
                                NIS: {{ $pembayaran->santri->nis ?? '-' }}
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
                                    Upload
                                </p>

                                <p class="text-sm font-semibold text-slate-700">
                                    {{ $pembayaran->tanggal_upload ? date('d-m-Y H:i', strtotime($pembayaran->tanggal_upload)) : '-' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                    Total Bayar
                                </p>

                                <p class="text-sm font-bold text-[#004199]">
                                    Rp {{ number_format($pembayaran->total_bayar, 0, ',', '.') }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                    Kelas
                                </p>

                                <p class="text-sm font-semibold text-slate-700">
                                    {{ $pembayaran->santri->kelasMadrasah->nama_kelas ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                    Jumlah Bulan
                                </p>

                                <p class="text-sm font-semibold text-slate-700">
                                    {{ $pembayaran->jumlah_bulan }} Bulan
                                </p>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-4">
                            <p class="text-xs uppercase tracking-wider text-slate-400 mb-2">
                                Wali Santri
                            </p>

                            <div class="text-sm font-semibold text-slate-700 space-y-1">
                                @forelse ($pembayaran->santri->walis ?? [] as $wali)
                                    <div>{{ $wali->name }}</div>
                                @empty
                                    -
                                @endforelse
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-4">
                            <p class="text-xs uppercase tracking-wider text-slate-400 mb-2">
                                Bulan Dibayar
                            </p>

                            <div class="flex flex-wrap gap-2">
                                @forelse ($pembayaran->detailPembayaranSpps as $detail)
                                    @if ($detail->tagihanSpp)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#D9E2FF] text-[#004199] text-xs font-semibold border border-[#B0C6FF]">
                                            {{ str_pad($detail->tagihanSpp->bulan, 2, '0', STR_PAD_LEFT) }}-{{ $detail->tagihanSpp->tahun }}
                                        </span>
                                    @endif
                                @empty
                                    <span class="text-sm text-slate-500">-</span>
                                @endforelse
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-4">
                            <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                Catatan Admin
                            </p>

                            <p class="text-sm text-slate-600 leading-relaxed">
                                {{ $pembayaran->catatan_admin ?? '-' }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-2 pt-2">
                            <a
                                href="{{ asset('storage/' . $pembayaran->bukti_bayar) }}"
                                target="_blank"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl bg-[#D9E2FF] text-[#004199] text-sm font-semibold hover:bg-[#C6D6FF] transition-colors"
                            >
                                <span class="material-symbols-outlined text-[18px]">
                                    image
                                </span>
                                Lihat Bukti
                            </a>

                            @if ($pembayaran->status_verifikasi === 'menunggu')
                                <div class="grid grid-cols-2 gap-2">
                                    <button
                                        type="button"
                                        onclick="openTerimaModal(@js(route('admin.verifikasi-pembayaran.terima', $pembayaran)), @js($namaSantri))"
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl bg-[#DFF5A6] text-[#4A5F00] text-sm font-semibold hover:bg-[#CFEF7F] transition-colors"
                                    >
                                        <span class="material-symbols-outlined text-[18px]">
                                            check
                                        </span>
                                        Terima
                                    </button>

                                    <button
                                        type="button"
                                        onclick="openTolakModal(@js(route('admin.verifikasi-pembayaran.tolak', $pembayaran)), @js($namaSantri))"
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl bg-red-50 text-red-600 text-sm font-semibold hover:bg-red-100 transition-colors"
                                    >
                                        <span class="material-symbols-outlined text-[18px]">
                                            close
                                        </span>
                                        Tolak
                                    </button>
                                </div>
                            @else
                                <div class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl bg-slate-100 text-slate-600 text-sm font-semibold">
                                    <span class="material-symbols-outlined text-[18px]">
                                        verified
                                    </span>
                                    Sudah Diverifikasi
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-6 text-center">
                    <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3 text-slate-400">
                        <span class="material-symbols-outlined">
                            payments
                        </span>
                    </div>

                    <p class="text-sm text-slate-500">
                        Belum ada data pembayaran.
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Desktop Table --}}
        <div class="hidden lg:block bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
            <table class="w-full table-fixed text-left border-collapse">
                <colgroup>
                    <col class="w-[5%]">
                    <col class="w-[12%]">
                    <col class="w-[17%]">
                    <col class="w-[12%]">
                    <col class="w-[13%]">
                    <col class="w-[12%]">
                    <col class="w-[12%]">
                    <col class="w-[9%]">
                    <col class="w-[8%]">
                </colgroup>

                <thead>
                    <tr class="bg-[#001847] text-white text-sm">
                        <th class="py-4 px-3 font-semibold">No</th>
                        <th class="py-4 px-3 font-semibold">Upload</th>
                        <th class="py-4 px-3 font-semibold">Santri</th>
                        <th class="py-4 px-3 font-semibold">Bulan</th>
                        <th class="py-4 px-3 font-semibold">Total</th>
                        <th class="py-4 px-3 font-semibold text-center">Status</th>
                        <th class="py-4 px-3 font-semibold">Catatan</th>
                        <th class="py-4 px-3 font-semibold text-center">Bukti</th>
                        <th class="py-4 px-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="text-sm text-slate-800">
                    @forelse ($pembayarans as $pembayaran)
                        @php
                            $statusLabel = $statusLabels[$pembayaran->status_verifikasi] ?? '-';
                            $statusClass = $statusBadgeClasses[$pembayaran->status_verifikasi] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                            $namaSantri = $pembayaran->santri->nama_santri ?? '-';
                        @endphp

                        <tr class="hover:bg-[#F8FAFC] transition-colors border-b border-slate-100">
                            <td class="py-4 px-3 font-semibold text-slate-500">
                                {{ $pembayarans->firstItem() + $loop->index }}
                            </td>

                            <td class="py-4 px-3 text-slate-500 break-words">
                                {{ $pembayaran->tanggal_upload ? date('d-m-Y H:i', strtotime($pembayaran->tanggal_upload)) : '-' }}
                            </td>

                            <td class="py-4 px-3">
                                <p class="font-semibold text-[#001847] break-words">
                                    {{ $namaSantri }}
                                </p>

                                <p class="text-xs text-slate-500 mt-1">
                                    NIS: {{ $pembayaran->santri->nis ?? '-' }}
                                </p>

                                <p class="text-xs text-slate-400 mt-1">
                                    {{ $pembayaran->santri->kelasMadrasah->nama_kelas ?? '-' }}
                                </p>
                            </td>

                            <td class="py-4 px-3">
                                <div class="flex flex-wrap gap-1.5">
                                    @forelse ($pembayaran->detailPembayaranSpps as $detail)
                                        @if ($detail->tagihanSpp)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-[#D9E2FF] text-[#004199] text-[11px] font-semibold border border-[#B0C6FF]">
                                                {{ str_pad($detail->tagihanSpp->bulan, 2, '0', STR_PAD_LEFT) }}-{{ $detail->tagihanSpp->tahun }}
                                            </span>
                                        @endif
                                    @empty
                                        <span class="text-slate-500">-</span>
                                    @endforelse
                                </div>

                                <p class="text-xs text-slate-400 mt-2">
                                    {{ $pembayaran->jumlah_bulan }} bulan
                                </p>
                            </td>

                            <td class="py-4 px-3 font-semibold text-[#004199] break-words">
                                Rp {{ number_format($pembayaran->total_bayar, 0, ',', '.') }}
                            </td>

                            <td class="py-4 px-3 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            <td class="py-4 px-3 text-slate-500 break-words">
                                {{ $pembayaran->catatan_admin ?? '-' }}
                            </td>

                            <td class="py-4 px-3 text-center">
                                <a
                                    href="{{ asset('storage/' . $pembayaran->bukti_bayar) }}"
                                    target="_blank"
                                    class="inline-flex items-center justify-center p-2 rounded-xl bg-[#D9E2FF] text-[#004199] hover:bg-[#C6D6FF] transition-colors"
                                    title="Lihat Bukti"
                                >
                                    <span class="material-symbols-outlined text-[20px]">
                                        image
                                    </span>
                                </a>
                            </td>

                            <td class="py-4 px-3 text-right">
                                @if ($pembayaran->status_verifikasi === 'menunggu')
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            type="button"
                                            onclick="openTerimaModal(@js(route('admin.verifikasi-pembayaran.terima', $pembayaran)), @js($namaSantri))"
                                            class="p-1.5 text-[#4A5F00] hover:bg-[#DFF5A6] rounded-md transition-colors"
                                            title="Terima"
                                        >
                                            <span class="material-symbols-outlined text-[20px]">
                                                check
                                            </span>
                                        </button>

                                        <button
                                            type="button"
                                            onclick="openTolakModal(@js(route('admin.verifikasi-pembayaran.tolak', $pembayaran)), @js($namaSantri))"
                                            class="p-1.5 text-red-600 hover:bg-red-100 rounded-md transition-colors"
                                            title="Tolak"
                                        >
                                            <span class="material-symbols-outlined text-[20px]">
                                                close
                                            </span>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 font-semibold">
                                        Selesai
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-10 px-6 text-center text-slate-500">
                                Belum ada data pembayaran.
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
                    <span class="font-semibold text-slate-700">{{ $pembayarans->firstItem() ?? 0 }}</span>
                    -
                    <span class="font-semibold text-slate-700">{{ $pembayarans->lastItem() ?? 0 }}</span>
                    dari
                    <span class="font-semibold text-slate-700">{{ $pembayarans->total() }}</span>
                    pembayaran
                </div>

                <div class="flex justify-center lg:justify-end">
                    {{ $pembayarans->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Terima --}}
    <div id="terimaModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeTerimaModal()"></div>

        <div class="relative z-10 w-full max-w-md bg-white rounded-[28px] border border-slate-200 shadow-[0_24px_60px_rgba(15,23,42,0.25)] overflow-hidden">
            <div class="bg-[#001847] px-6 py-4 text-white flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white">
                        check_circle
                    </span>
                </div>

                <h3 class="text-lg font-bold">
                    Terima Pembayaran
                </h3>
            </div>

            <div class="p-6">
                <p class="text-slate-600 leading-relaxed">
                    Terima pembayaran dari santri
                    <span id="terimaSantriName" class="font-bold text-[#001847]"></span>?
                </p>

                <p class="text-sm text-[#004199] mt-3">
                    Tagihan yang terkait akan diperbarui sesuai proses sistem.
                </p>

                <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <button
                        type="button"
                        onclick="closeTerimaModal()"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 text-sm font-semibold hover:bg-slate-200 transition-colors"
                    >
                        Batal
                    </button>

                    <form id="terimaForm" method="POST" class="w-full sm:w-auto">
                        @csrf

                        <button
                            type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-[#4A5F00] text-white text-sm font-semibold hover:bg-[#3F5200] transition-colors"
                        >
                            <span class="material-symbols-outlined text-[18px]">
                                check
                            </span>
                            Ya, Terima
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tolak --}}
    <div id="tolakModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeTolakModal()"></div>

        <div class="relative z-10 w-full max-w-lg bg-white rounded-[28px] border border-slate-200 shadow-[0_24px_60px_rgba(15,23,42,0.25)] overflow-hidden">
            <div class="bg-[#001847] px-6 py-4 text-white flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white">
                        cancel
                    </span>
                </div>

                <h3 class="text-lg font-bold">
                    Tolak Pembayaran
                </h3>
            </div>

            <form id="tolakForm" method="POST" class="p-6">
                @csrf

                <p class="text-slate-600 leading-relaxed">
                    Tolak pembayaran dari santri
                    <span id="tolakSantriName" class="font-bold text-[#001847]"></span>?
                </p>

                <div class="mt-5">
                    <label for="catatan_admin" class="block text-sm font-semibold text-slate-700 mb-2">
                        Alasan Penolakan
                    </label>

                    <textarea
                        id="catatan_admin"
                        name="catatan_admin"
                        rows="4"
                        required
                        placeholder="Contoh: Bukti pembayaran tidak jelas / nominal tidak sesuai."
                        class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 placeholder:text-slate-400 resize-none"
                    ></textarea>
                </div>

                <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <button
                        type="button"
                        onclick="closeTolakModal()"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 text-sm font-semibold hover:bg-slate-200 transition-colors"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-red-600 text-white text-sm font-semibold hover:bg-red-700 transition-colors"
                    >
                        <span class="material-symbols-outlined text-[18px]">
                            close
                        </span>
                        Ya, Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const filterForm = document.getElementById('verifikasiFilterForm');
        const searchInput = document.getElementById('searchInput');

        let searchTimer = null;

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

        setupDropdown({
            wrapperId: 'statusDropdownWrapper',
            buttonId: 'statusDropdownButton',
            menuId: 'statusDropdownMenu',
            iconId: 'statusDropdownIcon',
            inputId: 'status',
            labelId: 'statusDropdownLabel',
            optionSelector: '.js-status-option',
        });

        function openTerimaModal(actionUrl, santriName) {
            const modal = document.getElementById('terimaModal');
            const form = document.getElementById('terimaForm');
            const name = document.getElementById('terimaSantriName');

            if (!modal || !form || !name) return;

            form.action = actionUrl;
            name.textContent = '"' + santriName + '"';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeTerimaModal() {
            const modal = document.getElementById('terimaModal');

            if (!modal) return;

            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        function openTolakModal(actionUrl, santriName) {
            const modal = document.getElementById('tolakModal');
            const form = document.getElementById('tolakForm');
            const name = document.getElementById('tolakSantriName');
            const textarea = document.getElementById('catatan_admin');

            if (!modal || !form || !name) return;

            form.action = actionUrl;
            name.textContent = '"' + santriName + '"';

            if (textarea) {
                textarea.value = '';
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeTolakModal() {
            const modal = document.getElementById('tolakModal');

            if (!modal) return;

            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeTerimaModal();
                closeTolakModal();
            }
        });
    </script>
@endsection