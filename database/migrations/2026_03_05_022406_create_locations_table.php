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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('world_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('parent_id')->nullable(); // Untuk hierarki (ex: Kota di dalam Negara)
            $table->string('name');
            $table->string('type'); // e.g., 'City', 'Dungeon', 'Kingdom'
            $table->text('description')->nullable();
            $table->text('climate')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('locations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
