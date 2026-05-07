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
    Schema::create('pembayaran_spps', function (Blueprint $table) {
        $table->id();
        $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
        $table->integer('total_bayar');
        $table->unsignedTinyInteger('jumlah_bulan');
        $table->string('bukti_bayar');
        $table->enum('status_verifikasi', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');
        $table->text('catatan_admin')->nullable();
        $table->timestamp('tanggal_upload')->nullable();
        $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamp('tanggal_verifikasi')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_spps');
    }
};
