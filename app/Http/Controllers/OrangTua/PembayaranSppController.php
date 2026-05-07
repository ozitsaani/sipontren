<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\DetailPembayaranSpp;
use App\Models\PembayaranSpp;
use App\Models\TagihanSpp;
use Illuminate\Http\Request;

class PembayaranSppController extends Controller
{
    public function index()
    {
        $santri = auth()->user()
            ->santris()
            ->with('kelasMadrasah')
            ->firstOrFail();

        $tagihans = TagihanSpp::where('santri_id', $santri->id)
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        $tagihanBelumLunas = TagihanSpp::where('santri_id', $santri->id)
            ->whereIn('status', ['menunggak', 'belum_dibayar'])
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        $jumlahTagihanBelumLunas = $tagihanBelumLunas->count();

        $totalTagihanBelumLunas = $tagihanBelumLunas->sum('nominal');

        return view('orang-tua.pembayaran-spp.index', compact(
            'santri',
            'tagihans',
            'jumlahTagihanBelumLunas',
            'totalTagihanBelumLunas'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jumlah_bulan' => ['required', 'integer', 'min:1'],
            'bukti_bayar' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $santri = auth()->user()->santris()->firstOrFail();

        $tagihans = TagihanSpp::where('santri_id', $santri->id)
            ->whereIn('status', ['menunggak', 'belum_dibayar'])
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->take($request->jumlah_bulan)
            ->get();

        if ($tagihans->count() < $request->jumlah_bulan) {
            return back()->withErrors('Jumlah bulan pembayaran melebihi tagihan yang tersedia.');
        }

        $totalBayar = $tagihans->sum('nominal');

        $path = $request->file('bukti_bayar')->store('bukti-pembayaran', 'public');

        $pembayaran = PembayaranSpp::create([
            'santri_id' => $santri->id,
            'total_bayar' => $totalBayar,
            'jumlah_bulan' => $request->jumlah_bulan,
            'bukti_bayar' => $path,
            'status_verifikasi' => 'menunggu',
            'tanggal_upload' => now(),
        ]);

        foreach ($tagihans as $tagihan) {
            DetailPembayaranSpp::create([
                'pembayaran_spp_id' => $pembayaran->id,
                'tagihan_spp_id' => $tagihan->id,
                'nominal' => $tagihan->nominal,
            ]);

            $tagihan->update([
                'status' => 'menunggu_verifikasi',
            ]);
        }

        return redirect()
            ->route('orang-tua.riwayat-pembayaran.index')
            ->with('success', 'Bukti pembayaran berhasil dikirim. Pembayaran menunggu verifikasi admin.');
    }
}