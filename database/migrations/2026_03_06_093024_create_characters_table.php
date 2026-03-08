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
            $table->string('fullname');
            $table->string('title')->nullable(); // Untuk Gelar resmi
            $table->string('epithet')->nullable(); // Julukan/Sebutan khusus
           
            // Daftar ini adalah yang paling umum digunakan untuk membedakan porsi kemunculan dan dampak tokoh terhadap alur utama. 
            // Protagonist: Tokoh utama pusat cerita.
            // Antagonist: Tokoh yang menentang atau menjadi hambatan utama protagonis.
            // Deuteragonist: Tokoh terpenting kedua (pendamping utama).
            // Tritagonist: Tokoh terpenting ketiga (sering kali menjadi penengah atau memiliki sub-plot signifikan).
            // Supporting: Tokoh pendukung yang muncul secara rutin.
            // Minor: Tokoh figuran yang hanya muncul sesekali.
            $table->enum('role', [
                'Protagonist', 
                'Antagonist', 
                'Deuteragonist', 
                'Tritagonist', 
                'Supporting', 
                'Minor'])->nullable();
            $table->enum('gender', ['male', 'female', 'unknown'])->default('male');
            $table->integer('age')->nullable();
            $table->integer('height_cm')->nullable();
            $table->text('appearance')->nullable();
            $table->text('personality')->nullable();
            $table->text('motivation')->nullable();
            $table->text('backstory')->nullable();
            $table->string('faction_affiliation')->nullable();
            $table->enum('status', ['alive', 'dead', 'missing', 'unknown'])->default('alive');
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
