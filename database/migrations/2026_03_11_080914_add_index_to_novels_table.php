<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk optimasi tabel novels.
     */
    public function up(): void
    {
        Schema::table('novels', function (Blueprint $table) {
            // 1. Index pada slug karena sering digunakan di URL (Unique & Fast)
            // Ini sudah mendukung performa method getByNovel di controller Anda
            if (!IndexExists('novels', 'novels_slug_unique')) {
                $table->unique('slug');
            }

            // 2. Index pada status untuk memfilter novel 'Published' vs 'Draft' secara cepat
            $table->index('status');
        });

        // 3. Full-Text Search untuk pencarian judul dan deskripsi yang lebih fleksibel
        // Full-Text jauh lebih cepat daripada 'LIKE %judul%' saat data sudah banyak
        DB::statement('ALTER TABLE novels ADD FULLTEXT fulltext_title_synopsis(title, synopsis)');
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::table('novels', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropUnique(['slug']);
        });
        
        DB::statement('ALTER TABLE novels DROP INDEX fulltext_title_synopsis');
    }
};