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
        Schema::create('cost_centers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 150)->unique();
            $table->string('name', 255);
            $table->text('description')->nullable(); // Deskripsi

            $table->foreignId('profit_center_id')->constrained('profit_centers')->cascadeOnDelete();
            $table->foreignId('business_unit_id')->constrained('business_units')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cost_centers');
    }
};
