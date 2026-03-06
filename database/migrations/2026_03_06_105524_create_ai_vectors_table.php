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
            $table->string('source_category', 50); // character, chapter, lore, world, power
            $table->unsignedBigInteger('source_id');
            $table->integer('chunk_index')->default(0);
            $table->longText('content');
            $table->json('embedding');
            $table->timestamps();

            // Indexes for AI search
            $table->index(['novel_id','source_category']);
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
