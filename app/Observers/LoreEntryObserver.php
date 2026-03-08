<?php

namespace App\Observers;

use App\Models\LoreEntry;
use App\Jobs\ProcessAiEmbedding;

class LoreEntryObserver
{
    public function saved(LoreEntry $lore)
    {
        $content = "Lore Title: {$lore->title}. Category: {$lore->category}. Content: {$lore->content}";
        
        // Kirim model-nya langsung
        ProcessAiEmbedding::dispatch($lore, $content, $lore->category);
    }

    public function deleted(LoreEntry $lore)
    {
        // Menghapus vector berdasarkan polymorphic relationship
        \App\Models\AiVector::where('vectorable_id', $lore->id)
            ->where('vectorable_type', get_class($lore))
            ->delete();
    }
}