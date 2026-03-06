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
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('novel_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('role')->nullable();
            $table->enum('gender', ['male', 'female', 'unknown'])->default('male');
            $table->integer('age')->nullable();
            $table->integer('height_cm')->nullable();
            $table->text('appearance')->nullable();
            $table->text('personality')->nullable();
            $table->text('abilities')->nullable();
            $table->text('description')->nullable();
            $table->json('embedding')->nullable();
            $table->enum('status', ['alive', 'dead', 'unknown'])->default('alive');
            $table->timestamps();
        });
        // Exp:
        // name: Seren
        // role: Royal Knight
        // abilities: Ordinem Vitalis, Dormant Ether Seed
        // description:
        // Seren adalah ksatria kerajaan yang sangat loyal kepada Putri Aria.
        // Ia memiliki potensi kebangkitan Aetherial Trait.
        // embedding dipakai untuk vector memory AI, untuk menyimpan representasi vektor dari karakter yang bisa digunakan untuk pencarian semantik atau rekomendasi dalam konteks AI.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
