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
        Schema::create('workflow_template_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_template_id')->constrained('workflow_templates')->cascadeOnDelete();
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');

            $table->integer('stage_order');
            $table->string('stage_name', 100)->nullable();
            $table->string('stage_type', 20)->nullable(); // approval, input_data
            $table->string('input_schema', 50)->nullable(); // quotation

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
        Schema::dropIfExists('workflow_template_stages');
    }
};
