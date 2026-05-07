@extends('layouts.admin')

@section('title', 'Rekap Absensi - SIPontren')
@section('page-title', 'Rekap Absensi')

@section('content')
    @php
        $waktuOptions = [
            '' => 'Semua Waktu',
            'bada_shubuh' => "Ba'da Shubuh",
            'bada_dzuhur' => "Ba'da Dzuhur",
            'bada_ashar' => "Ba'da Ashar",
            'bada_maghrib' => "Ba'da Maghrib",
            'bada_isya' => "Ba'da Isya",
            'pengajian_malam' => 'Pengajian Malam',
        ];

        $statusOptions = [
            '' => 'Semua Status',
            'hadir' => 'Hadir',
            'izin' => 'Izin',
            'sakit' => 'Sakit',
            'alfa' => 'Alfa',
        ];

        $statusBadgeClasses = [
            'hadir' => 'bg-[#DFF5A6] text-[#4A5F00] border-[#CDEB7C]',
            'izin' => 'bg-[#D9E2FF] text-[#004199] border-[#B0C6FF]',
            'sakit' => 'bg-slate-100 text-slate-600 border-slate-200',
            'alfa' => 'bg-red-100 text-red-700 border-red-200',
        ];

        $selectedKelasId = request('kelas_madrasah_id', '');
        $selectedKelas = $kelasMadrasahs->firstWhere('id', $selectedKelasId);
        $selectedKelasLabel = $selectedKelas ? $selectedKelas->nama_kelas : 'Semua Kelas';

        $selectedWaktu = request('waktu_pengajian', '');
        $selectedWaktuLabel = $waktuOptions[$selectedWaktu] ?? 'Semua Waktu';

        $selectedStatus = request('status', '');
        $selectedStatusLabel = $statusOptions[$selectedStatus] ?? 'Semua Status';

        $totalAbsensi = $totalHadir + $totalIzin + $totalSakit + $totalAlfa;
        $persenHadir = $totalAbsensi > 0 ? round(($totalHadir / $totalAbsensi) * 100) : 0;
    @endphp

    <div class="space-y-5 sm:space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-[#191C1E] leading-tight">
                    Rekap Absensi Admin
                </h2>

                <p class="text-sm sm:text-base text-slate-500 mt-1 leading-relaxed">
                    Pantau data absensi santri berdasarkan bulan, kelas, waktu pengajian, status, dan pencarian.
                </p>
            </div>

            <a
                href="{{ route('admin.absensi.index') }}"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#004199] text-white rounded-2xl text-sm sm:text-base font-semibold hover:bg-[#00377F] transition-colors shadow-[0_8px_20px_rgba(0,65,153,0.22)]"
            >
                <span class="material-symbols-outlined text-[20px]">
                    edit_calendar
                </span>
                Input Absensi
            </a>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-5 sm:p-6 overflow-visible">
            <form id="rekapFilterForm" method="GET" action="{{ route('admin.rekap-absensi.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-[170px_220px_220px_180px_minmax(0,1fr)_auto] gap-3 xl:items-center">
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

                    {{-- Waktu Pengajian --}}
                    <div class="relative" id="waktuDropdownWrapper">
                        <input
                            type="hidden"
                            name="waktu_pengajian"
                            id="waktu_pengajian"
                            value="{{ $selectedWaktu }}"
                        >

                        <button
                            type="button"
                            id="waktuDropdownButton"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] flex items-center justify-between gap-3 text-left"
                        >
                            <span id="waktuDropdownLabel" class="truncate">
                                {{ $selectedWaktuLabel }}
                            </span>

                            <span id="waktuDropdownIcon" class="material-symbols-outlined text-slate-400 transition-transform duration-200 shrink-0">
                                expand_more
                            </span>
                        </button>

                        <div
                            id="waktuDropdownMenu"
                            class="hidden absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-[0_16px_40px_rgba(15,23,42,0.14)] overflow-hidden z-50"
                        >
                            @foreach ($waktuOptions as $value => $label)
                                <button
                                    type="button"
                                    class="js-waktu-option w-full px-4 py-3 text-left text-sm sm:text-base transition-colors flex items-center justify-between gap-3 {{ $selectedWaktu === $value ? 'bg-[#D9E2FF] text-[#004199] font-semibold' : 'text-slate-700 hover:bg-[#F8FAFC]' }}"
                                    data-value="{{ $value }}"
                                    data-label="{{ $label }}"
                                >
                                    <span>{{ $label }}</span>

                                    @if ($selectedWaktu === $value)
                                        <span class="material-symbols-outlined text-[18px] dropdown-check-icon">
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
                                href="{{ route('admin.rekap-absensi.index', request()->except('search')) }}"
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
                        href="{{ route('admin.rekap-absensi.index') }}"
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

        {{-- Ringkasan --}}
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-6">
            <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-5">
                <p class="text-xs sm:text-sm font-semibold text-slate-500">
                    Total
                </p>

                <p class="text-3xl font-bold text-[#191C1E] mt-3">
                    {{ $totalAbsensi }}
                </p>
            </div>

            <div class="bg-[#EEF8D8] rounded-[28px] border border-[#DCE8BE] shadow-[0_10px_28px_rgba(74,95,0,0.08)] p-5">
                <p class="text-xs sm:text-sm font-semibold text-[#4A5F00]">
                    Hadir
                </p>

                <p class="text-3xl font-bold text-[#4A5F00] mt-3">
                    {{ $totalHadir }}
                </p>
            </div>

            <div class="bg-[#D9E2FF] rounded-[28px] border border-[#B0C6FF] shadow-[0_10px_28px_rgba(37,99,235,0.08)] p-5">
                <p class="text-xs sm:text-sm font-semibold text-[#004199]">
                    Izin
                </p>

                <p class="text-3xl font-bold text-[#004199] mt-3">
                    {{ $totalIzin }}
                </p>
            </div>

            <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-5">
                <p class="text-xs sm:text-sm font-semibold text-slate-500">
                    Sakit
                </p>

                <p class="text-3xl font-bold text-slate-700 mt-3">
                    {{ $totalSakit }}
                </p>
            </div>

            <div class="bg-red-50 rounded-[28px] border border-red-200 shadow-[0_10px_28px_rgba(239,68,68,0.10)] p-5 col-span-2 lg:col-span-1">
                <p class="text-xs sm:text-sm font-semibold text-red-700">
                    Alfa
                </p>

                <p class="text-3xl font-bold text-red-600 mt-3">
                    {{ $totalAlfa }}
                </p>
            </div>
        </div>

        {{-- Persentase Hadir --}}
        <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-5 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-base sm:text-xl font-bold text-[#001847]">
                        Persentase Kehadiran
                    </h3>

                    <p class="text-sm text-slate-500 mt-1">
                        Berdasarkan hasil filter yang sedang aktif.
                    </p>
                </div>

                <div class="text-4xl font-bold text-[#4A5F00]">
                    {{ $persenHadir }}%
                </div>
            </div>

            <div class="mt-5 h-3 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-[#4A5F00] rounded-full" style="width: {{ $persenHadir }}%"></div>
            </div>
        </div>

        {{-- Mobile / Tablet Card List --}}
        <div class="lg:hidden space-y-4">
            @forelse ($absensis as $absensi)
                @php
                    $statusClass = $statusBadgeClasses[$absensi->status] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                    $waktuLabel = $waktuOptions[$absensi->waktu_pengajian] ?? '-';
                @endphp

                <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
                    <div class="bg-[#001847] px-5 py-4 text-white flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-xs uppercase tracking-wider text-white/70 mb-1">
                                Nama Santri
                            </p>

                            <h3 class="text-lg font-bold leading-tight">
                                {{ $absensi->santri->nama_santri ?? '-' }}
                            </h3>

                            <p class="text-sm text-white/70 mt-1">
                                {{ $absensi->santri->kelasMadrasah->nama_kelas ?? '-' }}
                            </p>
                        </div>

                        <span class="shrink-0 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                            {{ ucfirst($absensi->status) }}
                        </span>
                    </div>

                    <div class="p-5 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                    Tanggal
                                </p>

                                <p class="text-sm font-semibold text-slate-700">
                                    {{ date('d-m-Y', strtotime($absensi->tanggal)) }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                    NIS
                                </p>

                                <p class="text-sm font-semibold text-[#004199]">
                                    {{ $absensi->santri->nis ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-4">
                            <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                Waktu Pengajian
                            </p>

                            <p class="text-sm font-semibold text-[#004199]">
                                {{ $waktuLabel }}
                            </p>
                        </div>

                        <div class="border-t border-slate-100 pt-4">
                            <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                Catatan
                            </p>

                            <p class="text-sm text-slate-600 leading-relaxed">
                                {{ $absensi->catatan ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-6 text-center">
                    <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3 text-slate-400">
                        <span class="material-symbols-outlined">
                            event_busy
                        </span>
                    </div>

                    <p class="text-sm text-slate-500">
                        Belum ada data absensi.
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Desktop Table --}}
        <div class="hidden lg:block bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
            <table class="w-full table-fixed text-left border-collapse">
                <colgroup>
                    <col class="w-[6%]">
                    <col class="w-[12%]">
                    <col class="w-[17%]">
                    <col class="w-[10%]">
                    <col class="w-[18%]">
                    <col class="w-[15%]">
                    <col class="w-[10%]">
                    <col class="w-[12%]">
                </colgroup>

                <thead>
                    <tr class="bg-[#001847] text-white text-sm">
                        <th class="py-4 px-4 font-semibold">No</th>
                        <th class="py-4 px-4 font-semibold">Tanggal</th>
                        <th class="py-4 px-4 font-semibold">Waktu</th>
                        <th class="py-4 px-4 font-semibold">NIS</th>
                        <th class="py-4 px-4 font-semibold">Nama Santri</th>
                        <th class="py-4 px-4 font-semibold">Kelas</th>
                        <th class="py-4 px-4 font-semibold text-center">Status</th>
                        <th class="py-4 px-4 font-semibold">Catatan</th>
                    </tr>
                </thead>

                <tbody class="text-sm text-slate-800">
                    @forelse ($absensis as $absensi)
                        @php
                            $statusClass = $statusBadgeClasses[$absensi->status] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                            $waktuLabel = $waktuOptions[$absensi->waktu_pengajian] ?? '-';
                        @endphp

                        <tr class="hover:bg-[#F8FAFC] transition-colors border-b border-slate-100">
                            <td class="py-4 px-4 font-semibold text-slate-500">
                                {{ $absensis->firstItem() + $loop->index }}
                            </td>

                            <td class="py-4 px-4 font-semibold text-[#001847] break-words">
                                {{ date('d-m-Y', strtotime($absensi->tanggal)) }}
                            </td>

                            <td class="py-4 px-4 text-slate-500 break-words">
                                {{ $waktuLabel }}
                            </td>

                            <td class="py-4 px-4 text-[#004199] font-semibold break-words">
                                {{ $absensi->santri->nis ?? '-' }}
                            </td>

                            <td class="py-4 px-4 font-semibold text-slate-800 break-words">
                                {{ $absensi->santri->nama_santri ?? '-' }}
                            </td>

                            <td class="py-4 px-4 text-slate-500 break-words">
                                {{ $absensi->santri->kelasMadrasah->nama_kelas ?? '-' }}
                            </td>

                            <td class="py-4 px-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                                    {{ ucfirst($absensi->status) }}
                                </span>
                            </td>

                            <td class="py-4 px-4 text-slate-500 break-words">
                                {{ $absensi->catatan ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 px-6 text-center text-slate-500">
                                Belum ada data absensi.
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
                    <span class="font-semibold text-slate-700">{{ $absensis->firstItem() ?? 0 }}</span>
                    -
                    <span class="font-semibold text-slate-700">{{ $absensis->lastItem() ?? 0 }}</span>
                    dari
                    <span class="font-semibold text-slate-700">{{ $absensis->total() }}</span>
                    data absensi
                </div>

                <div class="flex justify-center lg:justify-end">
                    {{ $absensis->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        const filterForm = document.getElementById('rekapFilterForm');
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
                    menu: 'waktuDropdownMenu',
                    icon: 'waktuDropdownIcon',
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
            wrapperId: 'waktuDropdownWrapper',
            buttonId: 'waktuDropdownButton',
            menuId: 'waktuDropdownMenu',
            iconId: 'waktuDropdownIcon',
            inputId: 'waktu_pengajian',
            labelId: 'waktuDropdownLabel',
            optionSelector: '.js-waktu-option',
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
    </script>
@endsection