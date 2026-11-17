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
        Schema::create('titik_perekamans', function (Blueprint $table) {
            $table->id();
            $table->string('koordinat_gps', 50);
            $table->string('lokasi_spesifik', 150);
            $table->decimal('elevasi_mdpl', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titik_perekamans');
    }
};
