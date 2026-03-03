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
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->nullable()->constrained('regions')->restrictOnDelete();
            $table->string('code', 10)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable(); // Deskripsi peran

            // Relasi dengan tabel users
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null'); // User yang membuat peran ini
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null'); // User yang terakhir mengupdate peran ini

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provinces');
    }
};
