<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('tagihan_spps', function (Blueprint $table) {
        $table->id();
        $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
        $table->unsignedTinyInteger('bulan');
        $table->unsignedSmallInteger('tahun');
        $table->integer('nominal');
        $table->date('jatuh_tempo');
        $table->enum('status', [
            'belum_dibayar',
            'menunggak',
            'menunggu_verifikasi',
            'lunas',
            'ditolak'
        ])->default('belum_dibayar');
        $table->timestamps();

        $table->unique(['santri_id', 'bulan', 'tahun']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan_spps');
    }
};
