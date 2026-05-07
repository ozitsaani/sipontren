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
    Schema::create('pelanggarans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
        $table->date('tanggal');
        $table->string('jenis_pelanggaran');
        $table->enum('tingkat_pelanggaran', ['ringan', 'sedang', 'berat']);
        $table->text('catatan')->nullable();
        $table->text('tindak_lanjut')->nullable();
        $table->foreignId('diinput_oleh')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggarans');
    }
};
