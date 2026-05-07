@extends('layouts.admin')

@section('title', 'Data Santri - SIPontren')
@section('page-title', 'Data Santri')

@section('content')
    @php
        $selectedKelasId = request('kelas_madrasah_id');
        $selectedKelas = $kelasMadrasahs->firstWhere('id', $selectedKelasId);
    @endphp

    <div class="space-y-5 sm:space-y-6">
        {{-- Filter dan Aksi --}}
        <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-5 sm:p-6 overflow-visible">
            <form id="santriFilterForm" method="GET" action="{{ route('admin.santri.index') }}">
                <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_260px_auto] gap-3 lg:items-center">
                    {{-- Search --}}
                    <div class="relative min-w-0">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[24px]">
                            search
                        </span>

                        <input
                            id="searchInput"
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama atau NIS..."
                            autocomplete="off"
                            class="w-full pl-12 pr-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] placeholder:text-slate-400"
                        >
                    </div>

                    {{-- Custom Dropdown Kelas --}}
                    <div class="relative min-w-0" id="kelasDropdownWrapper">
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
                                {{ $selectedKelas ? $selectedKelas->nama_kelas : 'Semua Kelas' }}
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
                                class="js-kelas-option w-full px-4 py-3 text-left text-sm sm:text-base transition-colors flex items-center justify-between gap-3 {{ !request('kelas_madrasah_id') ? 'bg-[#D9E2FF] text-[#004199] font-semibold' : 'text-slate-700 hover:bg-[#F8FAFC]' }}"
                                data-value=""
                                data-label="Semua Kelas"
                            >
                                <span>Semua Kelas</span>

                                @if (!request('kelas_madrasah_id'))
                                    <span class="material-symbols-outlined text-[18px] dropdown-check-icon">
                                        check
                                    </span>
                                @endif
                            </button>

                            @foreach ($kelasMadrasahs as $kelas)
                                <button
                                    type="button"
                                    class="js-kelas-option w-full px-4 py-3 text-left text-sm sm:text-base transition-colors flex items-center justify-between gap-3 {{ request('kelas_madrasah_id') == $kelas->id ? 'bg-[#D9E2FF] text-[#004199] font-semibold' : 'text-slate-700 hover:bg-[#F8FAFC]' }}"
                                    data-value="{{ $kelas->id }}"
                                    data-label="{{ $kelas->nama_kelas }}"
                                >
                                    <span class="truncate">{{ $kelas->nama_kelas }}</span>

                                    @if (request('kelas_madrasah_id') == $kelas->id)
                                        <span class="material-symbols-outlined text-[18px] dropdown-check-icon shrink-0">
                                            check
                                        </span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Tombol Tambah Santri --}}
                    <a
                        href="{{ route('admin.santri.create') }}"
                        class="w-full lg:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#004199] text-white rounded-2xl text-sm sm:text-base font-semibold hover:bg-[#00377F] transition-colors shadow-[0_8px_20px_rgba(0,65,153,0.22)] whitespace-nowrap"
                    >
                        <span class="material-symbols-outlined text-[22px]">
                            add
                        </span>
                        Tambah Santri
                    </a>
                </div>
            </form>
        </div>

        {{-- Mobile / Tablet Card List --}}
        <div class="lg:hidden space-y-4">
            @forelse ($santris as $santri)
                <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
                    <div class="bg-[#001847] px-5 py-4 text-white flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-xs uppercase tracking-wider text-white/70 mb-1">
                                Nama Santri
                            </p>

                            <h3 class="text-lg font-bold leading-tight">
                                {{ $santri->nama_santri }}
                            </h3>
                        </div>

                        @if ($santri->status === 'aktif')
                            <span class="shrink-0 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#DFF5A6] text-[#4A5F00]">
                                Aktif
                            </span>
                        @else
                            <span class="shrink-0 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                Nonaktif
                            </span>
                        @endif
                    </div>

                    <div class="p-5">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                    NIS
                                </p>
                                <p class="text-sm font-semibold text-[#004199]">
                                    {{ $santri->nis }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                    Kelas
                                </p>
                                <p class="text-sm font-semibold text-slate-700">
                                    {{ $santri->kelasMadrasah->nama_kelas ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                    Asrama
                                </p>
                                <p class="text-sm font-semibold text-slate-700">
                                    {{ $santri->asrama ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                    Kamar
                                </p>
                                <p class="text-sm font-semibold text-slate-700">
                                    {{ $santri->kamar ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 border-t border-slate-100 pt-4">
                            <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                Wali Santri
                            </p>

                            <div class="text-sm font-semibold text-slate-700">
                                @forelse ($santri->walis as $wali)
                                    <div>{{ $wali->name }}</div>
                                @empty
                                    -
                                @endforelse
                            </div>
                        </div>

                        <div class="flex items-center gap-2 mt-5">
                            <a
                                href="{{ route('admin.santri.edit', $santri) }}"
                                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl bg-[#D9E2FF] text-[#004199] text-sm font-semibold hover:bg-[#C6D6FF] transition-colors"
                            >
                                <span class="material-symbols-outlined text-[18px]">
                                    edit
                                </span>
                                Edit
                            </a>

                            <button
                                type="button"
                                onclick="openDeleteModal(@js(route('admin.santri.destroy', $santri)), @js($santri->nama_santri))"
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
                            group_off
                        </span>
                    </div>

                    <p class="text-sm text-slate-500">
                        Belum ada data santri.
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Desktop Table --}}
        <div class="hidden lg:block bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
            <table class="w-full table-fixed text-left border-collapse">
                <colgroup>
                    <col class="w-[12%]">
                    <col class="w-[18%]">
                    <col class="w-[14%]">
                    <col class="w-[12%]">
                    <col class="w-[8%]">
                    <col class="w-[14%]">
                    <col class="w-[10%]">
                    <col class="w-[12%]">
                </colgroup>

                <thead>
                    <tr class="bg-[#001847] text-white text-sm">
                        <th class="py-4 px-4 font-semibold">NIS</th>
                        <th class="py-4 px-4 font-semibold">Nama Santri</th>
                        <th class="py-4 px-4 font-semibold">Kelas</th>
                        <th class="py-4 px-4 font-semibold">Asrama</th>
                        <th class="py-4 px-4 font-semibold">Kamar</th>
                        <th class="py-4 px-4 font-semibold">Nama Wali</th>
                        <th class="py-4 px-4 font-semibold text-center">Status</th>
                        <th class="py-4 px-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="text-sm text-slate-800">
                    @forelse ($santris as $santri)
                        <tr class="hover:bg-[#F8FAFC] transition-colors border-b border-slate-100">
                            <td class="py-4 px-4 font-semibold text-[#004199] break-words">
                                {{ $santri->nis }}
                            </td>

                            <td class="py-4 px-4 font-semibold break-words">
                                {{ $santri->nama_santri }}
                            </td>

                            <td class="py-4 px-4 text-slate-500 break-words">
                                {{ $santri->kelasMadrasah->nama_kelas ?? '-' }}
                            </td>

                            <td class="py-4 px-4 text-slate-500 break-words">
                                {{ $santri->asrama ?? '-' }}
                            </td>

                            <td class="py-4 px-4 text-slate-500 break-words">
                                {{ $santri->kamar ?? '-' }}
                            </td>

                            <td class="py-4 px-4 text-slate-500 break-words">
                                @forelse ($santri->walis as $wali)
                                    <div>{{ $wali->name }}</div>
                                @empty
                                    -
                                @endforelse
                            </td>

                            <td class="py-4 px-4 text-center">
                                @if ($santri->status === 'aktif')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#DFF5A6] text-[#4A5F00] border border-[#CDEB7C]">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a
                                        href="{{ route('admin.santri.edit', $santri) }}"
                                        class="p-1.5 text-[#004199] hover:bg-[#D9E2FF] rounded-md transition-colors"
                                        title="Edit"
                                    >
                                        <span class="material-symbols-outlined text-[20px]">
                                            edit
                                        </span>
                                    </a>

                                    <button
                                        type="button"
                                        onclick="openDeleteModal(@js(route('admin.santri.destroy', $santri)), @js($santri->nama_santri))"
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
                            <td colspan="8" class="py-10 px-6 text-center text-slate-500">
                                Belum ada data santri.
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
                    <span class="font-semibold text-slate-700">{{ $santris->firstItem() ?? 0 }}</span>
                    -
                    <span class="font-semibold text-slate-700">{{ $santris->lastItem() ?? 0 }}</span>
                    dari
                    <span class="font-semibold text-slate-700">{{ $santris->total() }}</span>
                    santri
                </div>

                <div class="flex justify-center lg:justify-end">
                    {{ $santris->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Custom Delete Modal --}}
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
                    Yakin ingin menghapus data santri
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
        let searchTimer = null;

        const filterForm = document.getElementById('santriFilterForm');
        const searchInput = document.getElementById('searchInput');

        if (searchInput && filterForm) {
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimer);

                searchTimer = setTimeout(function () {
                    filterForm.submit();
                }, 500);
            });
        }

        const kelasWrapper = document.getElementById('kelasDropdownWrapper');
        const kelasButton = document.getElementById('kelasDropdownButton');
        const kelasMenu = document.getElementById('kelasDropdownMenu');
        const kelasIcon = document.getElementById('kelasDropdownIcon');
        const kelasInput = document.getElementById('kelas_madrasah_id');
        const kelasLabel = document.getElementById('kelasDropdownLabel');
        const kelasOptions = document.querySelectorAll('.js-kelas-option');

        if (kelasButton && kelasMenu && kelasIcon) {
            kelasButton.addEventListener('click', function () {
                kelasMenu.classList.toggle('hidden');
                kelasIcon.classList.toggle('rotate-180');
            });
        }

        kelasOptions.forEach(function (option) {
            option.addEventListener('click', function () {
                const value = option.dataset.value ?? '';
                const label = option.dataset.label ?? 'Semua Kelas';

                if (kelasInput) {
                    kelasInput.value = value;
                }

                if (kelasLabel) {
                    kelasLabel.textContent = label;
                }

                if (kelasMenu) {
                    kelasMenu.classList.add('hidden');
                }

                if (kelasIcon) {
                    kelasIcon.classList.remove('rotate-180');
                }

                if (filterForm) {
                    filterForm.submit();
                }
            });
        });

        document.addEventListener('click', function (event) {
            if (!kelasWrapper || !kelasMenu || !kelasIcon) {
                return;
            }

            if (!kelasWrapper.contains(event.target)) {
                kelasMenu.classList.add('hidden');
                kelasIcon.classList.remove('rotate-180');
            }
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