<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KelasMadrasah;
use App\Models\PengaturanSpp;
use App\Models\Santri;
use App\Models\TagihanSpp;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TagihanSppController extends Controller
{
    public function index(Request $request)
    {
        $kelasMadrasahs = KelasMadrasah::orderBy('nama_kelas')->get();

        $query = TagihanSpp::with(['santri.kelasMadrasah']);

        if ($request->filled('kelas_madrasah_id')) {
            $query->whereHas('santri', function ($q) use ($request) {
                $q->where('kelas_madrasah_id', $request->kelas_madrasah_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('bulan')) {
            $tanggal = explode('-', $request->bulan);

            if (count($tanggal) === 2) {
                $query->where('tahun', $tanggal[0])
                    ->where('bulan', $tanggal[1]);
            }
        }

        if ($request->filled('search')) {
            $query->whereHas('santri', function ($q) use ($request) {
                $q->where('nama_santri', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%');
            });
        }

        $totalTagihan = (clone $query)->count();
        $totalLunas = (clone $query)->where('status', 'lunas')->count();
        $totalMenunggak = (clone $query)->where('status', 'menunggak')->count();
        $totalMenungguVerifikasi = (clone $query)->where('status', 'menunggu_verifikasi')->count();

        $tagihans = $query
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->paginate(10)
            ->withQueryString();

        return view('admin.tagihan-spp.index', compact(
            'kelasMadrasahs',
            'tagihans',
            'totalTagihan',
            'totalLunas',
            'totalMenunggak',
            'totalMenungguVerifikasi'
        ));
    }

    public function generate()
    {
        $pengaturan = PengaturanSpp::where('status', 'aktif')->first();

        if (! $pengaturan) {
            return redirect()
                ->route('admin.pengaturan-spp.index')
                ->withErrors('Pengaturan SPP aktif belum tersedia.');
        }

        $sekarang = now();

        // Ubah tagihan bulan sebelumnya yang belum lunas menjadi menunggak
        TagihanSpp::whereIn('status', ['belum_dibayar', 'ditolak'])
            ->where(function ($query) use ($sekarang) {
                $query->where('tahun', '<', $sekarang->year)
                    ->orWhere(function ($q) use ($sekarang) {
                        $q->where('tahun', $sekarang->year)
                          ->where('bulan', '<', $sekarang->month);
                    });
            })
            ->update(['status' => 'menunggak']);

        $santris = Santri::where('status', 'aktif')->get();

        foreach ($santris as $santri) {
            TagihanSpp::firstOrCreate(
                [
                    'santri_id' => $santri->id,
                    'bulan' => $sekarang->month,
                    'tahun' => $sekarang->year,
                ],
                [
                    'nominal' => $pengaturan->nominal_spp,
                    'jatuh_tempo' => Carbon::create(
                        $sekarang->year,
                        $sekarang->month,
                        $pengaturan->tanggal_jatuh_tempo
                    ),
                    'status' => 'belum_dibayar',
                ]
            );
        }

        return redirect()
            ->route('admin.tagihan-spp.index')
            ->with('success', 'Tagihan SPP bulan ini berhasil digenerate.');
    }
}