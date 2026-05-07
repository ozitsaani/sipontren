@extends('layouts.admin')

@section('title', 'Kelas Madrasah - SIPontren')
@section('page-title', 'Akademik')

@section('content')
    <div class="space-y-5 sm:space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-[#191C1E] leading-tight">
                    Kelas Madrasah
                </h2>

                <p class="text-sm sm:text-base text-slate-500 mt-1 leading-relaxed">
                    Kelola data kelas madrasah yang digunakan untuk pengelompokan santri.
                </p>
            </div>

            <a
                href="{{ route('admin.kelas-madrasah.create') }}"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#004199] text-white rounded-2xl text-sm sm:text-base font-semibold hover:bg-[#00377F] transition-colors shadow-[0_8px_20px_rgba(0,65,153,0.22)]"
            >
                <span class="material-symbols-outlined text-[22px]">
                    add
                </span>
                Tambah Kelas
            </a>
        </div>

        {{-- Summary Card --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
            <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-slate-500">
                            Total Kelas
                        </p>

                        <p class="text-3xl font-bold text-[#191C1E] mt-2">
                            {{ number_format($kelasMadrasahs->total()) }}
                        </p>
                    </div>

                    <div class="w-12 h-12 rounded-full bg-[#D9E2FF] text-[#004199] flex items-center justify-center">
                        <span class="material-symbols-outlined">
                            school
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-slate-500">
                            Total Santri
                        </p>

                        <p class="text-3xl font-bold text-[#191C1E] mt-2">
                            {{ number_format($kelasMadrasahs->sum('santris_count')) }}
                        </p>
                    </div>

                    <div class="w-12 h-12 rounded-full bg-[#DFF5A6] text-[#4A5F00] flex items-center justify-center">
                        <span class="material-symbols-outlined">
                            group
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-slate-500">
                            Halaman Ini
                        </p>

                        <p class="text-3xl font-bold text-[#191C1E] mt-2">
                            {{ number_format($kelasMadrasahs->count()) }}
                        </p>
                    </div>

                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center">
                        <span class="material-symbols-outlined">
                            list_alt
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mobile Card List --}}
        <div class="md:hidden space-y-4">
            @forelse ($kelasMadrasahs as $kelas)
                <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
                    <div class="bg-[#001847] px-5 py-4 text-white flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-white/70 mb-1">
                                Nama Kelas
                            </p>

                            <h3 class="text-lg font-bold leading-tight">
                                {{ $kelas->nama_kelas }}
                            </h3>
                        </div>

                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-white">
                                school
                            </span>
                        </div>
                    </div>

                    <div class="p-5 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                    No
                                </p>

                                <p class="text-sm font-semibold text-slate-700">
                                    {{ $kelasMadrasahs->firstItem() + $loop->index }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                    Jumlah Santri
                                </p>

                                <p class="text-sm font-semibold text-[#004199]">
                                    {{ number_format($kelas->santris_count) }} santri
                                </p>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-4">
                            <p class="text-xs uppercase tracking-wider text-slate-400 mb-1">
                                Keterangan
                            </p>

                            <p class="text-sm text-slate-600 leading-relaxed">
                                {{ $kelas->keterangan ?? '-' }}
                            </p>
                        </div>

                        <div class="flex items-center gap-2 pt-2">
                            <a
                                href="{{ route('admin.kelas-madrasah.edit', $kelas) }}"
                                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl bg-[#D9E2FF] text-[#004199] text-sm font-semibold hover:bg-[#C6D6FF] transition-colors"
                            >
                                <span class="material-symbols-outlined text-[18px]">
                                    edit
                                </span>
                                Edit
                            </a>

                            <button
                                type="button"
                                onclick="openDeleteModal(@js(route('admin.kelas-madrasah.destroy', $kelas)), @js($kelas->nama_kelas))"
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
                            school_off
                        </span>
                    </div>

                    <p class="text-sm text-slate-500">
                        Belum ada data kelas madrasah.
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Desktop Table --}}
        <div class="hidden md:block bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
            <div class="bg-[#001847] px-5 sm:px-6 py-4 text-white flex items-center justify-between">
                <h3 class="text-base sm:text-xl font-semibold flex items-center gap-2">
                    <span class="material-symbols-outlined text-white text-[20px]">
                        table_view
                    </span>
                    Daftar Kelas Madrasah
                </h3>

                <span class="text-xs sm:text-sm text-white/80">
                    Total: {{ number_format($kelasMadrasahs->total()) }} kelas
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#001847] text-white text-sm border-t border-white/10">
                            <th class="py-4 px-6 font-semibold w-20">No</th>
                            <th class="py-4 px-6 font-semibold">Nama Kelas Madrasah</th>
                            <th class="py-4 px-6 font-semibold">Keterangan</th>
                            <th class="py-4 px-6 font-semibold text-center">Jumlah Santri</th>
                            <th class="py-4 px-6 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="text-sm text-slate-800">
                        @forelse ($kelasMadrasahs as $kelas)
                            <tr class="hover:bg-[#F8FAFC] transition-colors border-b border-slate-100">
                                <td class="py-4 px-6 font-semibold text-slate-500">
                                    {{ $kelasMadrasahs->firstItem() + $loop->index }}
                                </td>

                                <td class="py-4 px-6 font-semibold text-[#001847] min-w-[180px]">
                                    {{ $kelas->nama_kelas }}
                                </td>

                                <td class="py-4 px-6 text-slate-500 min-w-[240px]">
                                    {{ $kelas->keterangan ?? '-' }}
                                </td>

                                <td class="py-4 px-6 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#D9E2FF] text-[#004199] border border-[#B0C6FF]">
                                        {{ number_format($kelas->santris_count) }} santri
                                    </span>
                                </td>

                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            href="{{ route('admin.kelas-madrasah.edit', $kelas) }}"
                                            class="p-1.5 text-[#004199] hover:bg-[#D9E2FF] rounded-md transition-colors"
                                            title="Edit"
                                        >
                                            <span class="material-symbols-outlined text-[20px]">
                                                edit
                                            </span>
                                        </a>

                                        <button
                                            type="button"
                                            onclick="openDeleteModal(@js(route('admin.kelas-madrasah.destroy', $kelas)), @js($kelas->nama_kelas))"
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
                                <td colspan="5" class="py-10 px-6 text-center text-slate-500">
                                    Belum ada data kelas madrasah.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] p-5">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="text-sm text-slate-500 text-center lg:text-left">
                    Menampilkan
                    <span class="font-semibold text-slate-700">{{ $kelasMadrasahs->firstItem() ?? 0 }}</span>
                    -
                    <span class="font-semibold text-slate-700">{{ $kelasMadrasahs->lastItem() ?? 0 }}</span>
                    dari
                    <span class="font-semibold text-slate-700">{{ $kelasMadrasahs->total() }}</span>
                    kelas
                </div>

                <div class="flex justify-center lg:justify-end">
                    {{ $kelasMadrasahs->links() }}
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
                    Yakin ingin menghapus kelas
                    <span id="deleteKelasName" class="font-bold text-[#001847]"></span>?
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
        function openDeleteModal(actionUrl, kelasName) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            const name = document.getElementById('deleteKelasName');

            if (!modal || !form || !name) {
                return;
            }

            form.action = actionUrl;
            name.textContent = '"' + kelasName + '"';

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