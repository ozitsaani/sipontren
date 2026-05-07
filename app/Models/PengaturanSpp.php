<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanSpp extends Model
{
    protected $fillable = [
        'nominal_spp',
        'tanggal_tagihan',
        'tanggal_jatuh_tempo',
        'status',
    ];
}