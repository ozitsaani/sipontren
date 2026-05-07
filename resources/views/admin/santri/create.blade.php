@extends('layouts.admin')

@section('title', 'Tambah Santri - SIPontren')
@section('page-title', 'Tambah Santri')

@section('content')
    @php
        $selectedKelasId = old('kelas_madrasah_id');
        $selectedKelas = $kelasMadrasahs->firstWhere('id', $selectedKelasId);

        $selectedStatus = old('status', 'aktif');
        $statusLabels = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
        ];

        $selectedStatusLabel = $statusLabels[$selectedStatus] ?? 'Aktif';
    @endphp

    <div class="space-y-5 sm:space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-[#191C1E] leading-tight">
                    Tambah Data Santri
                </h2>

                <p class="text-sm sm:text-base text-slate-500 mt-1 leading-relaxed">
                    Tambahkan data identitas santri, kelas madrasah, asrama, kamar, dan status santri.
                </p>
            </div>

            <a
                href="{{ route('admin.santri.index') }}"
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
                        person_add
                    </span>
                    Form Tambah Santri
                </h3>
            </div>

            <form action="{{ route('admin.santri.store') }}" method="POST" class="p-5 sm:p-6 space-y-6">
                @csrf

                {{-- Info Box --}}
                <div class="bg-[#F8FAFC] border border-slate-200 rounded-3xl p-5 flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#001847] text-white flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[26px]">
                            info
                        </span>
                    </div>

                    <div>
                        <h4 class="text-base font-bold text-[#001847]">
                            Informasi Data Santri
                        </h4>

                        <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                            Pastikan NIS, nama santri, kelas madrasah, asrama, dan kamar sudah sesuai sebelum data disimpan.
                        </p>
                    </div>
                </div>

                {{-- Input Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- NIS --}}
                    <div>
                        <label for="nis" class="block text-sm font-semibold text-slate-700 mb-2">
                            NIS
                        </label>

                        <input
                            id="nis"
                            type="text"
                            name="nis"
                            value="{{ old('nis') }}"
                            placeholder="Contoh: 202300129"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] placeholder:text-slate-400 @error('nis') border-red-400 focus:border-red-500 focus:ring-red-500 @enderror"
                        >

                        @error('nis')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama Santri --}}
                    <div>
                        <label for="nama_santri" class="block text-sm font-semibold text-slate-700 mb-2">
                            Nama Santri
                        </label>

                        <input
                            id="nama_santri"
                            type="text"
                            name="nama_santri"
                            value="{{ old('nama_santri') }}"
                            placeholder="Contoh: Ahmad Zaki Al-Farizi"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] placeholder:text-slate-400 @error('nama_santri') border-red-400 focus:border-red-500 focus:ring-red-500 @enderror"
                        >

                        @error('nama_santri')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kelas Madrasah Custom Dropdown --}}
                    <div>
                        <label for="kelas_madrasah_id" class="block text-sm font-semibold text-slate-700 mb-2">
                            Kelas Madrasah
                        </label>

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
                                class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] flex items-center justify-between gap-3 text-left @error('kelas_madrasah_id') border-red-400 @enderror"
                            >
                                <span id="kelasDropdownLabel" class="{{ $selectedKelas ? 'text-slate-800' : 'text-slate-400' }}">
                                    {{ $selectedKelas ? $selectedKelas->nama_kelas : 'Pilih Kelas Madrasah' }}
                                </span>

                                <span id="kelasDropdownIcon" class="material-symbols-outlined text-slate-400 transition-transform duration-200">
                                    expand_more
                                </span>
                            </button>

                            <div
                                id="kelasDropdownMenu"
                                class="hidden absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-[0_16px_40px_rgba(15,23,42,0.14)] overflow-hidden z-50"
                            >
                                <button
                                    type="button"
                                    class="js-kelas-option w-full px-4 py-3 text-left text-sm sm:text-base text-slate-500 hover:bg-[#F8FAFC] transition-colors"
                                    data-value=""
                                    data-label="Pilih Kelas Madrasah"
                                    data-placeholder="true"
                                >
                                    Pilih Kelas Madrasah
                                </button>

                                @foreach ($kelasMadrasahs as $kelas)
                                    <button
                                        type="button"
                                        class="js-kelas-option w-full px-4 py-3 text-left text-sm sm:text-base transition-colors flex items-center justify-between gap-3 {{ old('kelas_madrasah_id') == $kelas->id ? 'bg-[#D9E2FF] text-[#004199] font-semibold' : 'text-slate-700 hover:bg-[#F8FAFC]' }}"
                                        data-value="{{ $kelas->id }}"
                                        data-label="{{ $kelas->nama_kelas }}"
                                        data-placeholder="false"
                                    >
                                        <span>{{ $kelas->nama_kelas }}</span>

                                        @if (old('kelas_madrasah_id') == $kelas->id)
                                            <span class="material-symbols-outlined text-[18px]">
                                                check
                                            </span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        @error('kelas_madrasah_id')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status Custom Dropdown --}}
                    <div>
                        <label for="status" class="block text-sm font-semibold text-slate-700 mb-2">
                            Status Santri
                        </label>

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
                                class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] flex items-center justify-between gap-3 text-left @error('status') border-red-400 @enderror"
                            >
                                <span id="statusDropdownLabel" class="text-slate-800">
                                    {{ $selectedStatusLabel }}
                                </span>

                                <span id="statusDropdownIcon" class="material-symbols-outlined text-slate-400 transition-transform duration-200">
                                    expand_more
                                </span>
                            </button>

                            <div
                                id="statusDropdownMenu"
                                class="hidden absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-[0_16px_40px_rgba(15,23,42,0.14)] overflow-hidden z-50"
                            >
                                <button
                                    type="button"
                                    class="js-status-option w-full px-4 py-3 text-left text-sm sm:text-base transition-colors flex items-center justify-between gap-3 {{ $selectedStatus === 'aktif' ? 'bg-[#D9E2FF] text-[#004199] font-semibold' : 'text-slate-700 hover:bg-[#F8FAFC]' }}"
                                    data-value="aktif"
                                    data-label="Aktif"
                                >
                                    <span>Aktif</span>

                                    @if ($selectedStatus === 'aktif')
                                        <span class="material-symbols-outlined text-[18px]">
                                            check
                                        </span>
                                    @endif
                                </button>

                                <button
                                    type="button"
                                    class="js-status-option w-full px-4 py-3 text-left text-sm sm:text-base transition-colors flex items-center justify-between gap-3 {{ $selectedStatus === 'nonaktif' ? 'bg-[#D9E2FF] text-[#004199] font-semibold' : 'text-slate-700 hover:bg-[#F8FAFC]' }}"
                                    data-value="nonaktif"
                                    data-label="Nonaktif"
                                >
                                    <span>Nonaktif</span>

                                    @if ($selectedStatus === 'nonaktif')
                                        <span class="material-symbols-outlined text-[18px]">
                                            check
                                        </span>
                                    @endif
                                </button>
                            </div>
                        </div>

                        @error('status')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Asrama --}}
                    <div>
                        <label for="asrama" class="block text-sm font-semibold text-slate-700 mb-2">
                            Asrama
                        </label>

                        <input
                            id="asrama"
                            type="text"
                            name="asrama"
                            value="{{ old('asrama') }}"
                            placeholder="Contoh: Darul Wiqor"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] placeholder:text-slate-400 @error('asrama') border-red-400 focus:border-red-500 focus:ring-red-500 @enderror"
                        >

                        @error('asrama')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kamar --}}
                    <div>
                        <label for="kamar" class="block text-sm font-semibold text-slate-700 mb-2">
                            Kamar
                        </label>

                        <input
                            id="kamar"
                            type="text"
                            name="kamar"
                            value="{{ old('kamar') }}"
                            placeholder="Contoh: 16"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] placeholder:text-slate-400 @error('kamar') border-red-400 focus:border-red-500 focus:ring-red-500 @enderror"
                        >

                        @error('kamar')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-4 border-t border-slate-100">
                    <a
                        href="{{ route('admin.santri.index') }}"
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
                        Simpan Data Santri
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

            const materialCheck = option.querySelector('.material-symbols-outlined');

            if (materialCheck && materialCheck.textContent.trim() === 'check') {
                materialCheck.remove();
            }
        });
    }

    function activateDropdownOption(option) {
        const isPlaceholder = option.dataset.placeholder === 'true';

        if (isPlaceholder) {
            option.classList.remove('text-slate-700', 'hover:bg-[#F8FAFC]');
            option.classList.add('text-slate-500', 'hover:bg-[#F8FAFC]');
            return;
        }

        option.classList.remove('text-slate-700', 'hover:bg-[#F8FAFC]');
        option.classList.add('bg-[#D9E2FF]', 'text-[#004199]', 'font-semibold');

        const checkIcon = document.createElement('span');
        checkIcon.className = 'material-symbols-outlined text-[18px] dropdown-check-icon';
        checkIcon.textContent = 'check';

        option.appendChild(checkIcon);
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
</script>
@endsection