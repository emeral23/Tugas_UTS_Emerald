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
        Schema::create('anotasis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_anotasi', 20)->unique();
            $table->string('jenis_suara', 100); // Contoh: Panggilan kawin, Ekolokasi, dll.
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anotasis');
    }
};
