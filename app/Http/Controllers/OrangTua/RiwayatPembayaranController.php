<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSpp;
use Illuminate\Http\Request;

class RiwayatPembayaranController extends Controller
{
    public function index(Request $request)
    {
        $santri = auth()->user()
            ->santris()
            ->with('kelasMadrasah')
            ->firstOrFail();

        $query = PembayaranSpp::with('detailPembayaranSpps.tagihanSpp')
            ->where('santri_id', $santri->id)
            ->latest();

        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }

        $pembayarans = $query->paginate(10)->withQueryString();

        return view('orang-tua.riwayat-pembayaran.index', compact(
            'santri',
            'pembayarans'
        ));
    }
}