@extends('layouts.orang-tua')

@section('title', 'Notifikasi - SIPontren')
@section('page-title', 'Notifikasi')

@section('content')
    @php
        $jenisOptions = [
            '' => 'Semua Jenis',
            'absensi' => 'Absensi',
            'pembayaran' => 'Pembayaran',
            'pelanggaran' => 'Pelanggaran',
            'sistem' => 'Sistem',
        ];

        $statusOptions = [
            '' => 'Semua Status',
            'belum_dibaca' => 'Belum Dibaca',
            'sudah_dibaca' => 'Sudah Dibaca',
        ];

        $jenisBadgeClasses = [
            'absensi' => 'bg-[#D9E2FF] text-[#004199] border-[#B0C6FF]',
            'pembayaran' => 'bg-[#DFF5A6] text-[#4A5F00] border-[#CDEB7C]',
            'pelanggaran' => 'bg-red-100 text-red-700 border-red-200',
            'sistem' => 'bg-slate-100 text-slate-600 border-slate-200',
        ];

        $selectedJenis = request('jenis', '');
        $selectedJenisLabel = $jenisOptions[$selectedJenis] ?? 'Semua Jenis';

        $selectedStatus = request('status', '');
        $selectedStatusLabel = $statusOptions[$selectedStatus] ?? 'Semua Status';

        $notifikasiItems = $notifikasis->getCollection();
        $jumlahSudahDibacaHalaman = $notifikasiItems->where('dibaca', true)->count();
        $jumlahBelumDibacaHalaman = $notifikasiItems->where('dibaca', false)->count();
    @endphp

    <div class="space-y-5 sm:space-y-6">
        {{-- Header --}}
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-[#191C1E] leading-tight">
                Notifikasi Orang Tua
            </h2>

            <p class="text-sm sm:text-base text-slate-500 mt-1 leading-relaxed">
                Pantau informasi absensi, pembayaran, pelanggaran, dan sistem yang terkait dengan anak Anda.
            </p>
        </div>

        {{-- Privacy Notice --}}
        <div class="bg-[#D9E2FF] border border-[#B0C6FF] rounded-[28px] p-5 shadow-[0_10px_28px_rgba(37,99,235,0.08)] flex items-start gap-3">
            <span class="material-symbols-outlined text-[#004199] mt-0.5 shrink-0" style="font-variation-settings: 'FILL' 1;">
                shield
            </span>

            <p class="text-sm text-[#004199] leading-relaxed">
                <strong>Data Privasi:</strong>
                Data yang ditampilkan hanya notifikasi terkait anak Anda.
            </p>
        </div>

        {{-- Ringkasan --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
            <div class="bg-yellow-50 rounded-[28px] border border-yellow-200 shadow-[0_10px_28px_rgba(234,179,8,0.08)] p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-yellow-700">
                            Belum Dibaca
                        </p>

                        <p class="text-3xl font-bold text-yellow-700 mt-2">
                            {{ number_format($jumlahBelumDibaca) }}
                        </p>
                    </div>

                    <div class="w-12 h-12 rounded-full bg-yellow-100 text-yellow-700 flex items-center justify-center">
                        <span class="material-symbols-outlined">
                            mark_email_unread
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-slate-500">
                            Data Halaman Ini
                        </p>

                        <p class="text-3xl font-bold text-[#191C1E] mt-2">
                            {{ number_format($notifikasis->count()) }}
                        </p>
                    </div>

                    <div class="w-12 h-12 rounded-full bg-[#D9E2FF] text-[#004199] flex items-center justify-center">
                        <span class="material-symbols-outlined">
                            notifications
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-[#DFF5A6] rounded-[28px] border border-[#CDEB7C] shadow-[0_10px_28px_rgba(74,95,0,0.08)] p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-[#4A5F00]">
                            Sudah Dibaca
                        </p>

                        <p class="text-3xl font-bold text-[#4A5F00] mt-2">
                            {{ number_format($jumlahSudahDibacaHalaman) }}
                        </p>
                    </div>

                    <div class="w-12 h-12 rounded-full bg-white/40 text-[#4A5F00] flex items-center justify-center">
                        <span class="material-symbols-outlined">
                            mark_email_read
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Card --}}
        <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-5 sm:p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#001847] text-white flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[26px]">
                            done_all
                        </span>
                    </div>

                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-[#001847]">
                            Tandai Semua Notifikasi Dibaca
                        </h3>

                        <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                            Gunakan tombol ini untuk mengubah semua notifikasi yang belum dibaca menjadi sudah dibaca.
                        </p>
                    </div>
                </div>

                <button
                    type="button"
                    onclick="openReadAllModal()"
                    class="w-full lg:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#004199] text-white rounded-2xl text-sm sm:text-base font-semibold hover:bg-[#00377F] transition-colors shadow-[0_8px_20px_rgba(0,65,153,0.22)] whitespace-nowrap"
                >
                    <span class="material-symbols-outlined text-[20px]">
                        done_all
                    </span>
                    Tandai Semua Dibaca
                </button>
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-5 sm:p-6 overflow-visible">
            <form id="notifikasiFilterForm" method="GET" action="{{ route('orang-tua.notifikasi.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-[240px_240px_auto] gap-3 xl:items-center">
                    {{-- Jenis --}}
                    <div class="relative" id="jenisDropdownWrapper">
                        <input
                            type="hidden"
                            name="jenis"
                            id="jenis"
                            value="{{ $selectedJenis }}"
                        >

                        <button
                            type="button"
                            id="jenisDropdownButton"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] flex items-center justify-between gap-3 text-left"
                        >
                            <span id="jenisDropdownLabel" class="truncate">
                                {{ $selectedJenisLabel }}
                            </span>

                            <span id="jenisDropdownIcon" class="material-symbols-outlined text-slate-400 transition-transform duration-200 shrink-0">
                                expand_more
                            </span>
                        </button>

                        <div
                            id="jenisDropdownMenu"
                            class="hidden absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-[0_16px_40px_rgba(15,23,42,0.14)] overflow-hidden z-50"
                        >
                            @foreach ($jenisOptions as $value => $label)
                                <button
                                    type="button"
                                    class="js-jenis-option w-full px-4 py-3 text-left text-sm sm:text-base transition-colors flex items-center justify-between gap-3 {{ $selectedJenis === $value ? 'bg-[#D9E2FF] text-[#004199] font-semibold' : 'text-slate-700 hover:bg-[#F8FAFC]' }}"
                                    data-value="{{ $value }}"
                                    data-label="{{ $label }}"
                                >
                                    <span>{{ $label }}</span>

                                    @if ($selectedJenis === $value)
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

                    {{-- Reset --}}
                    <a
                        href="{{ route('orang-tua.notifikasi.index') }}"
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
            @forelse ($notifikasis as $notifikasi)
                @php
                    $jenisLabel = $jenisOptions[$notifikasi->jenis] ?? ucfirst($notifikasi->jenis);
                    $jenisClass = $jenisBadgeClasses[$notifikasi->jenis] ?? 'bg-slate-100 text-slate-600 border-slate-200';

                    $statusLabel = $notifikasi->dibaca ? 'Sudah Dibaca' : 'Belum Dibaca';
                    $statusClass = $notifikasi->dibaca
                        ? 'bg-[#DFF5A6] text-[#4A5F00] border-[#CDEB7C]'
                        : 'bg-yellow-100 text-yellow-700 border-yellow-200';
                @endphp

                <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
                    <div class="bg-[#001847] px-5 py-4 text-white flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-xs uppercase tracking-wider text-white/70 mb-1">
                                Judul Notifikasi
                            </p>

                            <h3 class="text-lg font-bold leading-tight">
                                {{ $notifikasi->judul }}
                            </h3>

                            <p class="text-sm text-white/70 mt-1">
                                {{ $notifikasi->created_at ? $notifikasi->created_at->format('d-m-Y H:i') : '-' }}
                            </p>
                        </div>

                        <span class="shrink-0 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <div class="p-5 space-y-4">
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $jenisClass }}">
                                {{ $jenisLabel }}
                            </span>
                        </div>

                        <div>
                            <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                Santri
                            </p>

                            <p class="text-sm font-semibold text-[#001847]">
                                {{ $notifikasi->santri->nama_santri ?? '-' }}
                            </p>
                        </div>

                        <div class="border-t border-slate-100 pt-4">
                            <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                Isi Notifikasi
                            </p>

                            <p class="text-sm text-slate-600 leading-relaxed">
                                {{ $notifikasi->isi }}
                            </p>
                        </div>

                        <div class="pt-2">
                            @if (! $notifikasi->dibaca)
                                <form action="{{ route('orang-tua.notifikasi.dibaca', $notifikasi) }}" method="POST">
                                    @csrf

                                    <button
                                        type="submit"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl bg-[#D9E2FF] text-[#004199] text-sm font-semibold hover:bg-[#C6D6FF] transition-colors"
                                    >
                                        <span class="material-symbols-outlined text-[18px]">
                                            mark_email_read
                                        </span>
                                        Tandai Dibaca
                                    </button>
                                </form>
                            @else
                                <div class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl bg-slate-100 text-slate-600 text-sm font-semibold">
                                    <span class="material-symbols-outlined text-[18px]">
                                        done
                                    </span>
                                    Sudah Dibaca
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-6 text-center">
                    <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3 text-slate-400">
                        <span class="material-symbols-outlined">
                            notifications_off
                        </span>
                    </div>

                    <p class="text-sm text-slate-500">
                        Belum ada notifikasi.
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Desktop Table --}}
        <div class="hidden lg:block bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
            <table class="w-full table-fixed text-left border-collapse">
                <colgroup>
                    <col class="w-[6%]">
                    <col class="w-[14%]">
                    <col class="w-[12%]">
                    <col class="w-[18%]">
                    <col class="w-[24%]">
                    <col class="w-[12%]">
                    <col class="w-[8%]">
                    <col class="w-[6%]">
                </colgroup>

                <thead>
                    <tr class="bg-[#001847] text-white text-sm">
                        <th class="py-4 px-3 font-semibold">No</th>
                        <th class="py-4 px-3 font-semibold">Tanggal</th>
                        <th class="py-4 px-3 font-semibold text-center">Jenis</th>
                        <th class="py-4 px-3 font-semibold">Judul</th>
                        <th class="py-4 px-3 font-semibold">Isi</th>
                        <th class="py-4 px-3 font-semibold">Santri</th>
                        <th class="py-4 px-3 font-semibold text-center">Status</th>
                        <th class="py-4 px-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="text-sm text-slate-800">
                    @forelse ($notifikasis as $notifikasi)
                        @php
                            $jenisLabel = $jenisOptions[$notifikasi->jenis] ?? ucfirst($notifikasi->jenis);
                            $jenisClass = $jenisBadgeClasses[$notifikasi->jenis] ?? 'bg-slate-100 text-slate-600 border-slate-200';

                            $statusLabel = $notifikasi->dibaca ? 'Sudah Dibaca' : 'Belum Dibaca';
                            $statusClass = $notifikasi->dibaca
                                ? 'bg-[#DFF5A6] text-[#4A5F00] border-[#CDEB7C]'
                                : 'bg-yellow-100 text-yellow-700 border-yellow-200';
                        @endphp

                        <tr class="hover:bg-[#F8FAFC] transition-colors border-b border-slate-100">
                            <td class="py-4 px-3 font-semibold text-slate-500">
                                {{ $notifikasis->firstItem() + $loop->index }}
                            </td>

                            <td class="py-4 px-3 text-slate-500 break-words">
                                {{ $notifikasi->created_at ? $notifikasi->created_at->format('d-m-Y H:i') : '-' }}
                            </td>

                            <td class="py-4 px-3 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $jenisClass }}">
                                    {{ $jenisLabel }}
                                </span>
                            </td>

                            <td class="py-4 px-3 font-semibold text-[#001847] break-words">
                                {{ $notifikasi->judul }}
                            </td>

                            <td class="py-4 px-3 text-slate-500 break-words">
                                {{ $notifikasi->isi }}
                            </td>

                            <td class="py-4 px-3 text-slate-600 break-words">
                                {{ $notifikasi->santri->nama_santri ?? '-' }}
                            </td>

                            <td class="py-4 px-3 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            <td class="py-4 px-3 text-right">
                                @if (! $notifikasi->dibaca)
                                    <form action="{{ route('orang-tua.notifikasi.dibaca', $notifikasi) }}" method="POST">
                                        @csrf

                                        <button
                                            type="submit"
                                            class="p-1.5 text-[#004199] hover:bg-[#D9E2FF] rounded-md transition-colors"
                                            title="Tandai Dibaca"
                                        >
                                            <span class="material-symbols-outlined text-[20px]">
                                                mark_email_read
                                            </span>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400 font-semibold">
                                        -
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 px-6 text-center text-slate-500">
                                Belum ada notifikasi.
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
                    <span class="font-semibold text-slate-700">{{ $notifikasis->firstItem() ?? 0 }}</span>
                    -
                    <span class="font-semibold text-slate-700">{{ $notifikasis->lastItem() ?? 0 }}</span>
                    dari
                    <span class="font-semibold text-slate-700">{{ $notifikasis->total() }}</span>
                    notifikasi
                </div>

                <div class="flex justify-center lg:justify-end">
                    {{ $notifikasis->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Read All Modal --}}
    <div id="readAllModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeReadAllModal()"></div>

        <div class="relative z-10 w-full max-w-md bg-white rounded-[28px] border border-slate-200 shadow-[0_24px_60px_rgba(15,23,42,0.25)] overflow-hidden">
            <div class="bg-[#001847] px-6 py-4 text-white flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white">
                        done_all
                    </span>
                </div>

                <h3 class="text-lg font-bold">
                    Tandai Semua Dibaca
                </h3>
            </div>

            <div class="p-6">
                <p class="text-slate-600 leading-relaxed">
                    Tandai semua notifikasi sebagai sudah dibaca?
                </p>

                <p class="text-sm text-[#004199] mt-3">
                    Notifikasi yang belum dibaca akan berubah menjadi sudah dibaca.
                </p>

                <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <button
                        type="button"
                        onclick="closeReadAllModal()"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 text-sm font-semibold hover:bg-slate-200 transition-colors"
                    >
                        Batal
                    </button>

                    <form action="{{ route('orang-tua.notifikasi.tandai-semua-dibaca') }}" method="POST" class="w-full sm:w-auto">
                        @csrf

                        <button
                            type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-[#004199] text-white text-sm font-semibold hover:bg-[#00377F] transition-colors"
                        >
                            <span class="material-symbols-outlined text-[18px]">
                                done_all
                            </span>
                            Ya, Tandai
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const filterForm = document.getElementById('notifikasiFilterForm');

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
                    menu: 'jenisDropdownMenu',
                    icon: 'jenisDropdownIcon',
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
            wrapperId: 'jenisDropdownWrapper',
            buttonId: 'jenisDropdownButton',
            menuId: 'jenisDropdownMenu',
            iconId: 'jenisDropdownIcon',
            inputId: 'jenis',
            labelId: 'jenisDropdownLabel',
            optionSelector: '.js-jenis-option',
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

        function openReadAllModal() {
            const modal = document.getElementById('readAllModal');

            if (!modal) {
                return;
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeReadAllModal() {
            const modal = document.getElementById('readAllModal');

            if (!modal) {
                return;
            }

            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeReadAllModal();
            }
        });
    </script>
@endsection