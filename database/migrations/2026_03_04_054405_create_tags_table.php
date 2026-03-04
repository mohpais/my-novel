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
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // contoh: "Perawatan Kendaraan Bermotor"
            $table->text('description')->nullable(); // Deskripsi bidang usaha
            $table->string('slug')->unique(); // Slug untuk URL yang ramah SEO

            // Relasi dengan tabel users
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null'); // User yang membuat peran ini
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null'); // User yang terakhir mengupdate peran ini

            // Tambahan untuk fitur lainnya
            $table->boolean('is_active')->default(true); // Status aktif bidang usaha
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
