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
        Schema::table('users', function (Blueprint $table) {
            // Foreign keys
            $table->foreignId('role_id')
                ->constrained('roles')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('department_id')
                ->nullable()
                ->constrained('departments')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('position_id')
                ->nullable()
                ->constrained('positions')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            // Location = province
            $table->foreignId('location_id')
                ->nullable()
                ->constrained('provinces')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
