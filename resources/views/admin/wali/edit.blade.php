@extends('layouts.admin')

@section('title', 'Edit Wali - SIPontren')
@section('page-title', 'Edit Wali')

@section('content')
    @php
        $selectedHubungan = old('hubungan', $hubungan ?? 'ayah');

        $hubunganLabels = [
            'ayah' => 'Ayah',
            'ibu' => 'Ibu',
            'wali' => 'Wali',
        ];

        $selectedHubunganLabel = $hubunganLabels[$selectedHubungan] ?? 'Ayah';

        $selectedSantriIds = old('santri_ids', $selectedSantriIds ?? $wali->santris->pluck('id')->toArray());
        $selectedSantriIds = collect($selectedSantriIds)->map(fn ($id) => (string) $id)->toArray();
    @endphp

    <div class="space-y-5 sm:space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-[#191C1E] leading-tight">
                    Edit Wali Santri
                </h2>

                <p class="text-sm sm:text-base text-slate-500 mt-1 leading-relaxed">
                    Perbarui data akun wali/orang tua dan santri yang terhubung.
                </p>
            </div>

            <a
                href="{{ route('admin.wali.index') }}"
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
                    Form Edit Wali
                </h3>
            </div>

            <form action="{{ route('admin.wali.update', $wali) }}" method="POST" class="p-5 sm:p-6 space-y-6">
                @csrf
                @method('PUT')

                {{-- Info Wali --}}
                <div class="bg-[#F8FAFC] border border-slate-200 rounded-3xl p-5 flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-[#001847] text-white flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[28px]">
                            supervisor_account
                        </span>
                    </div>

                    <div class="min-w-0">
                        <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                            Sedang diedit
                        </p>

                        <h4 class="text-lg font-bold text-[#001847] leading-tight">
                            {{ $wali->name }}
                        </h4>

                        <p class="text-sm text-slate-500 mt-1 break-words">
                            {{ $wali->email }}
                        </p>
                    </div>

                    <div class="sm:ml-auto">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#D9E2FF] text-[#004199] border border-[#B0C6FF]">
                            {{ $selectedHubunganLabel }}
                        </span>
                    </div>
                </div>

                {{-- Input Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Nama Wali --}}
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">
                            Nama Wali
                        </label>

                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name', $wali->name) }}"
                            placeholder="Contoh: Bpk. Hasan"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] placeholder:text-slate-400 @error('name') border-red-400 focus:border-red-500 focus:ring-red-500 @enderror"
                        >

                        @error('name')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                            Email
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email', $wali->email) }}"
                            placeholder="Contoh: wali@email.com"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] placeholder:text-slate-400 @error('email') border-red-400 focus:border-red-500 focus:ring-red-500 @enderror"
                        >

                        @error('email')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- No HP --}}
                    <div>
                        <label for="no_hp" class="block text-sm font-semibold text-slate-700 mb-2">
                            No HP
                        </label>

                        <input
                            id="no_hp"
                            type="text"
                            name="no_hp"
                            value="{{ old('no_hp', $wali->no_hp) }}"
                            placeholder="Contoh: 081234567890"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] placeholder:text-slate-400 @error('no_hp') border-red-400 focus:border-red-500 focus:ring-red-500 @enderror"
                        >

                        @error('no_hp')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                            Password Baru
                        </label>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Kosongkan jika tidak diganti"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] placeholder:text-slate-400 @error('password') border-red-400 focus:border-red-500 focus:ring-red-500 @enderror"
                        >

                        @error('password')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror

                        <p class="text-xs text-slate-400 mt-2">
                            Biarkan kosong jika password wali tidak ingin diubah.
                        </p>
                    </div>

                    {{-- Hubungan Custom Dropdown --}}
                    <div class="md:col-span-2">
                        <label for="hubungan" class="block text-sm font-semibold text-slate-700 mb-2">
                            Hubungan
                        </label>

                        <div class="relative" id="hubunganDropdownWrapper">
                            <input
                                type="hidden"
                                name="hubungan"
                                id="hubungan"
                                value="{{ $selectedHubungan }}"
                            >

                            <button
                                type="button"
                                id="hubunganDropdownButton"
                                class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] flex items-center justify-between gap-3 text-left @error('hubungan') border-red-400 @enderror"
                            >
                                <span id="hubunganDropdownLabel" class="text-slate-800">
                                    {{ $selectedHubunganLabel }}
                                </span>

                                <span id="hubunganDropdownIcon" class="material-symbols-outlined text-slate-400 transition-transform duration-200">
                                    expand_more
                                </span>
                            </button>

                            <div
                                id="hubunganDropdownMenu"
                                class="hidden absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-[0_16px_40px_rgba(15,23,42,0.14)] overflow-hidden z-50"
                            >
                                @foreach ($hubunganLabels as $value => $label)
                                    <button
                                        type="button"
                                        class="js-hubungan-option w-full px-4 py-3 text-left text-sm sm:text-base transition-colors flex items-center justify-between gap-3 {{ $selectedHubungan === $value ? 'bg-[#D9E2FF] text-[#004199] font-semibold' : 'text-slate-700 hover:bg-[#F8FAFC]' }}"
                                        data-value="{{ $value }}"
                                        data-label="{{ $label }}"
                                    >
                                        <span>{{ $label }}</span>

                                        @if ($selectedHubungan === $value)
                                            <span class="material-symbols-outlined text-[18px] dropdown-check-icon">
                                                check
                                            </span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        @error('hubungan')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Pilih Santri --}}
                <div>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-3">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">
                                Santri Terhubung
                            </label>

                            <p class="text-sm text-slate-500 mt-1">
                                Pilih satu atau lebih santri yang akan dihubungkan dengan wali ini.
                            </p>
                        </div>

                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#D9E2FF] text-[#004199] border border-[#B0C6FF] w-fit">
                            {{ $santris->count() }} santri tersedia
                        </span>
                    </div>

                    <div class="bg-[#F8FAFC] border border-slate-200 rounded-3xl overflow-hidden @error('santri_ids') border-red-400 @enderror">
                        {{-- Search Santri --}}
                        <div class="p-4 border-b border-slate-200 bg-white">
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[22px]">
                                    search
                                </span>

                                <input
                                    type="text"
                                    id="santriSearchInput"
                                    placeholder="Cari nama santri atau kelas..."
                                    autocomplete="off"
                                    class="w-full pl-12 pr-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] placeholder:text-slate-400"
                                >
                            </div>
                        </div>

                        {{-- Santri List --}}
                        <div class="max-h-[360px] overflow-y-auto p-4">
                            <div id="santriList" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @forelse ($santris as $santri)
                                    @php
                                        $isChecked = in_array((string) $santri->id, $selectedSantriIds);
                                    @endphp

                                    <label
                                        class="santri-option cursor-pointer rounded-2xl border {{ $isChecked ? 'border-[#004199] bg-[#D9E2FF]' : 'border-slate-200 bg-white' }} p-4 transition hover:border-[#004199] hover:bg-[#F8FAFC]"
                                        data-search="{{ strtolower($santri->nama_santri . ' ' . ($santri->kelasMadrasah->nama_kelas ?? '') . ' ' . ($santri->nis ?? '')) }}"
                                    >
                                        <div class="flex items-start gap-3">
                                            <input
                                                type="checkbox"
                                                name="santri_ids[]"
                                                value="{{ $santri->id }}"
                                                class="santri-checkbox mt-1 h-4 w-4 rounded border-slate-300 text-[#004199] focus:ring-[#004199]"
                                                @checked($isChecked)
                                            >

                                            <div class="min-w-0">
                                                <p class="text-sm font-bold text-[#001847] leading-tight">
                                                    {{ $santri->nama_santri }}
                                                </p>

                                                <p class="text-xs text-slate-500 mt-1">
                                                    {{ $santri->kelasMadrasah->nama_kelas ?? '-' }}
                                                </p>

                                                @if ($santri->nis)
                                                    <p class="text-xs text-[#004199] font-semibold mt-1">
                                                        NIS: {{ $santri->nis }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                @empty
                                    <div class="md:col-span-2 text-center py-8 text-slate-500">
                                        Belum ada data santri.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    @error('santri_ids')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror

                    @error('santri_ids.*')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-4 border-t border-slate-100">
                    <a
                        href="{{ route('admin.wali.index') }}"
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
                        Update Wali
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
                menu.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');
            });

            options.forEach(function (option) {
                option.addEventListener('click', function () {
                    const value = option.dataset.value ?? '';
                    const text = option.dataset.label ?? '';

                    input.value = value;
                    label.textContent = text;

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
            checkIcon.className = 'material-symbols-outlined text-[18px] dropdown-check-icon';
            checkIcon.textContent = 'check';

            option.appendChild(checkIcon);
        }

        setupDropdown({
            wrapperId: 'hubunganDropdownWrapper',
            buttonId: 'hubunganDropdownButton',
            menuId: 'hubunganDropdownMenu',
            iconId: 'hubunganDropdownIcon',
            inputId: 'hubungan',
            labelId: 'hubunganDropdownLabel',
            optionSelector: '.js-hubungan-option',
        });

        const santriSearchInput = document.getElementById('santriSearchInput');
        const santriOptions = document.querySelectorAll('.santri-option');

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

        document.querySelectorAll('.santri-checkbox').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const label = checkbox.closest('.santri-option');

                if (!label) {
                    return;
                }

                if (checkbox.checked) {
                    label.classList.remove('border-slate-200', 'bg-white');
                    label.classList.add('border-[#004199]', 'bg-[#D9E2FF]');
                } else {
                    label.classList.add('border-slate-200', 'bg-white');
                    label.classList.remove('border-[#004199]', 'bg-[#D9E2FF]');
                }
            });
        });
    </script>
@endsection