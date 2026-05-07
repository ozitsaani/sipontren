<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagihanSpp extends Model
{
    protected $fillable = [
        'santri_id',
        'bulan',
        'tahun',
        'nominal',
        'jatuh_tempo',
        'status',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function detailPembayaranSpps()
{
    return $this->hasMany(DetailPembayaranSpp::class);
}
}