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
        Schema::table('chapters', function (Blueprint $table) {
            // Menambahkan indeks pada novel_id dan order secara bersamaan (composite index)
            // Ini sangat mengoptimalkan query: WHERE novel_id = ? ORDER BY order ASC
            $table->index(['novel_id', 'order'], 'idx_novel_chapters_order');

            // Menambahkan indeks tunggal pada order jika kamu sering melakukan sorting global
            // $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chapters', function (Blueprint $table) {
            //
        });
    }
};
