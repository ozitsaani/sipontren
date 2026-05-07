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
    Schema::create('pengaturan_spps', function (Blueprint $table) {
        $table->id();
        $table->integer('nominal_spp');
        $table->unsignedTinyInteger('tanggal_tagihan')->default(1);
        $table->unsignedTinyInteger('tanggal_jatuh_tempo')->default(10);
        $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_spps');
    }
};
