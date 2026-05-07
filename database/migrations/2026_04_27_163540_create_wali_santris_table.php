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
    Schema::create('wali_santris', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
        $table->enum('hubungan', ['ayah', 'ibu', 'wali'])->default('wali');
        $table->timestamps();

        $table->unique(['user_id', 'santri_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wali_santris');
    }
};
