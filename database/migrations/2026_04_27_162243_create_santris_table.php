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
    Schema::create('santris', function (Blueprint $table) {
        $table->id();
        $table->string('nis')->unique();
        $table->string('nama_santri');
        $table->foreignId('kelas_madrasah_id')->constrained('kelas_madrasahs')->cascadeOnDelete();
        $table->string('asrama')->nullable();
        $table->string('kamar')->nullable();
        $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('santris');
    }
};
