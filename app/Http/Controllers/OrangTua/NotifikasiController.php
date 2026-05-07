<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Notifikasi::with('santri')
            ->where('user_id', auth()->id())
            ->latest();

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('status')) {
            if ($request->status === 'belum_dibaca') {
                $query->where('dibaca', false);
            }

            if ($request->status === 'sudah_dibaca') {
                $query->where('dibaca', true);
            }
        }

        $notifikasis = $query->paginate(10)->withQueryString();

        $jumlahBelumDibaca = Notifikasi::where('user_id', auth()->id())
            ->where('dibaca', false)
            ->count();

        return view('orang-tua.notifikasi.index', compact(
            'notifikasis',
            'jumlahBelumDibaca'
        ));
    }

    public function tandaiDibaca(Notifikasi $notifikasi)
    {
        if ($notifikasi->user_id !== auth()->id()) {
            abort(403);
        }

        $notifikasi->update([
            'dibaca' => true,
        ]);

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function tandaiSemuaDibaca()
    {
        Notifikasi::where('user_id', auth()->id())
            ->where('dibaca', false)
            ->update([
                'dibaca' => true,
            ]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}