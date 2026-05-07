<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;

class RekapAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $santri = auth()->user()
            ->santris()
            ->with('kelasMadrasah')
            ->firstOrFail();

        $query = Absensi::where('santri_id', $santri->id);

        if ($request->filled('bulan')) {
            $tanggal = explode('-', $request->bulan);

            if (count($tanggal) === 2) {
                $query->whereYear('tanggal', $tanggal[0])
                    ->whereMonth('tanggal', $tanggal[1]);
            }
        }

        if ($request->filled('waktu_pengajian')) {
            $query->where('waktu_pengajian', $request->waktu_pengajian);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $summaryQuery = clone $query;

        $totalHadir = (clone $summaryQuery)->where('status', 'hadir')->count();
        $totalIzin = (clone $summaryQuery)->where('status', 'izin')->count();
        $totalSakit = (clone $summaryQuery)->where('status', 'sakit')->count();
        $totalAlfa = (clone $summaryQuery)->where('status', 'alfa')->count();

        $absensis = $query
            ->orderByDesc('tanggal')
            ->orderBy('waktu_pengajian')
            ->paginate(10)
            ->withQueryString();

        return view('orang-tua.rekap-absensi.index', compact(
            'santri',
            'absensis',
            'totalHadir',
            'totalIzin',
            'totalSakit',
            'totalAlfa'
        ));
    }
}