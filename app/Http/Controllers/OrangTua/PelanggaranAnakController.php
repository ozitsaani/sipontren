<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Pelanggaran;
use Illuminate\Http\Request;

class PelanggaranAnakController extends Controller
{
    public function index(Request $request)
    {
        $santri = auth()->user()
            ->santris()
            ->with('kelasMadrasah')
            ->firstOrFail();

        $query = Pelanggaran::where('santri_id', $santri->id)
            ->latest('tanggal');

        if ($request->filled('bulan')) {
            $tanggal = explode('-', $request->bulan);

            if (count($tanggal) === 2) {
                $query->whereYear('tanggal', $tanggal[0])
                    ->whereMonth('tanggal', $tanggal[1]);
            }
        }

        if ($request->filled('tingkat_pelanggaran')) {
            $query->where('tingkat_pelanggaran', $request->tingkat_pelanggaran);
        }

        $totalPelanggaran = (clone $query)->count();
        $totalRingan = (clone $query)->where('tingkat_pelanggaran', 'ringan')->count();
        $totalSedang = (clone $query)->where('tingkat_pelanggaran', 'sedang')->count();
        $totalBerat = (clone $query)->where('tingkat_pelanggaran', 'berat')->count();

        $pelanggarans = $query->paginate(10)->withQueryString();

        return view('orang-tua.pelanggaran-anak.index', compact(
            'santri',
            'pelanggarans',
            'totalPelanggaran',
            'totalRingan',
            'totalSedang',
            'totalBerat'
        ));
    }
}