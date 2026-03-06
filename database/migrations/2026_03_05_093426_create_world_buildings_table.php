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
        Schema::create('world_buildings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('novel_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->enum('category', ['Building', 'Landmark', 'Structure', 'Power System', 'Location', 'History', 'Race', 'Magic System'])->nullable();
            $table->longText('content');
            $table->json('embedding')->nullable();
            $table->timestamps();
        });

        // title: Aetherial Trait
        // category: Power System
        // content:
        // Aetherial Trait adalah kekuatan absolut berbasis resonansi jiwa. 
        // Setiap individu memiliki potensi untuk mengembangkan Aetherial Trait, tetapi hanya sedikit yang berhasil membangkitkannya. 
        // Trait ini memberikan kemampuan luar biasa yang dapat mempengaruhi dunia di sekitar mereka, seperti manipulasi elemen, peningkatan fisik, atau bahkan kemampuan untuk memanipulasi waktu dan ruang. 
        // Namun, membangkitkan Aetherial Trait juga membawa risiko besar, termasuk kemungkinan kehilangan kendali atas kekuatan tersebut atau bahkan kehancuran diri sendiri jika tidak dikelola dengan benar.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('world_buildings');
    }
};
