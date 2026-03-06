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
        Schema::create('ai_memories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('novel_id')->nullable();
            $table->unsignedBigInteger('source_id');
            $table->string('source_type', 50); // character, chapter, lore, world, power
            // $table->enum('source_type', [
            //     'character', 
            //     'chapter', 
            //     'world_building', 
            //     'lore', 
            //     'power'
            // ])->nullable();
            $table->string('title')->nullable();
            $table->longText('content');
            $table->smallInteger('importance')->nullable()->default(1);
            $table->json('embedding')->nullable();
            $table->timestamps();
            
            // Indexes for AI search
            $table->index(['novel_id','source_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_memories');
    }
};
