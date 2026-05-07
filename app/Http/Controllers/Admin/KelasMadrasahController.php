<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KelasMadrasah;
use Illuminate\Http\Request;

class KelasMadrasahController extends Controller
{
    public function index()
{
    $kelasMadrasahs = KelasMadrasah::withCount('santris')
        ->latest()
        ->paginate(10);

    return view('admin.kelas-madrasah.index', compact('kelasMadrasahs'));
}

    public function create()
    {
        return view('admin.kelas-madrasah.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ]);

        KelasMadrasah::create($request->only('nama_kelas', 'keterangan'));

        return redirect()
            ->route('admin.kelas-madrasah.index')
            ->with('success', 'Kelas madrasah berhasil ditambahkan.');
    }

    public function edit(KelasMadrasah $kelasMadrasah)
    {
        return view('admin.kelas-madrasah.edit', compact('kelasMadrasah'));
    }

    public function update(Request $request, KelasMadrasah $kelasMadrasah)
    {
        $request->validate([
            'nama_kelas' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $kelasMadrasah->update($request->only('nama_kelas', 'keterangan'));

        return redirect()
            ->route('admin.kelas-madrasah.index')
            ->with('success', 'Kelas madrasah berhasil diperbarui.');
    }

    public function destroy(KelasMadrasah $kelasMadrasah)
    {
        $kelasMadrasah->delete();

        return redirect()
            ->route('admin.kelas-madrasah.index')
            ->with('success', 'Kelas madrasah berhasil dihapus.');
    }
}