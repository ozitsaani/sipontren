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
    Schema::create('detail_pembayaran_spps', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pembayaran_spp_id')->constrained('pembayaran_spps')->cascadeOnDelete();
        $table->foreignId('tagihan_spp_id')->constrained('tagihan_spps')->cascadeOnDelete();
        $table->integer('nominal');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pembayaran_spps');
    }
};
