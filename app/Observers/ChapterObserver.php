<?php

namespace App\Observers;

use App\Models\Chapter;
use App\Jobs\ProcessAiEmbedding;

class ChapterObserver
{
    /**
     * Menangani event saat Chapter berhasil disimpan (create/update).
     */
    public function saved(Chapter $chapter)
    {
        // Potong konten menjadi beberapa bagian (misal per 2000 karakter)
        // agar tidak melebihi limit model embedding
        $chunks = str_split($chapter->content, 2000);

        foreach ($chunks as $index => $chunk) {
            $category = "Chapter " . $chapter->number . " (Bagian " . ($index + 1) . ")";
            
            // Kirim setiap potongan ke Job
            // Kita tambahkan index agar ID vektor unik jika diperlukan
            ProcessAiEmbedding::dispatch($chapter, $chunk, $category, $index);
        }
    }

    /**
     * Menangani event saat Chapter dihapus.
     */
    public function deleted(Chapter $chapter)
    {
        // Menghapus data vektor AI yang terkait dengan chapter ini
        // Menggunakan polymorphic relationship sesuai pola LoreEntryObserver
        \App\Models\AiVector::where('vectorable_id', $chapter->id)
            ->where('vectorable_type', get_class($chapter))
            ->delete();
    }
}