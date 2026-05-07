@extends('layouts.admin')

@section('title', 'Data Wali - SIPontren')
@section('page-title', 'Data Wali')

@section('content')
    <div class="space-y-5 sm:space-y-6">
        {{-- Filter dan Aksi --}}
        <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-5 sm:p-6">
            <form id="waliFilterForm" method="GET" action="{{ route('admin.wali.index') }}">
                <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_auto] gap-3 lg:items-center">
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
                            placeholder="Cari nama wali, email, atau no HP..."
                            autocomplete="off"
                            class="w-full pl-12 pr-12 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] placeholder:text-slate-400"
                        >

                        @if (request('search'))
                            <a
                                href="{{ route('admin.wali.index') }}"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-600 transition-colors"
                                title="Reset pencarian"
                            >
                                <span class="material-symbols-outlined text-[20px]">
                                    close
                                </span>
                            </a>
                        @endif
                    </div>

                    {{-- Tambah Wali --}}
                    <a
                        href="{{ route('admin.wali.create') }}"
                        class="w-full lg:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#004199] text-white rounded-2xl text-sm sm:text-base font-semibold hover:bg-[#00377F] transition-colors shadow-[0_8px_20px_rgba(0,65,153,0.22)] whitespace-nowrap"
                    >
                        <span class="material-symbols-outlined text-[22px]">
                            add
                        </span>
                        Tambah Wali
                    </a>
                </div>
            </form>
        </div>

        {{-- Mobile / Tablet Card List --}}
        <div class="lg:hidden space-y-4">
            @forelse ($walis as $wali)
                <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
                    <div class="bg-[#001847] px-5 py-4 text-white flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-xs uppercase tracking-wider text-white/70 mb-1">
                                Nama Wali
                            </p>

                            <h3 class="text-lg font-bold leading-tight">
                                {{ $wali->name }}
                            </h3>
                        </div>

                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-white">
                                supervisor_account
                            </span>
                        </div>
                    </div>

                    <div class="p-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                    Email
                                </p>

                                <p class="text-sm font-semibold text-[#004199] break-words">
                                    {{ $wali->email }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                    No HP
                                </p>

                                <p class="text-sm font-semibold text-slate-700">
                                    {{ $wali->no_hp ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 border-t border-slate-100 pt-4">
                            <p class="text-xs uppercase tracking-wider text-slate-400 mb-2">
                                Santri Terhubung
                            </p>

                            <div class="space-y-2">
                                @forelse ($wali->santris as $santri)
                                    <div class="rounded-2xl bg-[#F8FAFC] border border-slate-100 px-4 py-3">
                                        <p class="text-sm font-semibold text-[#001847]">
                                            {{ $santri->nama_santri }}
                                        </p>

                                        <p class="text-xs text-slate-500 mt-1">
                                            {{ $santri->kelasMadrasah->nama_kelas ?? '-' }}
                                        </p>
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-500">
                                        Belum terhubung dengan santri.
                                    </p>
                                @endforelse
                            </div>
                        </div>

                        <div class="mt-4 border-t border-slate-100 pt-4">
                            <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                Hubungan
                            </p>

                            <p class="text-sm font-semibold text-slate-700">
                                {{ ucfirst($wali->santris->first()?->pivot?->hubungan ?? '-') }}
                            </p>
                        </div>

                        <div class="flex items-center gap-2 mt-5">
                            <a
                                href="{{ route('admin.wali.edit', $wali) }}"
                                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl bg-[#D9E2FF] text-[#004199] text-sm font-semibold hover:bg-[#C6D6FF] transition-colors"
                            >
                                <span class="material-symbols-outlined text-[18px]">
                                    edit
                                </span>
                                Edit
                            </a>

                            <button
                                type="button"
                                onclick="openDeleteModal(@js(route('admin.wali.destroy', $wali)), @js($wali->name))"
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
                            supervisor_account
                        </span>
                    </div>

                    <p class="text-sm text-slate-500">
                        Belum ada data wali.
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Desktop Table --}}
        <div class="hidden lg:block bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
            <table class="w-full table-fixed text-left border-collapse">
                <colgroup>
                    <col class="w-[6%]">
                    <col class="w-[18%]">
                    <col class="w-[20%]">
                    <col class="w-[12%]">
                    <col class="w-[22%]">
                    <col class="w-[10%]">
                    <col class="w-[12%]">
                </colgroup>

                <thead>
                    <tr class="bg-[#001847] text-white text-sm">
                        <th class="py-4 px-4 font-semibold">No</th>
                        <th class="py-4 px-4 font-semibold">Nama Wali</th>
                        <th class="py-4 px-4 font-semibold">Email</th>
                        <th class="py-4 px-4 font-semibold">No HP</th>
                        <th class="py-4 px-4 font-semibold">Santri Terhubung</th>
                        <th class="py-4 px-4 font-semibold">Hubungan</th>
                        <th class="py-4 px-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="text-sm text-slate-800">
                    @forelse ($walis as $wali)
                        <tr class="hover:bg-[#F8FAFC] transition-colors border-b border-slate-100">
                            <td class="py-4 px-4 font-semibold text-slate-500">
                                {{ $walis->firstItem() + $loop->index }}
                            </td>

                            <td class="py-4 px-4 font-semibold text-[#001847] break-words">
                                {{ $wali->name }}
                            </td>

                            <td class="py-4 px-4 text-[#004199] break-words">
                                {{ $wali->email }}
                            </td>

                            <td class="py-4 px-4 text-slate-500 break-words">
                                {{ $wali->no_hp ?? '-' }}
                            </td>

                            <td class="py-4 px-4 text-slate-500">
                                <div class="space-y-1">
                                    @forelse ($wali->santris as $santri)
                                        <div>
                                            <span class="font-semibold text-slate-700">
                                                {{ $santri->nama_santri }}
                                            </span>

                                            <span class="text-xs text-slate-400">
                                                ({{ $santri->kelasMadrasah->nama_kelas ?? '-' }})
                                            </span>
                                        </div>
                                    @empty
                                        -
                                    @endforelse
                                </div>
                            </td>

                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#D9E2FF] text-[#004199] border border-[#B0C6FF]">
                                    {{ ucfirst($wali->santris->first()?->pivot?->hubungan ?? '-') }}
                                </span>
                            </td>

                            <td class="py-4 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a
                                        href="{{ route('admin.wali.edit', $wali) }}"
                                        class="p-1.5 text-[#004199] hover:bg-[#D9E2FF] rounded-md transition-colors"
                                        title="Edit"
                                    >
                                        <span class="material-symbols-outlined text-[20px]">
                                            edit
                                        </span>
                                    </a>

                                    <button
                                        type="button"
                                        onclick="openDeleteModal(@js(route('admin.wali.destroy', $wali)), @js($wali->name))"
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
                            <td colspan="7" class="py-10 px-6 text-center text-slate-500">
                                Belum ada data wali.
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
                    <span class="font-semibold text-slate-700">{{ $walis->firstItem() ?? 0 }}</span>
                    -
                    <span class="font-semibold text-slate-700">{{ $walis->lastItem() ?? 0 }}</span>
                    dari
                    <span class="font-semibold text-slate-700">{{ $walis->total() }}</span>
                    wali
                </div>

                <div class="flex justify-center lg:justify-end">
                    {{ $walis->links() }}
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
                    Yakin ingin menghapus data wali
                    <span id="deleteWaliName" class="font-bold text-[#001847]"></span>?
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

        const waliFilterForm = document.getElementById('waliFilterForm');
        const searchInput = document.getElementById('searchInput');

        if (searchInput && waliFilterForm) {
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimer);

                searchTimer = setTimeout(function () {
                    waliFilterForm.submit();
                }, 500);
            });
        }

        function openDeleteModal(actionUrl, waliName) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            const name = document.getElementById('deleteWaliName');

            if (!modal || !form || !name) {
                return;
            }

            form.action = actionUrl;
            name.textContent = '"' + waliName + '"';

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