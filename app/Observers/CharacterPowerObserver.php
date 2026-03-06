<?php

namespace App\Observers;

use App\Models\CharacterPower;
use App\Jobs\ProcessAiEmbedding;

class CharacterPowerObserver
{
    /**
     * Menangani event "saved" pada CharacterPower.
     */
    public function saved(CharacterPower $power)
    {
        // Ambil nama karakter melalui relasi (pastikan relasi 'character' ada di model)
        $characterName = $power->character->name ?? 'Unknown Character';

        // Susun konten teknis kekuatan
        $content = "Power Name: {$power->name}. " .
                   "Owner: {$characterName}. " .
                   "Category: {$power->category} ({$power->type}). " .
                   "Stance: {$power->stance}. " .
                   "Power Level: {$power->power_level}. " .
                   "Description: {$power->description}";

        // Kirim ke antrean background
        // Karena CharacterPower terikat ke novel melalui Character, ambil novel_id dari character
        ProcessAiEmbedding::dispatch(
            $power->character->novel_id,
            'power', // source_category
            $power->id,
            $content
        );
    }

    /**
     * Hapus memori AI jika kekuatan dihapus.
     */
    public function deleted(CharacterPower $power)
    {
        \App\Models\AiVector::where('source_category', 'power')
            ->where('source_id', $power->id)
            ->delete();
    }
}