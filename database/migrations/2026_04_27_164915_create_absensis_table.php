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
    Schema::create('absensis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
        $table->date('tanggal');
        $table->enum('waktu_pengajian', [
            'bada_shubuh',
            'bada_dzuhur',
            'bada_ashar',
            'bada_maghrib',
            'bada_isya',
            'pengajian_malam',
        ]);
        $table->enum('status', ['hadir', 'izin', 'sakit', 'alfa']);
        $table->text('catatan')->nullable();
        $table->foreignId('diinput_oleh')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamps();

        $table->unique(['santri_id', 'tanggal', 'waktu_pengajian'], 'unique_absensi_pengajian');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
