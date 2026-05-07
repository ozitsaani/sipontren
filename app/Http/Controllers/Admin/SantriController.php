<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KelasMadrasah;
use App\Models\Santri;
use Illuminate\Http\Request;

class SantriController extends Controller
{
    public function index(Request $request)
{
    $query = Santri::with(['kelasMadrasah', 'walis'])
        ->latest();

    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('nama_santri', 'like', '%' . $request->search . '%')
              ->orWhere('nis', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->filled('kelas_madrasah_id')) {
        $query->where('kelas_madrasah_id', $request->kelas_madrasah_id);
    }

    $santris = $query->paginate(10)->withQueryString();

    $kelasMadrasahs = KelasMadrasah::orderBy('nama_kelas')->get();

    return view('admin.santri.index', compact('santris', 'kelasMadrasahs'));
}

    public function create()
    {
        $kelasMadrasahs = KelasMadrasah::orderBy('nama_kelas')->get();

        return view('admin.santri.create', compact('kelasMadrasahs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => ['required', 'string', 'max:255', 'unique:santris,nis'],
            'nama_santri' => ['required', 'string', 'max:255'],
            'kelas_madrasah_id' => ['required', 'exists:kelas_madrasahs,id'],
            'asrama' => ['nullable', 'string', 'max:255'],
            'kamar' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        Santri::create([
            'nis' => $request->nis,
            'nama_santri' => $request->nama_santri,
            'kelas_madrasah_id' => $request->kelas_madrasah_id,
            'asrama' => $request->asrama,
            'kamar' => $request->kamar,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.santri.index')
            ->with('success', 'Data santri berhasil ditambahkan.');
    }

    public function edit(Santri $santri)
    {
        $kelasMadrasahs = KelasMadrasah::orderBy('nama_kelas')->get();

        return view('admin.santri.edit', compact('santri', 'kelasMadrasahs'));
    }

    public function update(Request $request, Santri $santri)
    {
        $request->validate([
            'nis' => ['required', 'string', 'max:255', 'unique:santris,nis,' . $santri->id],
            'nama_santri' => ['required', 'string', 'max:255'],
            'kelas_madrasah_id' => ['required', 'exists:kelas_madrasahs,id'],
            'asrama' => ['nullable', 'string', 'max:255'],
            'kamar' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $santri->update([
            'nis' => $request->nis,
            'nama_santri' => $request->nama_santri,
            'kelas_madrasah_id' => $request->kelas_madrasah_id,
            'asrama' => $request->asrama,
            'kamar' => $request->kamar,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.santri.index')
            ->with('success', 'Data santri berhasil diperbarui.');
    }

    public function destroy(Santri $santri)
    {
        $santri->delete();

        return redirect()
            ->route('admin.santri.index')
            ->with('success', 'Data santri berhasil dihapus.');
    }
}