<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = [
        'santri_id',
        'tanggal',
        'waktu_pengajian',
        'status',
        'catatan',
        'diinput_oleh',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'diinput_oleh');
    }
}