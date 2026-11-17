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
        Schema::create('anotasi_rekaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekaman_id')
                ->constrained('rekamans')
                ->cascadeOnDelete();
            $table->foreignId('anotasi_id')
                ->constrained('anotasis')
                ->restrictOnDelete();
            $table->string('frekuensi_dominan', 50)->nullable();
            $table->timestamps();

            // Mencegah duplikasi anotasi yang sama pada rekaman yang sama
            $table->unique(['rekaman_id', 'anotasi_id'], 'rekaman_anotasi_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anotasi_rekaman');
    }
};
