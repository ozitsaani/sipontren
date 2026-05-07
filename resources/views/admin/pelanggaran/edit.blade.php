@extends('layouts.admin')

@section('title', 'Edit Pelanggaran - SIPontren')
@section('page-title', 'Edit Pelanggaran')

@section('content')
    @php
        $selectedSantriId = old('santri_id', $pelanggaran->santri_id);
        $selectedSantri = $santris->firstWhere('id', $selectedSantriId);

        $tanggalValue = old(
            'tanggal',
            $pelanggaran->tanggal
                ? \Carbon\Carbon::parse($pelanggaran->tanggal)->format('Y-m-d')
                : date('Y-m-d')
        );

        $tingkatOptions = [
            '' => 'Pilih Tingkat',
            'ringan' => 'Ringan',
            'sedang' => 'Sedang',
            'berat' => 'Berat',
        ];

        $selectedTingkat = old('tingkat_pelanggaran', $pelanggaran->tingkat_pelanggaran);
        $selectedTingkatLabel = $tingkatOptions[$selectedTingkat] ?? 'Pilih Tingkat';
    @endphp

    <div class="space-y-5 sm:space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-[#191C1E] leading-tight">
                    Edit Pelanggaran Santri
                </h2>

                <p class="text-sm sm:text-base text-slate-500 mt-1 leading-relaxed">
                    Perbarui data pelanggaran santri, tingkat pelanggaran, catatan, dan tindak lanjut pembinaan.
                </p>
            </div>

            <a
                href="{{ route('admin.pelanggaran.index') }}"
                class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-white border border-slate-200 text-slate-700 rounded-2xl text-sm font-semibold hover:bg-slate-50 transition-colors shadow-[0_10px_28px_rgba(15,23,42,0.07)]"
            >
                <span class="material-symbols-outlined text-[18px]">
                    arrow_back
                </span>
                Kembali
            </a>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-visible">
            <div class="bg-[#001847] px-5 sm:px-6 py-4 text-white rounded-t-[28px]">
                <h3 class="text-base sm:text-xl font-semibold flex items-center gap-2">
                    <span class="material-symbols-outlined text-white text-[20px]">
                        edit
                    </span>
                    Form Edit Pelanggaran
                </h3>
            </div>

            <form action="{{ route('admin.pelanggaran.update', $pelanggaran) }}" method="POST" class="p-5 sm:p-6 space-y-6">
                @csrf
                @method('PUT')

                {{-- Info Pelanggaran --}}
                <div class="bg-[#F8FAFC] border border-slate-200 rounded-3xl p-5 flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-[#001847] text-white flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[28px]">
                            gavel
                        </span>
                    </div>

                    <div class="min-w-0">
                        <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                            Sedang diedit
                        </p>

                        <h4 class="text-lg font-bold text-[#001847] leading-tight">
                            {{ $pelanggaran->santri->nama_santri ?? '-' }}
                        </h4>

                        <p class="text-sm text-slate-500 mt-1">
                            {{ $pelanggaran->jenis_pelanggaran }}
                        </p>
                    </div>

                    <div class="sm:ml-auto">
                        @if ($selectedTingkat === 'ringan')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#D9E2FF] text-[#004199] border border-[#B0C6FF]">
                                Ringan
                            </span>
                        @elseif ($selectedTingkat === 'sedang')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 border border-yellow-200">
                                Sedang
                            </span>
                        @elseif ($selectedTingkat === 'berat')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">
                                Berat
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                -
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Input Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Santri --}}
                    <div class="md:col-span-2">
                        <label for="santri_id" class="block text-sm font-semibold text-slate-700 mb-2">
                            Santri
                        </label>

                        <div class="relative" id="santriDropdownWrapper">
                            <input
                                type="hidden"
                                name="santri_id"
                                id="santri_id"
                                value="{{ $selectedSantriId }}"
                            >

                            <button
                                type="button"
                                id="santriDropdownButton"
                                class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] flex items-center justify-between gap-3 text-left @error('santri_id') border-red-400 @enderror"
                            >
                                <span id="santriDropdownLabel" class="{{ $selectedSantri ? 'text-slate-800' : 'text-slate-400' }} truncate">
                                    {{ $selectedSantri ? $selectedSantri->nama_santri . ' - ' . ($selectedSantri->kelasMadrasah->nama_kelas ?? '-') : 'Pilih Santri' }}
                                </span>

                                <span id="santriDropdownIcon" class="material-symbols-outlined text-slate-400 transition-transform duration-200 shrink-0">
                                    expand_more
                                </span>
                            </button>

                            <div
                                id="santriDropdownMenu"
                                class="hidden absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-[0_16px_40px_rgba(15,23,42,0.14)] overflow-hidden z-50"
                            >
                                <div class="p-3 border-b border-slate-100">
                                    <div class="relative">
                                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">
                                            search
                                        </span>

                                        <input
                                            type="text"
                                            id="santriSearchInput"
                                            placeholder="Cari nama santri, NIS, atau kelas..."
                                            autocomplete="off"
                                            class="w-full pl-10 pr-4 py-2.5 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] placeholder:text-slate-400"
                                        >
                                    </div>
                                </div>

                                <div class="max-h-[320px] overflow-y-auto">
                                    <button
                                        type="button"
                                        class="js-santri-option w-full px-4 py-3 text-left text-sm sm:text-base transition-colors flex items-center justify-between gap-3 {{ !$selectedSantriId ? 'bg-[#D9E2FF] text-[#004199] font-semibold' : 'text-slate-500 hover:bg-[#F8FAFC]' }}"
                                        data-value=""
                                        data-label="Pilih Santri"
                                        data-placeholder="true"
                                        data-search="pilih santri"
                                    >
                                        <span>Pilih Santri</span>

                                        @if (!$selectedSantriId)
                                            <span class="material-symbols-outlined text-[18px] dropdown-check-icon">
                                                check
                                            </span>
                                        @endif
                                    </button>

                                    @foreach ($santris as $santri)
                                        @php
                                            $label = $santri->nama_santri . ' - ' . ($santri->kelasMadrasah->nama_kelas ?? '-');
                                            $isSelected = (string) $selectedSantriId === (string) $santri->id;
                                        @endphp

                                        <button
                                            type="button"
                                            class="js-santri-option w-full px-4 py-3 text-left text-sm sm:text-base transition-colors flex items-start justify-between gap-3 {{ $isSelected ? 'bg-[#D9E2FF] text-[#004199] font-semibold' : 'text-slate-700 hover:bg-[#F8FAFC]' }}"
                                            data-value="{{ $santri->id }}"
                                            data-label="{{ $label }}"
                                            data-placeholder="false"
                                            data-search="{{ strtolower($santri->nama_santri . ' ' . ($santri->nis ?? '') . ' ' . ($santri->kelasMadrasah->nama_kelas ?? '')) }}"
                                        >
                                            <span class="min-w-0">
                                                <span class="block font-semibold truncate">
                                                    {{ $santri->nama_santri }}
                                                </span>

                                                <span class="block text-xs text-slate-500 mt-1">
                                                    NIS: {{ $santri->nis ?? '-' }} · {{ $santri->kelasMadrasah->nama_kelas ?? '-' }}
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
                        </div>

                        @error('santri_id')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <label for="tanggal" class="block text-sm font-semibold text-slate-700 mb-2">
                            Tanggal Pelanggaran
                        </label>

                        <x-sipontren-date-picker
    name="tanggal"
    mode="date"
    :value="old('tanggal', date('Y-m-d'))"
    placeholder="Pilih Tanggal"
    :auto-submit="false"
/>

                        @error('tanggal')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tingkat --}}
                    <div>
                        <label for="tingkat_pelanggaran" class="block text-sm font-semibold text-slate-700 mb-2">
                            Tingkat Pelanggaran
                        </label>

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
                                class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] flex items-center justify-between gap-3 text-left @error('tingkat_pelanggaran') border-red-400 @enderror"
                            >
                                <span id="tingkatDropdownLabel" class="{{ $selectedTingkat ? 'text-slate-800' : 'text-slate-400' }}">
                                    {{ $selectedTingkatLabel }}
                                </span>

                                <span id="tingkatDropdownIcon" class="material-symbols-outlined text-slate-400 transition-transform duration-200">
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
                                        data-placeholder="{{ $value === '' ? 'true' : 'false' }}"
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

                        @error('tingkat_pelanggaran')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jenis Pelanggaran --}}
                    <div class="md:col-span-2">
                        <label for="jenis_pelanggaran" class="block text-sm font-semibold text-slate-700 mb-2">
                            Jenis Pelanggaran
                        </label>

                        <input
                            id="jenis_pelanggaran"
                            type="text"
                            name="jenis_pelanggaran"
                            value="{{ old('jenis_pelanggaran', $pelanggaran->jenis_pelanggaran) }}"
                            placeholder="Contoh: Terlambat mengikuti pengajian"
                            required
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] placeholder:text-slate-400 @error('jenis_pelanggaran') border-red-400 focus:border-red-500 focus:ring-red-500 @enderror"
                        >

                        @error('jenis_pelanggaran')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <label for="catatan" class="block text-sm font-semibold text-slate-700 mb-2">
                            Catatan
                        </label>

                        <textarea
                            id="catatan"
                            name="catatan"
                            rows="5"
                            placeholder="Catatan tambahan"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] placeholder:text-slate-400 resize-none @error('catatan') border-red-400 focus:border-red-500 focus:ring-red-500 @enderror"
                        >{{ old('catatan', $pelanggaran->catatan) }}</textarea>

                        @error('catatan')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tindak Lanjut --}}
                    <div>
                        <label for="tindak_lanjut" class="block text-sm font-semibold text-slate-700 mb-2">
                            Tindak Lanjut
                        </label>

                        <textarea
                            id="tindak_lanjut"
                            name="tindak_lanjut"
                            rows="5"
                            placeholder="Contoh: Teguran lisan / pembinaan / pemanggilan wali"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] placeholder:text-slate-400 resize-none @error('tindak_lanjut') border-red-400 focus:border-red-500 focus:ring-red-500 @enderror"
                        >{{ old('tindak_lanjut', $pelanggaran->tindak_lanjut) }}</textarea>

                        @error('tindak_lanjut')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Contoh Jenis Pelanggaran --}}
                <div class="bg-[#F8FAFC] border border-slate-200 rounded-3xl p-5">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#001847] text-white flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[26px]">
                                checklist
                            </span>
                        </div>

                        <div class="flex-1">
                            <h4 class="text-base font-bold text-[#001847]">
                                Contoh Jenis Pelanggaran
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-4">
                                @foreach ([
                                    'Terlambat mengikuti pengajian',
                                    'Tidak mengikuti kegiatan',
                                    'Keluar area pesantren tanpa izin',
                                    'Tidak menjaga kebersihan',
                                    'Membawa barang yang dilarang',
                                    'Tidak mematuhi aturan asrama',
                                ] as $contoh)
                                    <button
                                        type="button"
                                        onclick="fillJenisPelanggaran(@js($contoh))"
                                        class="text-left px-4 py-3 rounded-2xl bg-white border border-slate-200 text-sm font-semibold text-slate-700 hover:border-[#004199] hover:text-[#004199] transition-colors"
                                    >
                                        {{ $contoh }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-4 border-t border-slate-100">
                    <a
                        href="{{ route('admin.pelanggaran.index') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-slate-100 text-slate-700 rounded-2xl text-sm sm:text-base font-semibold hover:bg-slate-200 transition-colors"
                    >
                        <span class="material-symbols-outlined text-[18px]">
                            close
                        </span>
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#004199] text-white rounded-2xl text-sm sm:text-base font-semibold hover:bg-[#00377F] transition-colors shadow-[0_8px_20px_rgba(0,65,153,0.22)]"
                    >
                        <span class="material-symbols-outlined text-[18px]">
                            save
                        </span>
                        Update Pelanggaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
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
            const isPlaceholder = option.dataset.placeholder === 'true';

            option.classList.remove('text-slate-700', 'hover:bg-[#F8FAFC]');
            option.classList.add('bg-[#D9E2FF]', 'text-[#004199]', 'font-semibold');

            const checkIcon = document.createElement('span');
            checkIcon.className = 'material-symbols-outlined text-[18px] dropdown-check-icon shrink-0';
            checkIcon.textContent = 'check';

            option.appendChild(checkIcon);

            if (isPlaceholder) {
                option.classList.add('text-[#004199]');
            }
        }

        function closeAllDropdowns(exceptMenuId = null) {
            const dropdowns = [
                {
                    menu: 'santriDropdownMenu',
                    icon: 'santriDropdownIcon',
                },
                {
                    menu: 'tingkatDropdownMenu',
                    icon: 'tingkatDropdownIcon',
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
            wrapperId: 'santriDropdownWrapper',
            buttonId: 'santriDropdownButton',
            menuId: 'santriDropdownMenu',
            iconId: 'santriDropdownIcon',
            inputId: 'santri_id',
            labelId: 'santriDropdownLabel',
            optionSelector: '.js-santri-option',
        });

        setupDropdown({
            wrapperId: 'tingkatDropdownWrapper',
            buttonId: 'tingkatDropdownButton',
            menuId: 'tingkatDropdownMenu',
            iconId: 'tingkatDropdownIcon',
            inputId: 'tingkat_pelanggaran',
            labelId: 'tingkatDropdownLabel',
            optionSelector: '.js-tingkat-option',
        });

        const santriSearchInput = document.getElementById('santriSearchInput');
        const santriOptions = document.querySelectorAll('.js-santri-option');

        if (santriSearchInput) {
            santriSearchInput.addEventListener('input', function () {
                const keyword = this.value.toLowerCase();

                santriOptions.forEach(function (option) {
                    const searchableText = option.dataset.search || '';

                    if (searchableText.includes(keyword)) {
                        option.classList.remove('hidden');
                    } else {
                        option.classList.add('hidden');
                    }
                });
            });
        }

        function fillJenisPelanggaran(value) {
            const input = document.getElementById('jenis_pelanggaran');

            if (input) {
                input.value = value;
                input.focus();
            }
        }
    </script>
@endsection