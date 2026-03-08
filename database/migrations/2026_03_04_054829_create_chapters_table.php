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
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('novel_id')->constrained()->cascadeOnDelete();
            $table->integer('number')->comment('chapter number');
            $table->integer('order')->comment('chapter order in the novel')->default(0);
            $table->string('title');
            $table->string('slug')->nullable();
            $table->longText('content');
            $table->date('released_date')->nullable();
            $table->timestamps();
            
            $table->unique(['novel_id', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};
