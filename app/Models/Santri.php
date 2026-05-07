<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    protected $fillable = [
        'nis',
        'nama_santri',
        'kelas_madrasah_id',
        'asrama',
        'kamar',
        'status',
    ];

    public function kelasMadrasah()
    {
        return $this->belongsTo(KelasMadrasah::class);
    }

    public function waliSantris()
{
    return $this->hasMany(WaliSantri::class);
}

public function walis()
{
    return $this->belongsToMany(User::class, 'wali_santris')
        ->withPivot('hubungan')
        ->withTimestamps();
}

public function absensis()
{
    return $this->hasMany(Absensi::class);
}

public function notifikasis()
{
    return $this->hasMany(Notifikasi::class);
}

public function tagihanSpps()
{
    return $this->hasMany(TagihanSpp::class);
}

public function pembayaranSpps()
{
    return $this->hasMany(PembayaranSpp::class);
}

public function pelanggarans()
{
    return $this->hasMany(Pelanggaran::class);
}

}