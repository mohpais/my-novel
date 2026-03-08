<?php

namespace App\Observers;

use App\Models\Character;
use App\Jobs\ProcessAiEmbedding;

class CharacterObserver
{
    public function saved(Character $character)
    {
        // Gabungkan data penting karakter untuk dijadikan konteks
        $nickName = $character->title ?? $character->epithet;
        
        $content = "Character Full Name: {$character->fullname}. " .
                   "Nick Name: {$nickName}. " .
                   "Role: {$character->role}. " .
                   "Appearance: {$character->appearance}. " .
                   "Personality: {$character->personality}. " .
                   "Motivation: {$character->motivation}" .
                   "Backstory: {$character->backstory}";

        // Kirim ke antrean background
        ProcessAiEmbedding::dispatch($character, $content, 'character');
    }

    public function deleted(Character $character) {
        \App\Models\AiVector::where('source_category', 'character')
            ->where('source_id', $character->id)
            ->delete();
    }
}