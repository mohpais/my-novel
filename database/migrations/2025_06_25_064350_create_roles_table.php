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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // contoh: superadmin, admin, provider, customer
            $table->string('name')->nullable(); // contoh: "Super Admin"
            $table->text('description')->nullable(); // Deskripsi peran
            $table->boolean('is_active')->default(true); // Status aktif peran

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
        Schema::dropIfExists('roles');
    }
};
