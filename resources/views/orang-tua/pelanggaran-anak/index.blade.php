@extends('layouts.orang-tua')

@section('title', 'Pelanggaran Anak - SIPontren')
@section('page-title', 'Pelanggaran Anak')

@section('content')
    @php
        $tingkatOptions = [
            '' => 'Semua Tingkat',
            'ringan' => 'Ringan',
            'sedang' => 'Sedang',
            'berat' => 'Berat',
        ];

        $tingkatBadgeClasses = [
            'ringan' => 'bg-[#D9E2FF] text-[#004199] border-[#B0C6FF]',
            'sedang' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
            'berat' => 'bg-red-100 text-red-700 border-red-200',
        ];

        $selectedTingkat = request('tingkat_pelanggaran', '');
        $selectedTingkatLabel = $tingkatOptions[$selectedTingkat] ?? 'Semua Tingkat';
    @endphp

    <div class="space-y-5 sm:space-y-6">
        {{-- Header --}}
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-[#191C1E] leading-tight">
                Pelanggaran Anak
            </h2>

            <p class="text-sm sm:text-base text-slate-500 mt-1 leading-relaxed">
                Pantau riwayat pelanggaran anak berdasarkan bulan dan tingkat pelanggaran.
            </p>
        </div>

        {{-- Privacy Notice --}}
        <div class="bg-[#D9E2FF] border border-[#B0C6FF] rounded-[28px] p-5 shadow-[0_10px_28px_rgba(37,99,235,0.08)] flex items-start gap-3">
            <span class="material-symbols-outlined text-[#004199] mt-0.5 shrink-0" style="font-variation-settings: 'FILL' 1;">
                shield
            </span>

            <p class="text-sm text-[#004199] leading-relaxed">
                <strong>Data Privasi:</strong>
                Data yang ditampilkan hanya data pelanggaran anak Anda.
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
                            Asrama
                        </p>

                        <p class="text-sm font-semibold text-slate-700">
                            {{ $santri->asrama ?? '-' }}
                        </p>
                    </div>

                    <div class="bg-[#F8FAFC] rounded-2xl border border-slate-100 p-4">
                        <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                            Kamar
                        </p>

                        <p class="text-sm font-semibold text-slate-700">
                            {{ $santri->kamar ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ringkasan --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-5">
                <p class="text-xs sm:text-sm font-semibold text-slate-500">
                    Total Pelanggaran
                </p>

                <p class="text-3xl font-bold text-[#191C1E] mt-3">
                    {{ number_format($totalPelanggaran) }}
                </p>
            </div>

            <div class="bg-[#D9E2FF] rounded-[28px] border border-[#B0C6FF] shadow-[0_10px_28px_rgba(37,99,235,0.08)] p-5">
                <p class="text-xs sm:text-sm font-semibold text-[#004199]">
                    Ringan
                </p>

                <p class="text-3xl font-bold text-[#004199] mt-3">
                    {{ number_format($totalRingan) }}
                </p>
            </div>

            <div class="bg-yellow-50 rounded-[28px] border border-yellow-200 shadow-[0_10px_28px_rgba(234,179,8,0.08)] p-5">
                <p class="text-xs sm:text-sm font-semibold text-yellow-700">
                    Sedang
                </p>

                <p class="text-3xl font-bold text-yellow-700 mt-3">
                    {{ number_format($totalSedang) }}
                </p>
            </div>

            <div class="bg-red-50 rounded-[28px] border border-red-200 shadow-[0_10px_28px_rgba(239,68,68,0.10)] p-5">
                <p class="text-xs sm:text-sm font-semibold text-red-700">
                    Berat
                </p>

                <p class="text-3xl font-bold text-red-600 mt-3">
                    {{ number_format($totalBerat) }}
                </p>
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-5 sm:p-6 overflow-visible">
            <form id="pelanggaranFilterForm" method="GET" action="{{ route('orang-tua.pelanggaran-anak.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-[220px_1fr_auto] gap-3 md:items-center">
                    {{-- Bulan --}}
                    <div>
                        <x-sipontren-date-picker
    name="bulan"
    mode="month"
    :value="request('bulan')"
    placeholder="Pilih Bulan"
/>
                    </div>

                    {{-- Tingkat --}}
                    <div class="relative" id="tingkatDropdownWrapper">
                        <input
                            type="hidden"
                            name="tingkat_pelanggaran"
                            id="tingkat_pelanggaran"
                            value="{{ $selectedTingkat }}"
                        >

                        <button
                            type="button"
                            id="tingkatDropdownButton"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] flex items-center justify-between gap-3 text-left"
                        >
                            <span id="tingkatDropdownLabel" class="truncate">
                                {{ $selectedTingkatLabel }}
                            </span>

                            <span id="tingkatDropdownIcon" class="material-symbols-outlined text-slate-400 transition-transform duration-200 shrink-0">
                                expand_more
                            </span>
                        </button>

                        <div
                            id="tingkatDropdownMenu"
                            class="hidden absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-[0_16px_40px_rgba(15,23,42,0.14)] overflow-hidden z-50"
                        >
                            @foreach ($tingkatOptions as $value => $label)
                                <button
                                    type="button"
                                    class="js-tingkat-option w-full px-4 py-3 text-left text-sm sm:text-base transition-colors flex items-center justify-between gap-3 {{ $selectedTingkat === $value ? 'bg-[#D9E2FF] text-[#004199] font-semibold' : 'text-slate-700 hover:bg-[#F8FAFC]' }}"
                                    data-value="{{ $value }}"
                                    data-label="{{ $label }}"
                                >
                                    <span>{{ $label }}</span>

                                    @if ($selectedTingkat === $value)
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
                        href="{{ route('orang-tua.pelanggaran-anak.index') }}"
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
            @forelse ($pelanggarans as $pelanggaran)
                @php
                    $tingkatLabel = $tingkatOptions[$pelanggaran->tingkat_pelanggaran] ?? ucfirst($pelanggaran->tingkat_pelanggaran);
                    $tingkatClass = $tingkatBadgeClasses[$pelanggaran->tingkat_pelanggaran] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                @endphp

                <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
                    <div class="bg-[#001847] px-5 py-4 text-white flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-xs uppercase tracking-wider text-white/70 mb-1">
                                Tanggal Pelanggaran
                            </p>

                            <h3 class="text-lg font-bold leading-tight">
                                {{ $pelanggaran->tanggal ? date('d-m-Y', strtotime($pelanggaran->tanggal)) : '-' }}
                            </h3>
                        </div>

                        <span class="shrink-0 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $tingkatClass }}">
                            {{ $tingkatLabel }}
                        </span>
                    </div>

                    <div class="p-5 space-y-4">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                Jenis Pelanggaran
                            </p>

                            <p class="text-sm font-semibold text-[#001847] leading-relaxed">
                                {{ $pelanggaran->jenis_pelanggaran }}
                            </p>
                        </div>

                        <div class="border-t border-slate-100 pt-4">
                            <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                Catatan Pengurus
                            </p>

                            <p class="text-sm text-slate-600 leading-relaxed">
                                {{ $pelanggaran->catatan ?? '-' }}
                            </p>
                        </div>

                        <div class="border-t border-slate-100 pt-4">
                            <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                Tindak Lanjut
                            </p>

                            <p class="text-sm text-slate-600 leading-relaxed">
                                {{ $pelanggaran->tindak_lanjut ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-6 text-center">
                    <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3 text-slate-400">
                        <span class="material-symbols-outlined">
                            gavel
                        </span>
                    </div>

                    <p class="text-sm text-slate-500">
                        Belum ada data pelanggaran untuk anak Anda.
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Desktop Table --}}
        <div class="hidden lg:block bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
            <table class="w-full table-fixed text-left border-collapse">
                <colgroup>
                    <col class="w-[7%]">
                    <col class="w-[14%]">
                    <col class="w-[25%]">
                    <col class="w-[12%]">
                    <col class="w-[21%]">
                    <col class="w-[21%]">
                </colgroup>

                <thead>
                    <tr class="bg-[#001847] text-white text-sm">
                        <th class="py-4 px-4 font-semibold">No</th>
                        <th class="py-4 px-4 font-semibold">Tanggal</th>
                        <th class="py-4 px-4 font-semibold">Jenis Pelanggaran</th>
                        <th class="py-4 px-4 font-semibold text-center">Tingkat</th>
                        <th class="py-4 px-4 font-semibold">Catatan Pengurus</th>
                        <th class="py-4 px-4 font-semibold">Tindak Lanjut</th>
                    </tr>
                </thead>

                <tbody class="text-sm text-slate-800">
                    @forelse ($pelanggarans as $pelanggaran)
                        @php
                            $tingkatLabel = $tingkatOptions[$pelanggaran->tingkat_pelanggaran] ?? ucfirst($pelanggaran->tingkat_pelanggaran);
                            $tingkatClass = $tingkatBadgeClasses[$pelanggaran->tingkat_pelanggaran] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                        @endphp

                        <tr class="hover:bg-[#F8FAFC] transition-colors border-b border-slate-100">
                            <td class="py-4 px-4 font-semibold text-slate-500">
                                {{ $pelanggarans->firstItem() + $loop->index }}
                            </td>

                            <td class="py-4 px-4 text-slate-500 break-words">
                                {{ $pelanggaran->tanggal ? date('d-m-Y', strtotime($pelanggaran->tanggal)) : '-' }}
                            </td>

                            <td class="py-4 px-4 font-semibold text-[#001847] break-words">
                                {{ $pelanggaran->jenis_pelanggaran }}
                            </td>

                            <td class="py-4 px-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $tingkatClass }}">
                                    {{ $tingkatLabel }}
                                </span>
                            </td>

                            <td class="py-4 px-4 text-slate-500 break-words">
                                {{ $pelanggaran->catatan ?? '-' }}
                            </td>

                            <td class="py-4 px-4 text-slate-500 break-words">
                                {{ $pelanggaran->tindak_lanjut ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 px-6 text-center text-slate-500">
                                Belum ada data pelanggaran untuk anak Anda.
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
                    <span class="font-semibold text-slate-700">{{ $pelanggarans->firstItem() ?? 0 }}</span>
                    -
                    <span class="font-semibold text-slate-700">{{ $pelanggarans->lastItem() ?? 0 }}</span>
                    dari
                    <span class="font-semibold text-slate-700">{{ $pelanggarans->total() }}</span>
                    pelanggaran
                </div>

                <div class="flex justify-center lg:justify-end">
                    {{ $pelanggarans->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        const filterForm = document.getElementById('pelanggaranFilterForm');
        const bulanInput = document.getElementById('bulanInput');

        if (bulanInput && filterForm) {
            bulanInput.addEventListener('change', function () {
                filterForm.submit();
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
            wrapperId: 'tingkatDropdownWrapper',
            buttonId: 'tingkatDropdownButton',
            menuId: 'tingkatDropdownMenu',
            iconId: 'tingkatDropdownIcon',
            inputId: 'tingkat_pelanggaran',
            labelId: 'tingkatDropdownLabel',
            optionSelector: '.js-tingkat-option',
        });
    </script>
@endsection