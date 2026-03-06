<?php

namespace App\Observers;

use App\Models\WorldBuilding;
use App\Jobs\ProcessAiEmbedding;

class WorldBuildingObserver
{
    /**
     * Menangani event "saved" (create & update) pada WorldBuilding.
     */
    public function saved(WorldBuilding $worldBuilding)
    {
        // Susun konten yang akan dipelajari AI
        $content = "Category: {$worldBuilding->category}. " .
                   "Title: {$worldBuilding->title}. " .
                   "Information: {$worldBuilding->content}";

        // Kirim ke antrean background
        ProcessAiEmbedding::dispatch(
            $worldBuilding->novel_id,
            'world_building', // source_category
            $worldBuilding->id,
            $content
        );
    }

    /**
     * Menangani event "deleted" jika Anda ingin menghapus vektor saat data dihapus.
     */
    public function deleted(WorldBuilding $worldBuilding)
    {
        // Opsional: Hapus data di ai_vectors agar memori AI tetap sinkron
        \App\Models\AiVector::where('source_category', 'world_building')
            ->where('source_id', $worldBuilding->id)
            ->delete();
    }
}