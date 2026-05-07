<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Notifikasi::with(['user', 'santri'])
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

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                    ->orWhere('isi', 'like', '%' . $request->search . '%')
                    ->orWhereHas('santri', function ($q2) use ($request) {
                        $q2->where('nama_santri', 'like', '%' . $request->search . '%')
                           ->orWhere('nis', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('user', function ($q3) use ($request) {
                        $q3->where('name', 'like', '%' . $request->search . '%')
                           ->orWhere('email', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $notifikasis = $query->paginate(10)->withQueryString();

        return view('admin.notifikasi.index', compact('notifikasis'));
    }
}