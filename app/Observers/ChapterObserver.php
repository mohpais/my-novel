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
        // Mengambil judul novel untuk memberikan konteks yang lebih kuat pada AI
        $novelTitle = $chapter->novel->title ?? 'Unknown Novel';

        // Menggabungkan data penting chapter sebagai konteks AI
        $content = "Novel: {$novelTitle}. " .
                   "Chapter Number: {$chapter->number}. " .
                   "Chapter Title: {$chapter->title}. " .
                   "Content: {$chapter->content}";

        // Kirim ke antrean background untuk pemrosesan embedding
        // Menggunakan kategori 'chapter' agar mudah difilter nantinya
        ProcessAiEmbedding::dispatch($chapter, $content, 'chapter');
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