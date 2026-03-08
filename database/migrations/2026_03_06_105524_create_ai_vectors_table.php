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
        Schema::create('ai_vectors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('novel_id')->nullable();

            // Menghubungkan ke Model manapun (Location, LoreEntry, Character, dll)
            $table->morphs('vectorable');
            
            // Kita tambahkan 'sub_category' untuk filtering (misal: 'flora', 'religion', 'history')
            $table->string('tags')->nullable()->index();

            $table->longText('content');
            $table->json('embedding');
            $table->timestamps();

            // Indexes for AI search
            $table->index(['novel_id','vectorable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_vectors');
    }
};
