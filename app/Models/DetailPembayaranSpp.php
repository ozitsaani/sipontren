<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPembayaranSpp extends Model
{
    protected $fillable = [
        'pembayaran_spp_id',
        'tagihan_spp_id',
        'nominal',
    ];

    public function pembayaranSpp()
    {
        return $this->belongsTo(PembayaranSpp::class);
    }

    public function tagihanSpp()
    {
        return $this->belongsTo(TagihanSpp::class);
    }
}