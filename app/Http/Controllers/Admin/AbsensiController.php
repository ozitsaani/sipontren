<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\KelasMadrasah;
use App\Models\Notifikasi;
use App\Models\Santri;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $waktuLabels = [
            'bada_shubuh' => "Ba'da Shubuh",
            'bada_dzuhur' => "Ba'da Dzuhur",
            'bada_ashar' => "Ba'da Ashar",
            'bada_maghrib' => "Ba'da Maghrib",
            'bada_isya' => "Ba'da Isya",
            'pengajian_malam' => 'Pengajian Malam',
        ];

        $kelasMadrasahs = KelasMadrasah::orderBy('nama_kelas')->get();

        $filterLengkap = $request->filled('tanggal')
            && $request->filled('waktu_pengajian')
            && $request->filled('kelas_madrasah_id');

        $santris = collect();
        $absensiTersimpan = collect();

        if ($filterLengkap) {
            $santris = Santri::with('kelasMadrasah')
                ->where('kelas_madrasah_id', $request->kelas_madrasah_id)
                ->where('status', 'aktif')
                ->orderBy('nama_santri')
                ->get();

            if ($santris->isNotEmpty()) {
                $absensiTersimpan = Absensi::whereIn('santri_id', $santris->pluck('id'))
                    ->where('tanggal', $request->tanggal)
                    ->where('waktu_pengajian', $request->waktu_pengajian)
                    ->get()
                    ->keyBy('santri_id');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Alias variable
        |--------------------------------------------------------------------------
        | Beberapa versi Blade yang sudah kita buat memakai nama $existingAbsensis,
        | sedangkan controller lama kamu memakai $absensiTersimpan.
        | Jadi dua-duanya dikirim agar aman.
        */
        $existingAbsensis = $absensiTersimpan;

        return view('admin.absensi.index', compact(
            'waktuLabels',
            'kelasMadrasahs',
            'santris',
            'absensiTersimpan',
            'existingAbsensis',
            'filterLengkap'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'waktu_pengajian' => ['required', 'in:bada_shubuh,bada_dzuhur,bada_ashar,bada_maghrib,bada_isya,pengajian_malam'],
            'kelas_madrasah_id' => ['required', 'exists:kelas_madrasahs,id'],
            'absensi' => ['required', 'array'],
            'absensi.*.status' => ['required', 'in:hadir,izin,sakit,alfa'],
            'absensi.*.catatan' => ['nullable', 'string', 'max:255'],
        ], [
            'tanggal.required' => 'Tanggal pengajian wajib dipilih.',
            'tanggal.date' => 'Format tanggal pengajian tidak valid.',

            'waktu_pengajian.required' => 'Waktu pengajian wajib dipilih.',
            'waktu_pengajian.in' => 'Waktu pengajian tidak valid.',

            'kelas_madrasah_id.required' => 'Kelas madrasah wajib dipilih.',
            'kelas_madrasah_id.exists' => 'Kelas madrasah tidak ditemukan.',

            'absensi.required' => 'Data absensi santri wajib diisi.',
            'absensi.array' => 'Format data absensi tidak valid.',

            'absensi.*.status.required' => 'Status kehadiran wajib dipilih.',
            'absensi.*.status.in' => 'Status kehadiran tidak valid.',

            'absensi.*.catatan.string' => 'Catatan harus berupa teks.',
            'absensi.*.catatan.max' => 'Catatan maksimal 255 karakter.',
        ]);

        foreach ($validated['absensi'] as $santriId => $data) {
            $absensiSebelumnya = Absensi::where('santri_id', $santriId)
                ->where('tanggal', $validated['tanggal'])
                ->where('waktu_pengajian', $validated['waktu_pengajian'])
                ->first();

            Absensi::updateOrCreate(
                [
                    'santri_id' => $santriId,
                    'tanggal' => $validated['tanggal'],
                    'waktu_pengajian' => $validated['waktu_pengajian'],
                ],
                [
                    'status' => $data['status'],
                    'catatan' => $data['catatan'] ?? null,
                    'diinput_oleh' => auth()->id(),
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Notifikasi wali
            |--------------------------------------------------------------------------
            | Notifikasi dikirim jika status izin/sakit/alfa.
            | Untuk mengurangi notifikasi dobel, notifikasi hanya dibuat jika:
            | - data absensi sebelumnya belum ada, atau
            | - status sebelumnya berbeda dari status baru.
            */
            $statusBerubah = ! $absensiSebelumnya
                || $absensiSebelumnya->status !== $data['status'];

            if ($statusBerubah && in_array($data['status'], ['izin', 'sakit', 'alfa'])) {
                $santri = Santri::with('walis')->find($santriId);

                if ($santri) {
                    foreach ($santri->walis as $wali) {
                        Notifikasi::create([
                            'user_id' => $wali->id,
                            'santri_id' => $santri->id,
                            'jenis' => 'absensi',
                            'judul' => 'Notifikasi Absensi Pengajian',
                            'isi' => $santri->nama_santri
                                . ' tercatat '
                                . strtoupper($data['status'])
                                . ' pada pengajian '
                                . $this->formatWaktuPengajian($validated['waktu_pengajian'])
                                . ' tanggal '
                                . date('d-m-Y', strtotime($validated['tanggal']))
                                . '.',
                        ]);
                    }
                }
            }
        }

        return redirect()
            ->route('admin.absensi.index', [
                'tanggal' => $validated['tanggal'],
                'waktu_pengajian' => $validated['waktu_pengajian'],
                'kelas_madrasah_id' => $validated['kelas_madrasah_id'],
            ])
            ->with('success', 'Absensi pengajian berhasil disimpan.');
    }

    private function formatWaktuPengajian(string $waktu): string
    {
        return match ($waktu) {
            'bada_shubuh' => "Ba'da Shubuh",
            'bada_dzuhur' => "Ba'da Dzuhur",
            'bada_ashar' => "Ba'da Ashar",
            'bada_maghrib' => "Ba'da Maghrib",
            'bada_isya' => "Ba'da Isya",
            'pengajian_malam' => 'Pengajian Malam',
            default => $waktu,
        };
    }
}