<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggaran extends Model
{
    protected $fillable = [
        'santri_id',
        'tanggal',
        'jenis_pelanggaran',
        'tingkat_pelanggaran',
        'catatan',
        'tindak_lanjut',
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