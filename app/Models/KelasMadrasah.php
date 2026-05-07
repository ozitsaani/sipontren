<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelasMadrasah extends Model
{
    protected $fillable = [
        'nama_kelas',
        'keterangan',
    ];

    public function santris()
    {
        return $this->hasMany(Santri::class);
    }
}