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
        Schema::create('workflow_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('description')->nullable();
            $table->boolean('is_deleted')->nullable()->default(false);

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
        Schema::dropIfExists('workflow_templates');
    }
};
