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
        Schema::create('approval_stages', function (Blueprint $table) {
            $table->id();

            // request yang di-approve
            $table->foreignId('asset_request_id')
                ->constrained('asset_requests')
                ->cascadeOnDelete();

            // status saat approval stage ini
            $table->foreignId('request_status_id')
                ->nullable()
                ->constrained('request_statuses')
                ->cascadeOnDelete();

            // role yang bertanggung jawab di stage ini
            $table->foreignId('role_id')
                ->nullable()
                ->constrained('roles')
                ->nullOnDelete();

            // user yang melakukan action (approve/reject)
            $table->foreignId('actioned_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('actioned_at')->nullable();

            // urutan stage approval
            $table->unsignedInteger('stage_order');

            // apakah stage ini terkunci untuk user tertentu
            $table->boolean('is_locked_to_user')->default(false);

            // user yang ditugaskan secara spesifik
            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_stages');
    }
};
