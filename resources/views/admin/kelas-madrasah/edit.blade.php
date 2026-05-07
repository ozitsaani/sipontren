@extends('layouts.admin')

@section('title', 'Edit Kelas Madrasah - SIPontren')
@section('page-title', 'Edit Kelas Madrasah')

@section('content')
    <div class="space-y-5 sm:space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-[#191C1E] leading-tight">
                    Edit Kelas Madrasah
                </h2>

                <p class="text-sm sm:text-base text-slate-500 mt-1 leading-relaxed">
                    Perbarui nama kelas madrasah dan keterangan yang digunakan untuk pengelompokan santri.
                </p>
            </div>

            <a
                href="{{ route('admin.kelas-madrasah.index') }}"
                class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-white border border-slate-200 text-slate-700 rounded-2xl text-sm font-semibold hover:bg-slate-50 transition-colors shadow-[0_10px_28px_rgba(15,23,42,0.07)]"
            >
                <span class="material-symbols-outlined text-[18px]">
                    arrow_back
                </span>
                Kembali
            </a>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-[28px] border border-slate-200 shadow-[0_10px_28px_rgba(15,23,42,0.07)] overflow-hidden">
            <div class="bg-[#001847] px-5 sm:px-6 py-4 text-white">
                <h3 class="text-base sm:text-xl font-semibold flex items-center gap-2">
                    <span class="material-symbols-outlined text-white text-[20px]">
                        edit
                    </span>
                    Form Edit Kelas
                </h3>
            </div>

            <form action="{{ route('admin.kelas-madrasah.update', $kelasMadrasah) }}" method="POST" class="p-5 sm:p-6 space-y-6">
                @csrf
                @method('PUT')

                {{-- Info Box --}}
                <div class="bg-[#F8FAFC] border border-slate-200 rounded-3xl p-5 flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-[#001847] text-white flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[28px]">
                            school
                        </span>
                    </div>

                    <div class="min-w-0">
                        <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">
                            Sedang diedit
                        </p>

                        <h4 class="text-lg font-bold text-[#001847] leading-tight">
                            {{ $kelasMadrasah->nama_kelas }}
                        </h4>

                        <p class="text-sm text-slate-500 mt-1">
                            Jumlah santri: {{ number_format($kelasMadrasah->santris_count ?? $kelasMadrasah->santris()->count()) }} santri
                        </p>
                    </div>
                </div>

                {{-- Input --}}
                <div class="grid grid-cols-1 gap-5">
                    <div>
                        <label for="nama_kelas" class="block text-sm font-semibold text-slate-700 mb-2">
                            Nama Kelas Madrasah
                        </label>

                        <input
                            id="nama_kelas"
                            type="text"
                            name="nama_kelas"
                            value="{{ old('nama_kelas', $kelasMadrasah->nama_kelas) }}"
                            placeholder="Contoh: Kelas 1 MKHS"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] placeholder:text-slate-400 @error('nama_kelas') border-red-400 focus:border-red-500 focus:ring-red-500 @enderror"
                        >

                        @error('nama_kelas')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="keterangan" class="block text-sm font-semibold text-slate-700 mb-2">
                            Keterangan
                        </label>

                        <textarea
                            id="keterangan"
                            name="keterangan"
                            rows="4"
                            placeholder="Contoh: Kelas awal tingkat madrasah pesantren"
                            class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-2xl text-sm sm:text-base text-slate-800 focus:outline-none focus:border-[#004199] focus:ring-1 focus:ring-[#004199] placeholder:text-slate-400 resize-none @error('keterangan') border-red-400 focus:border-red-500 focus:ring-red-500 @enderror"
                        >{{ old('keterangan', $kelasMadrasah->keterangan) }}</textarea>

                        @error('keterangan')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-4 border-t border-slate-100">
                    <a
                        href="{{ route('admin.kelas-madrasah.index') }}"
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
                        Update Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection