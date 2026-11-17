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
        Schema::create('rekamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('habitat_id')
                ->constrained('habitats')
                ->restrictOnDelete();
            $table->foreignId('titik_perekaman_id')
                ->unique()
                ->constrained('titik_perekamans')
                ->cascadeOnDelete();
            $table->string('kode_rekaman', 30)->unique();
            $table->string('nama_rekaman', 150);
            $table->date('tanggal_perekaman');
            $table->string('perekam', 100);
            $table->boolean('status_validasi')->default(false);
            $table->timestamps();

            // Index untuk optimasi pencarian
            $table->index(['kode_rekaman', 'tanggal_perekaman']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekamans');
    }
};
