<?php

namespace App\Observers;

use App\Models\Character;
use App\Jobs\ProcessAiEmbedding;

class CharacterObserver
{
    public function saved(Character $character)
    {
        // Gabungkan data penting karakter untuk dijadikan konteks
        $content = "Character Name: {$character->name}. " .
                   "Role: {$character->role}. " .
                   "Bio: {$character->description}. " .
                   "Personality: {$character->personality}";

        // Kirim ke antrean background
        ProcessAiEmbedding::dispatch(
            $character->novel_id,
            'character',
            $character->id,
            $content
        );
    }

    public function deleted(Character $character) {
        \App\Models\AiVector::where('source_category', 'character')
            ->where('source_id', $character->id)
            ->delete();
    }
}