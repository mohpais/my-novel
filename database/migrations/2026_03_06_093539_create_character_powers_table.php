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
        Schema::create('character_powers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();
        
            // Kategori Dasar (Disiplin Ilmu)
            $table->enum('category', [
                'Physical',    // Untuk Sword, Spear, Archery, dll
                'Magical',     // Untuk Sorcery, Summoning, Healing
                'Aetherial',   // Untuk Aetherial Trait
                'Technical',   // Untuk Utility/Support murni
            ]);

            // Detail Tipe Kekuatan
            $table->string('type'); // Isi bebas: 'Swordsmanship', 'Pyromancy', dll.

            // Sifat Kekuatan (Role)
            $table->enum('stance', ['Offensive', 'Defensive', 'Support', 'Utility'])->nullable();

            $table->string('name'); // Contoh: "Ordinem Vitalis"
            $table->text('description')->nullable();
            $table->integer('power_level')->nullable();
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_powers');
    }
};
