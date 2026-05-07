<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranSpp extends Model
{
    protected $fillable = [
        'santri_id',
        'total_bayar',
        'jumlah_bulan',
        'bukti_bayar',
        'status_verifikasi',
        'catatan_admin',
        'tanggal_upload',
        'diverifikasi_oleh',
        'tanggal_verifikasi',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function detailPembayaranSpps()
    {
        return $this->hasMany(DetailPembayaranSpp::class);
    }

    public function adminVerifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }
}