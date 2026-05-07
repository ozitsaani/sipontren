@extends('layouts.admin')

@section('title', 'Pelanggaran Santri - SIPontren')
@section('page-title', 'Pelanggaran Santri')

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

        $selectedKelasId = request('kelas_madrasah_id', '');
        $selectedKelas = $kelasMadrasahs->firstWhere('id', $selectedKelasId);
        $selectedKelasLabel = $selectedKelas ? $selectedKelas->nama_kelas : 'Semua Kelas';

        $selectedTingkat = request('tingkat_pelanggaran', '');
        $selectedTingkatLabel = $tingkatOptions[$selectedTingkat] ?? 'Semua Tingkat';

        $pelanggaranItems = $pelanggarans->getCollection();

        $totalRingan = $pelanggaranItems->where('tingkat_pelanggaran', 'ringan')->count();
        $totalSedang = $pelanggaranItems->where('tingkat_pelanggaran', 'sedang')->count();
        $totalBerat = $pelanggaranItems->where('tingkat_pelanggaran', 'berat')->count();
    @endphp

    <div class="space-y-5 sm:space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-[#191C1E] leading-tight">
                    Pelanggaran Santri
                </h2>

                <p class="text-sm sm:text-base text-slate-500 mt-1 leading-relaxed">
                    Kelola catatan pelanggaran santri berdasarkan bulan, kelas, tingkat pelanggaran, dan pencarian.
                </p>
            </div>

            <a
                href="{{ route('admin.pelanggaran.create') }}"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#004199] text-white rounded-2xl text-sm sm:text-base font-semibold hover:bg-[#00377F] transition-colors shadow-[0_8px_20px_rgba(0,65,153,0.22)]"
            >
                <span class="material-symbols-outlined text-[22px]">
                    add
                </span>
                Tambah Pelanggaran
            </a>
        </div>

        {{-- Ringkasan --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-5">
                <p class="text-xs sm:text-sm font-semibold text-slate-500">
                    Data Halaman Ini
                </p>

                <p class="text-3xl font-bold text-[#191C1E] mt-3">
                    {{ number_format($pelanggarans->count()) }}
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
            <form id="pelanggaranFilterForm" method="GET" action="{{ route('admin.pelanggaran.index') }}">
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

                    {{-- Kelas --}}
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
                                href="{{ route('admin.pelanggaran.index', request()->except('search')) }}"
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
                        href="{{ route('admin.pelanggaran.index') }}"
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
            @forelse ($pelanggarans as $pelanggaran)
                @php
                    $tingkatLabel = $tingkatOptions[$pelanggaran->tingkat_pelanggaran] ?? ucfirst($pelanggaran->tingkat_pelanggaran);
                    $tingkatClass = $tingkatBadgeClasses[$pelanggaran->tingkat_pelanggaran] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                    $namaSantri = $pelanggaran->santri->nama_santri ?? '-';
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
                                NIS: {{ $pelanggaran->santri->nis ?? '-' }}
                            </p>
                        </div>

                        <span class="shrink-0 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $tingkatClass }}">
                            {{ $tingkatLabel }}
                        </span>
                    </div>

                    <div class="p-5 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                    Tanggal
                                </p>

                                <p class="text-sm font-semibold text-slate-700">
                                    {{ $pelanggaran->tanggal ? date('d-m-Y', strtotime($pelanggaran->tanggal)) : '-' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                    Kelas
                                </p>

                                <p class="text-sm font-semibold text-slate-700">
                                    {{ $pelanggaran->santri->kelasMadrasah->nama_kelas ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-4">
                            <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                Jenis Pelanggaran
                            </p>

                            <p class="text-sm font-semibold text-[#001847] leading-relaxed">
                                {{ $pelanggaran->jenis_pelanggaran }}
                            </p>
                        </div>

                        <div class="border-t border-slate-100 pt-4">
                            <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                Catatan
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

                        <div class="border-t border-slate-100 pt-4">
                            <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                Diinput Oleh
                            </p>

                            <p class="text-sm font-semibold text-slate-700">
                                {{ $pelanggaran->admin->name ?? '-' }}
                            </p>
                        </div>

                        <div class="flex items-center gap-2 pt-2">
                            <a
                                href="{{ route('admin.pelanggaran.edit', $pelanggaran) }}"
                                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl bg-[#D9E2FF] text-[#004199] text-sm font-semibold hover:bg-[#C6D6FF] transition-colors"
                            >
                                <span class="material-symbols-outlined text-[18px]">
                                    edit
                                </span>
                                Edit
                            </a>

                            <button
                                type="button"
                                onclick="openDeleteModal(@js(route('admin.pelanggaran.destroy', $pelanggaran)), @js($namaSantri))"
                                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl bg-red-50 text-red-600 text-sm font-semibold hover:bg-red-100 transition-colors"
                            >
                                <span class="material-symbols-outlined text-[18px]">
                                    delete
                                </span>
                                Hapus
                            </button>
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
                        Belum ada data pelanggaran.
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Desktop Table --}}
        <div class="hidden lg:block bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
            <table class="w-full table-fixed text-left border-collapse">
                <colgroup>
                    <col class="w-[5%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[16%]">
                    <col class="w-[13%]">
                    <col class="w-[15%]">
                    <col class="w-[9%]">
                    <col class="w-[12%]">
                    <col class="w-[10%]">
                </colgroup>

                <thead>
                    <tr class="bg-[#001847] text-white text-sm">
                        <th class="py-4 px-3 font-semibold">No</th>
                        <th class="py-4 px-3 font-semibold">Tanggal</th>
                        <th class="py-4 px-3 font-semibold">NIS</th>
                        <th class="py-4 px-3 font-semibold">Nama Santri</th>
                        <th class="py-4 px-3 font-semibold">Kelas</th>
                        <th class="py-4 px-3 font-semibold">Jenis</th>
                        <th class="py-4 px-3 font-semibold text-center">Tingkat</th>
                        <th class="py-4 px-3 font-semibold">Tindak Lanjut</th>
                        <th class="py-4 px-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="text-sm text-slate-800">
                    @forelse ($pelanggarans as $pelanggaran)
                        @php
                            $tingkatLabel = $tingkatOptions[$pelanggaran->tingkat_pelanggaran] ?? ucfirst($pelanggaran->tingkat_pelanggaran);
                            $tingkatClass = $tingkatBadgeClasses[$pelanggaran->tingkat_pelanggaran] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                            $namaSantri = $pelanggaran->santri->nama_santri ?? '-';
                        @endphp

                        <tr class="hover:bg-[#F8FAFC] transition-colors border-b border-slate-100">
                            <td class="py-4 px-3 font-semibold text-slate-500">
                                {{ $pelanggarans->firstItem() + $loop->index }}
                            </td>

                            <td class="py-4 px-3 text-slate-500 break-words">
                                {{ $pelanggaran->tanggal ? date('d-m-Y', strtotime($pelanggaran->tanggal)) : '-' }}
                            </td>

                            <td class="py-4 px-3 font-semibold text-[#004199] break-words">
                                {{ $pelanggaran->santri->nis ?? '-' }}
                            </td>

                            <td class="py-4 px-3 font-semibold text-[#001847] break-words">
                                {{ $namaSantri }}
                            </td>

                            <td class="py-4 px-3 text-slate-500 break-words">
                                {{ $pelanggaran->santri->kelasMadrasah->nama_kelas ?? '-' }}
                            </td>

                            <td class="py-4 px-3 text-slate-700 break-words">
                                <p class="font-semibold">
                                    {{ $pelanggaran->jenis_pelanggaran }}
                                </p>

                                <p class="text-xs text-slate-400 mt-1">
                                    {{ $pelanggaran->admin->name ?? '-' }}
                                </p>
                            </td>

                            <td class="py-4 px-3 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $tingkatClass }}">
                                    {{ $tingkatLabel }}
                                </span>
                            </td>

                            <td class="py-4 px-3 text-slate-500 break-words">
                                {{ $pelanggaran->tindak_lanjut ?? '-' }}
                            </td>

                            <td class="py-4 px-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a
                                        href="{{ route('admin.pelanggaran.edit', $pelanggaran) }}"
                                        class="p-1.5 text-[#004199] hover:bg-[#D9E2FF] rounded-md transition-colors"
                                        title="Edit"
                                    >
                                        <span class="material-symbols-outlined text-[20px]">
                                            edit
                                        </span>
                                    </a>

                                    <button
                                        type="button"
                                        onclick="openDeleteModal(@js(route('admin.pelanggaran.destroy', $pelanggaran)), @js($namaSantri))"
                                        class="p-1.5 text-red-600 hover:bg-red-100 rounded-md transition-colors"
                                        title="Hapus"
                                    >
                                        <span class="material-symbols-outlined text-[20px]">
                                            delete
                                        </span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-10 px-6 text-center text-slate-500">
                                Belum ada data pelanggaran.
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

    {{-- Delete Modal --}}
    <div id="deleteModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeDeleteModal()"></div>

        <div class="relative z-10 w-full max-w-md bg-white rounded-[28px] border border-slate-200 shadow-[0_24px_60px_rgba(15,23,42,0.25)] overflow-hidden">
            <div class="bg-[#001847] px-6 py-4 text-white flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white">
                        warning
                    </span>
                </div>

                <h3 class="text-lg font-bold">
                    Konfirmasi Hapus
                </h3>
            </div>

            <div class="p-6">
                <p class="text-slate-600 leading-relaxed">
                    Yakin ingin menghapus data pelanggaran santri
                    <span id="deleteSantriName" class="font-bold text-[#001847]"></span>?
                </p>

                <p class="text-sm text-red-600 mt-3">
                    Tindakan ini tidak bisa dibatalkan.
                </p>

                <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <button
                        type="button"
                        onclick="closeDeleteModal()"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-slate-100 text-slate-700 text-sm font-semibold hover:bg-slate-200 transition-colors"
                    >
                        Batal
                    </button>

                    <form id="deleteForm" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-red-600 text-white text-sm font-semibold hover:bg-red-700 transition-colors"
                        >
                            <span class="material-symbols-outlined text-[18px]">
                                delete
                            </span>
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const filterForm = document.getElementById('pelanggaranFilterForm');
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
            wrapperId: 'kelasDropdownWrapper',
            buttonId: 'kelasDropdownButton',
            menuId: 'kelasDropdownMenu',
            iconId: 'kelasDropdownIcon',
            inputId: 'kelas_madrasah_id',
            labelId: 'kelasDropdownLabel',
            optionSelector: '.js-kelas-option',
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

        function openDeleteModal(actionUrl, santriName) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            const name = document.getElementById('deleteSantriName');

            if (!modal || !form || !name) {
                return;
            }

            form.action = actionUrl;
            name.textContent = '"' + santriName + '"';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');

            if (!modal) {
                return;
            }

            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
@endsection