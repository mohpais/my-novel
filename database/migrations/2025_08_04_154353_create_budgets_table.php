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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cost_center_id')->constrained()->cascadeOnDelete();
            
            $table->string('code', 255)->unique();
            $table->string('item', 1000);
            $table->float('amount');
            $table->float('used')->default(0);
            $table->string('category')->nullable();
            $table->integer('year'); // <-- Tambahkan kolom tahun
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
