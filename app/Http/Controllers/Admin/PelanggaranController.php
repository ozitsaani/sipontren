<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KelasMadrasah;
use App\Models\Notifikasi;
use App\Models\Pelanggaran;
use App\Models\Santri;
use Illuminate\Http\Request;

class PelanggaranController extends Controller
{
    public function index(Request $request)
    {
        $kelasMadrasahs = KelasMadrasah::orderBy('nama_kelas')->get();

        $query = Pelanggaran::with(['santri.kelasMadrasah', 'admin'])->latest('tanggal');

        if ($request->filled('search')) {
            $query->whereHas('santri', function ($q) use ($request) {
                $q->where('nama_santri', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kelas_madrasah_id')) {
            $query->whereHas('santri', function ($q) use ($request) {
                $q->where('kelas_madrasah_id', $request->kelas_madrasah_id);
            });
        }

        if ($request->filled('tingkat_pelanggaran')) {
            $query->where('tingkat_pelanggaran', $request->tingkat_pelanggaran);
        }

        if ($request->filled('bulan')) {
            $tanggal = explode('-', $request->bulan);

            if (count($tanggal) === 2) {
                $query->whereYear('tanggal', $tanggal[0])
                    ->whereMonth('tanggal', $tanggal[1]);
            }
        }

        $pelanggarans = $query->paginate(10)->withQueryString();

        return view('admin.pelanggaran.index', compact(
            'pelanggarans',
            'kelasMadrasahs'
        ));
    }

    public function create()
    {
        $santris = Santri::with('kelasMadrasah')
            ->where('status', 'aktif')
            ->orderBy('nama_santri')
            ->get();

        return view('admin.pelanggaran.create', compact('santris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'santri_id' => ['required', 'exists:santris,id'],
            'tanggal' => ['required', 'date'],
            'jenis_pelanggaran' => ['required', 'string', 'max:255'],
            'tingkat_pelanggaran' => ['required', 'in:ringan,sedang,berat'],
            'catatan' => ['nullable', 'string'],
            'tindak_lanjut' => ['nullable', 'string'],
        ]);

        $pelanggaran = Pelanggaran::create([
            'santri_id' => $request->santri_id,
            'tanggal' => $request->tanggal,
            'jenis_pelanggaran' => $request->jenis_pelanggaran,
            'tingkat_pelanggaran' => $request->tingkat_pelanggaran,
            'catatan' => $request->catatan,
            'tindak_lanjut' => $request->tindak_lanjut,
            'diinput_oleh' => auth()->id(),
        ]);

        $santri = Santri::with('walis')->findOrFail($request->santri_id);

        foreach ($santri->walis as $wali) {
            Notifikasi::create([
                'user_id' => $wali->id,
                'santri_id' => $santri->id,
                'jenis' => 'pelanggaran',
                'judul' => 'Catatan Pelanggaran Santri',
                'isi' => $santri->nama_santri . ' mendapatkan catatan pelanggaran '
                    . strtoupper($request->tingkat_pelanggaran)
                    . ': ' . $request->jenis_pelanggaran . '.',
            ]);
        }

        return redirect()
            ->route('admin.pelanggaran.index')
            ->with('success', 'Data pelanggaran berhasil disimpan.');
    }

    public function edit(Pelanggaran $pelanggaran)
    {
        $santris = Santri::with('kelasMadrasah')
            ->where('status', 'aktif')
            ->orderBy('nama_santri')
            ->get();

        return view('admin.pelanggaran.edit', compact('pelanggaran', 'santris'));
    }

    public function update(Request $request, Pelanggaran $pelanggaran)
    {
        $request->validate([
            'santri_id' => ['required', 'exists:santris,id'],
            'tanggal' => ['required', 'date'],
            'jenis_pelanggaran' => ['required', 'string', 'max:255'],
            'tingkat_pelanggaran' => ['required', 'in:ringan,sedang,berat'],
            'catatan' => ['nullable', 'string'],
            'tindak_lanjut' => ['nullable', 'string'],
        ]);

        $pelanggaran->update([
            'santri_id' => $request->santri_id,
            'tanggal' => $request->tanggal,
            'jenis_pelanggaran' => $request->jenis_pelanggaran,
            'tingkat_pelanggaran' => $request->tingkat_pelanggaran,
            'catatan' => $request->catatan,
            'tindak_lanjut' => $request->tindak_lanjut,
        ]);

        return redirect()
            ->route('admin.pelanggaran.index')
            ->with('success', 'Data pelanggaran berhasil diperbarui.');
    }

    public function destroy(Pelanggaran $pelanggaran)
    {
        $pelanggaran->delete();

        return redirect()
            ->route('admin.pelanggaran.index')
            ->with('success', 'Data pelanggaran berhasil dihapus.');
    }
}