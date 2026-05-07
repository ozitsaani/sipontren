<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengaturanSpp;
use Illuminate\Http\Request;

class PengaturanSppController extends Controller
{
    public function index()
    {
        $pengaturan = PengaturanSpp::where('status', 'aktif')->first();

        return view('admin.pengaturan-spp.index', compact('pengaturan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nominal_spp' => ['required', 'integer', 'min:1000'],
            'tanggal_tagihan' => ['required', 'integer', 'min:1', 'max:28'],
            'tanggal_jatuh_tempo' => ['required', 'integer', 'min:1', 'max:28'],
        ]);

        PengaturanSpp::where('status', 'aktif')->update([
            'status' => 'nonaktif',
        ]);

        PengaturanSpp::create([
            'nominal_spp' => $request->nominal_spp,
            'tanggal_tagihan' => $request->tanggal_tagihan,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            'status' => 'aktif',
        ]);

        return redirect()
            ->route('admin.pengaturan-spp.index')
            ->with('success', 'Pengaturan SPP berhasil disimpan.');
    }
}