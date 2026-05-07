<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\KelasMadrasah;
use Illuminate\Http\Request;

class RekapAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $kelasMadrasahs = KelasMadrasah::orderBy('nama_kelas')->get();

        $query = Absensi::with(['santri.kelasMadrasah']);

        if ($request->filled('bulan')) {
            $tanggal = explode('-', $request->bulan);

            if (count($tanggal) === 2) {
                $query->whereYear('tanggal', $tanggal[0])
                    ->whereMonth('tanggal', $tanggal[1]);
            }
        }

        if ($request->filled('kelas_madrasah_id')) {
            $query->whereHas('santri', function ($q) use ($request) {
                $q->where('kelas_madrasah_id', $request->kelas_madrasah_id);
            });
        }

        if ($request->filled('waktu_pengajian')) {
            $query->where('waktu_pengajian', $request->waktu_pengajian);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('santri', function ($q) use ($request) {
                $q->where('nama_santri', 'like', '%' . $request->search . '%')
                    ->orWhere('nis', 'like', '%' . $request->search . '%');
            });
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

        return view('admin.rekap-absensi.index', compact(
            'kelasMadrasahs',
            'absensis',
            'totalHadir',
            'totalIzin',
            'totalSakit',
            'totalAlfa'
        ));
    }
}