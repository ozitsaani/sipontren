<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Models\PembayaranSpp;
use Illuminate\Http\Request;

class VerifikasiPembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = PembayaranSpp::with([
            'santri.kelasMadrasah',
            'santri.walis',
            'detailPembayaranSpps.tagihanSpp',
        ])->latest();

        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('santri', function ($q) use ($request) {
                $q->where('nama_santri', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%');
            });
        }

        $pembayarans = $query->paginate(10)->withQueryString();

        return view('admin.verifikasi-pembayaran.index', compact('pembayarans'));
    }

    public function terima(PembayaranSpp $pembayaran)
    {
        if ($pembayaran->status_verifikasi !== 'menunggu') {
            return back()->withErrors('Pembayaran ini sudah diverifikasi sebelumnya.');
        }

        $pembayaran->load([
            'santri.walis',
            'detailPembayaranSpps.tagihanSpp',
        ]);

        $pembayaran->update([
            'status_verifikasi' => 'diterima',
            'diverifikasi_oleh' => auth()->id(),
            'tanggal_verifikasi' => now(),
            'catatan_admin' => null,
        ]);

        foreach ($pembayaran->detailPembayaranSpps as $detail) {
            $detail->tagihanSpp->update([
                'status' => 'lunas',
            ]);
        }

        foreach ($pembayaran->santri->walis as $wali) {
            Notifikasi::create([
                'user_id' => $wali->id,
                'santri_id' => $pembayaran->santri_id,
                'jenis' => 'pembayaran',
                'judul' => 'Pembayaran SPP Diterima',
                'isi' => 'Pembayaran SPP atas nama ' . $pembayaran->santri->nama_santri . ' telah diterima dan dinyatakan lunas.',
            ]);
        }

        return redirect()
            ->route('admin.verifikasi-pembayaran.index')
            ->with('success', 'Pembayaran berhasil diterima.');
    }

    public function tolak(Request $request, PembayaranSpp $pembayaran)
    {
        if ($pembayaran->status_verifikasi !== 'menunggu') {
            return back()->withErrors('Pembayaran ini sudah diverifikasi sebelumnya.');
        }

        $request->validate([
            'catatan_admin' => ['required', 'string', 'max:1000'],
        ]);

        $pembayaran->load([
            'santri.walis',
            'detailPembayaranSpps.tagihanSpp',
        ]);

        $pembayaran->update([
            'status_verifikasi' => 'ditolak',
            'catatan_admin' => $request->catatan_admin,
            'diverifikasi_oleh' => auth()->id(),
            'tanggal_verifikasi' => now(),
        ]);

        foreach ($pembayaran->detailPembayaranSpps as $detail) {
            $tagihan = $detail->tagihanSpp;

            $statusTagihan = (
                $tagihan->tahun < now()->year ||
                ($tagihan->tahun == now()->year && $tagihan->bulan < now()->month)
            )
                ? 'menunggak'
                : 'belum_dibayar';

            $tagihan->update([
                'status' => $statusTagihan,
            ]);
        }

        foreach ($pembayaran->santri->walis as $wali) {
            Notifikasi::create([
                'user_id' => $wali->id,
                'santri_id' => $pembayaran->santri_id,
                'jenis' => 'pembayaran',
                'judul' => 'Pembayaran SPP Ditolak',
                'isi' => 'Pembayaran SPP atas nama ' . $pembayaran->santri->nama_santri . ' ditolak. Catatan admin: ' . $request->catatan_admin,
            ]);
        }

        return redirect()
            ->route('admin.verifikasi-pembayaran.index')
            ->with('success', 'Pembayaran berhasil ditolak.');
    }
}