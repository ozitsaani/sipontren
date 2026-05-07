@extends('layouts.orang-tua')

@section('title', 'Riwayat Pembayaran - SIPontren')
@section('page-title', 'Riwayat Pembayaran')

@section('content')
    @php
        $statusOptions = [
            '' => 'Semua Status',
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
                    Riwayat Pembayaran
                </h2>

                <p class="text-sm sm:text-base text-slate-500 mt-1 leading-relaxed">
                    Lihat riwayat pembayaran SPP yang sudah dikirim beserta status verifikasi dari admin.
                </p>
            </div>

            <a
                href="{{ route('orang-tua.pembayaran-spp.index') }}"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#004199] text-white rounded-2xl text-sm sm:text-base font-semibold hover:bg-[#00377F] transition-colors shadow-[0_8px_20px_rgba(0,65,153,0.22)]"
            >
                <span class="material-symbols-outlined text-[20px]">
                    payments
                </span>
                Bayar SPP
            </a>
        </div>

        {{-- Privacy Notice --}}
        <div class="bg-[#D9E2FF] border border-[#B0C6FF] rounded-[28px] p-5 shadow-[0_10px_28px_rgba(37,99,235,0.08)] flex items-start gap-3">
            <span class="material-symbols-outlined text-[#004199] mt-0.5 shrink-0" style="font-variation-settings: 'FILL' 1;">
                shield
            </span>

            <p class="text-sm text-[#004199] leading-relaxed">
                <strong>Data Privasi:</strong>
                Data yang ditampilkan hanya riwayat pembayaran SPP anak Anda.
            </p>
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

        {{-- Total Nominal --}}
        <div class="bg-[#D9E2FF] border border-[#B0C6FF] rounded-[28px] p-5 sm:p-6 shadow-[0_10px_28px_rgba(37,99,235,0.08)]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#004199] text-white flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[26px]">
                            account_balance_wallet
                        </span>
                    </div>

                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-[#004199]">
                            Total Nominal Pada Halaman Ini
                        </h3>

                        <p class="text-sm text-[#004199] mt-1 leading-relaxed">
                            Jumlah mengikuti riwayat pembayaran yang sedang tampil pada halaman ini.
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
            <form id="riwayatFilterForm" method="GET" action="{{ route('orang-tua.riwayat-pembayaran.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-[240px_auto] gap-3 md:items-center">
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

                    {{-- Reset --}}
                    <a
                        href="{{ route('orang-tua.riwayat-pembayaran.index') }}"
                        class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-slate-100 text-slate-700 rounded-2xl text-sm sm:text-base font-semibold hover:bg-slate-200 transition-colors whitespace-nowrap"
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
                    $statusLabel = $statusOptions[$pembayaran->status_verifikasi] ?? '-';
                    $statusClass = $statusBadgeClasses[$pembayaran->status_verifikasi] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                @endphp

                <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
                    <div class="bg-[#001847] px-5 py-4 text-white flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-xs uppercase tracking-wider text-white/70 mb-1">
                                Tanggal Upload
                            </p>

                            <h3 class="text-lg font-bold leading-tight">
                                {{ $pembayaran->tanggal_upload ? date('d-m-Y H:i', strtotime($pembayaran->tanggal_upload)) : '-' }}
                            </h3>
                        </div>

                        <span class="shrink-0 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <div class="p-5 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
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
                                    Jumlah Bulan
                                </p>

                                <p class="text-sm font-semibold text-slate-700">
                                    {{ $pembayaran->jumlah_bulan }} Bulan
                                </p>
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

                        <a
                            href="{{ asset('storage/' . $pembayaran->bukti_bayar) }}"
                            target="_blank"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl bg-[#D9E2FF] text-[#004199] text-sm font-semibold hover:bg-[#C6D6FF] transition-colors"
                        >
                            <span class="material-symbols-outlined text-[18px]">
                                image
                            </span>
                            Lihat Bukti Pembayaran
                        </a>
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
                        Belum ada riwayat pembayaran.
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Desktop Table --}}
        <div class="hidden lg:block bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
            <table class="w-full table-fixed text-left border-collapse">
                <colgroup>
                    <col class="w-[6%]">
                    <col class="w-[16%]">
                    <col class="w-[14%]">
                    <col class="w-[12%]">
                    <col class="w-[22%]">
                    <col class="w-[13%]">
                    <col class="w-[11%]">
                    <col class="w-[6%]">
                </colgroup>

                <thead>
                    <tr class="bg-[#001847] text-white text-sm">
                        <th class="py-4 px-3 font-semibold">No</th>
                        <th class="py-4 px-3 font-semibold">Tanggal Upload</th>
                        <th class="py-4 px-3 font-semibold">Total Bayar</th>
                        <th class="py-4 px-3 font-semibold">Jumlah Bulan</th>
                        <th class="py-4 px-3 font-semibold">Bulan Dibayar</th>
                        <th class="py-4 px-3 font-semibold text-center">Status</th>
                        <th class="py-4 px-3 font-semibold">Catatan</th>
                        <th class="py-4 px-3 font-semibold text-right">Bukti</th>
                    </tr>
                </thead>

                <tbody class="text-sm text-slate-800">
                    @forelse ($pembayarans as $pembayaran)
                        @php
                            $statusLabel = $statusOptions[$pembayaran->status_verifikasi] ?? '-';
                            $statusClass = $statusBadgeClasses[$pembayaran->status_verifikasi] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                        @endphp

                        <tr class="hover:bg-[#F8FAFC] transition-colors border-b border-slate-100">
                            <td class="py-4 px-3 font-semibold text-slate-500">
                                {{ $pembayarans->firstItem() + $loop->index }}
                            </td>

                            <td class="py-4 px-3 text-slate-500 break-words">
                                {{ $pembayaran->tanggal_upload ? date('d-m-Y H:i', strtotime($pembayaran->tanggal_upload)) : '-' }}
                            </td>

                            <td class="py-4 px-3 font-bold text-[#004199] break-words">
                                Rp {{ number_format($pembayaran->total_bayar, 0, ',', '.') }}
                            </td>

                            <td class="py-4 px-3 text-slate-500">
                                {{ $pembayaran->jumlah_bulan }} Bulan
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
                            </td>

                            <td class="py-4 px-3 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            <td class="py-4 px-3 text-slate-500 break-words">
                                {{ $pembayaran->catatan_admin ?? '-' }}
                            </td>

                            <td class="py-4 px-3 text-right">
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 px-6 text-center text-slate-500">
                                Belum ada riwayat pembayaran.
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

    <script>
        const filterForm = document.getElementById('riwayatFilterForm');

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
    </script>
@endsection