@extends('layouts.admin')

@section('title', 'Absensi Pengajian - SIPontren')
@section('page-title', 'Absensi Pengajian')

@section('content')
    @php
        $waktuOptions = [
            '' => 'Pilih Waktu Pengajian',
            'bada_shubuh' => "Ba'da Shubuh",
            'bada_dzuhur' => "Ba'da Dzuhur",
            'bada_ashar' => "Ba'da Ashar",
            'bada_maghrib' => "Ba'da Maghrib",
            'bada_isya' => "Ba'da Isya",
            'pengajian_malam' => 'Pengajian Malam',
        ];

        $statusOptions = [
            'hadir' => [
                'label' => 'Hadir',
                'active' => 'border-[#4A5F00] bg-[#DFF5A6] text-[#4A5F00]',
                'inactive' => 'border-slate-200 bg-white text-slate-600 hover:bg-[#F8FAFC]',
            ],
            'izin' => [
                'label' => 'Izin',
                'active' => 'border-[#004199] bg-[#D9E2FF] text-[#004199]',
                'inactive' => 'border-slate-200 bg-white text-slate-600 hover:bg-[#F8FAFC]',
            ],
            'sakit' => [
                'label' => 'Sakit',
                'active' => 'border-slate-400 bg-slate-100 text-slate-700',
                'inactive' => 'border-slate-200 bg-white text-slate-600 hover:bg-[#F8FAFC]',
            ],
            'alfa' => [
                'label' => 'Alfa',
                'active' => 'border-red-300 bg-red-100 text-red-700',
                'inactive' => 'border-slate-200 bg-white text-slate-600 hover:bg-[#F8FAFC]',
            ],
        ];

        $selectedTanggal = request('tanggal', date('Y-m-d'));
        $selectedWaktu = request('waktu_pengajian', '');
        $selectedWaktuLabel = $waktuOptions[$selectedWaktu] ?? 'Pilih Waktu Pengajian';

        $selectedKelasId = request('kelas_madrasah_id', '');
        $selectedKelas = $kelasMadrasahs->firstWhere('id', $selectedKelasId);
        $selectedKelasLabel = $selectedKelas ? $selectedKelas->nama_kelas : 'Pilih Kelas Madrasah';

        $filterLengkap = isset($filterLengkap)
            ? $filterLengkap
            : (
                request()->filled('tanggal') &&
                request()->filled('waktu_pengajian') &&
                request()->filled('kelas_madrasah_id')
            );

        $absensiData = isset($absensiTersimpan)
            ? $absensiTersimpan
            : (isset($existingAbsensis) ? $existingAbsensis : collect());

        $tanggalAutoSubmit = $selectedWaktu && $selectedKelasId;
    @endphp

    <div class="space-y-5 sm:space-y-6">
        {{-- Success --}}
        @if (session('success'))
            <div class="bg-[#DFF5A6] border border-[#CDEB7C] rounded-[28px] p-5 shadow-[0_10px_28px_rgba(74,95,0,0.08)] flex items-start gap-3">
                <span class="material-symbols-outlined text-[#4A5F00] mt-0.5 shrink-0" style="font-variation-settings: 'FILL' 1;">
                    check_circle
                </span>

                <p class="text-sm text-[#4A5F00] leading-relaxed font-semibold">
                    {{ session('success') }}
                </p>
            </div>
        @endif

        {{-- Error --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-[28px] p-5 shadow-[0_10px_28px_rgba(239,68,68,0.08)]">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-red-600 mt-0.5 shrink-0">
                        error
                    </span>

                    <div>
                        <p class="text-sm font-bold text-red-700">
                            Data belum bisa disimpan
                        </p>

                        <ul class="mt-2 space-y-1 text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Header --}}
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-[#191C1E] leading-tight">
                Absensi Pengajian
            </h2>

            <p class="text-sm sm:text-base text-slate-500 mt-1 leading-relaxed">
                Pilih tanggal, waktu pengajian, dan kelas madrasah. Daftar santri akan muncul otomatis setelah pilihan lengkap.
            </p>
        </div>

        {{-- Filter Jadwal --}}
        <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-visible">
            <div class="bg-[#001847] px-5 sm:px-6 py-4 text-white rounded-t-[28px]">
                <h3 class="text-base sm:text-xl font-semibold flex items-center gap-2">
                    <span class="material-symbols-outlined text-white text-[20px]">
                        event_note
                    </span>
                    Pilih Jadwal Pengajian
                </h3>
            </div>

            <form id="jadwalForm" method="GET" action="{{ route('admin.absensi.index') }}" class="p-5 sm:p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 lg:items-center">
                    {{-- Tanggal --}}
                    <div>
                        <x-sipontren-date-picker
                            name="tanggal"
                            mode="date"
                            :value="$selectedTanggal"
                            placeholder="Pilih Tanggal"
                            :auto-submit="$tanggalAutoSubmit"
                        />
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
                            <span
                                id="waktuDropdownLabel"
                                class="truncate {{ $selectedWaktu ? 'text-slate-800' : 'text-slate-400' }}"
                            >
                                {{ $selectedWaktuLabel }}
                            </span>

                            <span id="waktuDropdownIcon" class="material-symbols-outlined text-slate-400 transition-transform duration-200 shrink-0">
                                expand_more
                            </span>
                        </button>

                        <div
                            id="waktuDropdownMenu"
                            class="hidden absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-[0_16px_40px_rgba(15,23,42,0.14)] overflow-hidden z-[9999]"
                        >
                            @foreach ($waktuOptions as $value => $label)
                                <button
                                    type="button"
                                    class="js-waktu-option w-full px-4 py-3 text-left text-sm sm:text-base transition-colors flex items-center justify-between gap-3 {{ $selectedWaktu === $value ? 'bg-[#D9E2FF] text-[#004199] font-semibold' : 'text-slate-700 hover:bg-[#F8FAFC]' }}"
                                    data-value="{{ $value }}"
                                    data-label="{{ $label }}"
                                    data-placeholder="{{ $value === '' ? 'true' : 'false' }}"
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
                            <span
                                id="kelasDropdownLabel"
                                class="truncate {{ $selectedKelasId ? 'text-slate-800' : 'text-slate-400' }}"
                            >
                                {{ $selectedKelasLabel }}
                            </span>

                            <span id="kelasDropdownIcon" class="material-symbols-outlined text-slate-400 transition-transform duration-200 shrink-0">
                                expand_more
                            </span>
                        </button>

                        <div
                            id="kelasDropdownMenu"
                            class="hidden absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-[0_16px_40px_rgba(15,23,42,0.14)] overflow-hidden z-[9999]"
                        >
                            <button
                                type="button"
                                class="js-kelas-option w-full px-4 py-3 text-left text-sm sm:text-base transition-colors flex items-center justify-between gap-3 {{ $selectedKelasId === '' ? 'bg-[#D9E2FF] text-[#004199] font-semibold' : 'text-slate-700 hover:bg-[#F8FAFC]' }}"
                                data-value=""
                                data-label="Pilih Kelas Madrasah"
                                data-placeholder="true"
                            >
                                <span>Pilih Kelas Madrasah</span>

                                @if ($selectedKelasId === '')
                                    <span class="material-symbols-outlined text-[18px] dropdown-check-icon">
                                        check
                                    </span>
                                @endif
                            </button>

                            @foreach ($kelasMadrasahs as $kelas)
                                <button
                                    type="button"
                                    class="js-kelas-option w-full px-4 py-3 text-left text-sm sm:text-base transition-colors flex items-center justify-between gap-3 {{ (string) $selectedKelasId === (string) $kelas->id ? 'bg-[#D9E2FF] text-[#004199] font-semibold' : 'text-slate-700 hover:bg-[#F8FAFC]' }}"
                                    data-value="{{ $kelas->id }}"
                                    data-label="{{ $kelas->nama_kelas }}"
                                    data-placeholder="false"
                                >
                                    <span class="truncate">{{ $kelas->nama_kelas }}</span>

                                    @if ((string) $selectedKelasId === (string) $kelas->id)
                                        <span class="material-symbols-outlined text-[18px] dropdown-check-icon">
                                            check
                                        </span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <p class="text-xs sm:text-sm text-slate-400 mt-3 leading-relaxed">
                    Daftar santri akan otomatis tampil setelah tanggal, waktu pengajian, dan kelas madrasah dipilih.
                </p>
            </form>
        </div>

        @if ($filterLengkap && $santris->isNotEmpty())
            {{-- Form Absensi --}}
            <form method="POST" action="{{ route('admin.absensi.store') }}" class="space-y-5 sm:space-y-6">
                @csrf

                <input type="hidden" name="tanggal" value="{{ $selectedTanggal }}">
                <input type="hidden" name="waktu_pengajian" value="{{ $selectedWaktu }}">
                <input type="hidden" name="kelas_madrasah_id" value="{{ $selectedKelasId }}">

                {{-- Info --}}
                <div class="bg-[#D9E2FF] border border-[#B0C6FF] rounded-[28px] p-5 shadow-[0_10px_28px_rgba(37,99,235,0.08)] flex items-start gap-3">
                    <span class="material-symbols-outlined text-[#004199] mt-0.5 shrink-0" style="font-variation-settings: 'FILL' 1;">
                        info
                    </span>

                    <p class="text-sm text-[#004199] leading-relaxed">
                        <strong>Jadwal aktif:</strong>
                        {{ date('d-m-Y', strtotime($selectedTanggal)) }},
                        {{ $selectedWaktuLabel }},
                        {{ $selectedKelasLabel }}.
                        Pastikan data absensi sudah benar sebelum disimpan.
                    </p>
                </div>

                {{-- Mobile Card List --}}
                <div class="lg:hidden space-y-4">
                    @foreach ($santris as $santri)
                        @php
                            $existing = $absensiData->get($santri->id);
                            $status = old("absensi.$santri->id.status", $existing->status ?? 'hadir');
                            $catatan = old("absensi.$santri->id.catatan", $existing->catatan ?? '');
                        @endphp

                        <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
                            <div class="bg-[#001847] px-5 py-4 text-white">
                                <p class="text-xs uppercase tracking-wider text-white/70 mb-1">
                                    Nama Santri
                                </p>

                                <h3 class="text-lg font-bold leading-tight">
                                    {{ $santri->nama_santri }}
                                </h3>

                                <p class="text-sm text-white/70 mt-1">
                                    {{ $santri->kelasMadrasah->nama_kelas ?? '-' }}
                                </p>
                            </div>

                            <div class="p-5 space-y-4">
                                <div>
                                    <p class="text-xs uppercase tracking-wider text-slate-400 mb-2">
                                        Status Kehadiran
                                    </p>

                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach ($statusOptions as $value => $config)
                                            <label class="cursor-pointer">
                                                <input
                                                    type="radio"
                                                    name="absensi[{{ $santri->id }}][status]"
                                                    value="{{ $value }}"
                                                    class="sr-only peer"
                                                    @checked($status == $value)
                                                >

                                                <span
                                                    data-status-option
                                                    class="block text-center px-3 py-2 rounded-2xl border text-sm font-semibold transition {{ $status == $value ? $config['active'] : $config['inactive'] }}"
                                                >
                                                    {{ $config['label'] }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs uppercase tracking-wider text-slate-400 mb-2">
                                        Catatan
                                    </label>

                                    <input
                                        type="text"
                                        name="absensi[{{ $santri->id }}][catatan]"
                                        value="{{ $catatan }}"
                                        placeholder="Opsional"
                                        class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] placeholder:text-slate-400"
                                    >
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Desktop Table --}}
                <div class="hidden lg:block bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
                    <div class="bg-[#001847] px-5 sm:px-6 py-4 text-white flex items-center justify-between">
                        <h3 class="text-base sm:text-xl font-semibold flex items-center gap-2">
                            <span class="material-symbols-outlined text-white text-[20px]">
                                fact_check
                            </span>
                            Daftar Santri
                        </h3>

                        <span class="text-xs sm:text-sm text-white/80">
                            Total: {{ $santris->count() }} santri
                        </span>
                    </div>

                    <table class="w-full table-fixed text-left border-collapse">
                        <colgroup>
                            <col class="w-[6%]">
                            <col class="w-[24%]">
                            <col class="w-[18%]">
                            <col class="w-[32%]">
                            <col class="w-[20%]">
                        </colgroup>

                        <thead>
                            <tr class="bg-[#001847] text-white text-sm border-t border-white/10">
                                <th class="py-4 px-4 font-semibold">No</th>
                                <th class="py-4 px-4 font-semibold">Nama Santri</th>
                                <th class="py-4 px-4 font-semibold">Kelas</th>
                                <th class="py-4 px-4 font-semibold">Status Kehadiran</th>
                                <th class="py-4 px-4 font-semibold">Catatan</th>
                            </tr>
                        </thead>

                        <tbody class="text-sm text-slate-800">
                            @foreach ($santris as $santri)
                                @php
                                    $existing = $absensiData->get($santri->id);
                                    $status = old("absensi.$santri->id.status", $existing->status ?? 'hadir');
                                    $catatan = old("absensi.$santri->id.catatan", $existing->catatan ?? '');
                                @endphp

                                <tr class="hover:bg-[#F8FAFC] transition-colors border-b border-slate-100">
                                    <td class="py-4 px-4 font-semibold text-slate-500">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="py-4 px-4 font-semibold text-[#001847] break-words">
                                        {{ $santri->nama_santri }}
                                    </td>

                                    <td class="py-4 px-4 text-slate-500 break-words">
                                        {{ $santri->kelasMadrasah->nama_kelas ?? '-' }}
                                    </td>

                                    <td class="py-4 px-4">
                                        <div class="grid grid-cols-4 gap-2">
                                            @foreach ($statusOptions as $value => $config)
                                                <label class="cursor-pointer">
                                                    <input
                                                        type="radio"
                                                        name="absensi[{{ $santri->id }}][status]"
                                                        value="{{ $value }}"
                                                        class="sr-only"
                                                        @checked($status == $value)
                                                    >

                                                    <span
                                                        data-status-option
                                                        class="block text-center px-3 py-2 rounded-2xl border text-xs font-semibold transition {{ $status == $value ? $config['active'] : $config['inactive'] }}"
                                                    >
                                                        {{ $config['label'] }}
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </td>

                                    <td class="py-4 px-4">
                                        <input
                                            type="text"
                                            name="absensi[{{ $santri->id }}][catatan]"
                                            value="{{ $catatan }}"
                                            placeholder="Opsional"
                                            class="w-full px-4 py-2.5 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] placeholder:text-slate-400"
                                        >
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Action --}}
                <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Pastikan seluruh status kehadiran santri sudah sesuai sebelum menekan tombol simpan.
                        </p>

                        <button
                            type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#004199] text-white rounded-2xl text-sm sm:text-base font-semibold hover:bg-[#00377F] transition-colors shadow-[0_8px_20px_rgba(0,65,153,0.22)]"
                        >
                            <span class="material-symbols-outlined text-[20px]">
                                save
                            </span>
                            Simpan Absensi
                        </button>
                    </div>
                </div>
            </form>
        @elseif ($filterLengkap && $santris->isEmpty())
            {{-- Empty kelas --}}
            <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-8 text-center">
                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-[32px]">
                        groups
                    </span>
                </div>

                <h3 class="text-lg font-bold text-[#001847]">
                    Tidak Ada Santri Aktif
                </h3>

                <p class="text-sm text-slate-500 mt-2 max-w-md mx-auto leading-relaxed">
                    Tidak ada santri aktif pada kelas madrasah yang dipilih.
                </p>
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-8 text-center">
                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-[32px]">
                        event_busy
                    </span>
                </div>

                <h3 class="text-lg font-bold text-[#001847]">
                    Belum Ada Daftar Santri
                </h3>

                <p class="text-sm text-slate-500 mt-2 max-w-md mx-auto leading-relaxed">
                    Silakan pilih tanggal, waktu pengajian, dan kelas madrasah untuk menampilkan daftar santri.
                </p>
            </div>
        @endif
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
                        label.classList.add('text-slate-400');
                        label.classList.remove('text-slate-800');
                    } else {
                        label.classList.remove('text-slate-400');
                        label.classList.add('text-slate-800');
                    }

                    resetDropdownOptions(options);
                    activateDropdownOption(option);

                    menu.classList.add('hidden');
                    icon.classList.remove('rotate-180');

                    autoSubmitJadwalIfComplete();
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

        function closeAllDropdowns(exceptMenuId = null) {
            const dropdowns = [
                {
                    menu: 'waktuDropdownMenu',
                    icon: 'waktuDropdownIcon',
                },
                {
                    menu: 'kelasDropdownMenu',
                    icon: 'kelasDropdownIcon',
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

        function autoSubmitJadwalIfComplete() {
            const form = document.getElementById('jadwalForm');

            if (!form) {
                return;
            }

            const tanggal = form.querySelector('[name="tanggal"]')?.value;
            const waktu = form.querySelector('[name="waktu_pengajian"]')?.value;
            const kelas = form.querySelector('[name="kelas_madrasah_id"]')?.value;

            if (tanggal && waktu && kelas) {
                form.submit();
            }
        }

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
            wrapperId: 'kelasDropdownWrapper',
            buttonId: 'kelasDropdownButton',
            menuId: 'kelasDropdownMenu',
            iconId: 'kelasDropdownIcon',
            inputId: 'kelas_madrasah_id',
            labelId: 'kelasDropdownLabel',
            optionSelector: '.js-kelas-option',
        });

        document.querySelectorAll('input[type="radio"]').forEach(function (radio) {
            radio.addEventListener('change', function () {
                const name = radio.getAttribute('name');
                const group = document.querySelectorAll('input[name="' + name + '"]');

                group.forEach(function (item) {
                    const span = item.parentElement.querySelector('[data-status-option]');

                    if (!span) {
                        return;
                    }

                    span.className = 'block text-center px-3 py-2 rounded-2xl border text-xs font-semibold transition border-slate-200 bg-white text-slate-600 hover:bg-[#F8FAFC]';
                });

                const activeSpan = radio.parentElement.querySelector('[data-status-option]');

                if (!activeSpan) {
                    return;
                }

                if (radio.value === 'hadir') {
                    activeSpan.className = 'block text-center px-3 py-2 rounded-2xl border text-xs font-semibold transition border-[#4A5F00] bg-[#DFF5A6] text-[#4A5F00]';
                }

                if (radio.value === 'izin') {
                    activeSpan.className = 'block text-center px-3 py-2 rounded-2xl border text-xs font-semibold transition border-[#004199] bg-[#D9E2FF] text-[#004199]';
                }

                if (radio.value === 'sakit') {
                    activeSpan.className = 'block text-center px-3 py-2 rounded-2xl border text-xs font-semibold transition border-slate-400 bg-slate-100 text-slate-700';
                }

                if (radio.value === 'alfa') {
                    activeSpan.className = 'block text-center px-3 py-2 rounded-2xl border text-xs font-semibold transition border-red-300 bg-red-100 text-red-700';
                }
            });
        });
    </script>
@endsection