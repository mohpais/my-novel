<?php

namespace App\Observers;

use App\Models\CharacterPower;
use App\Jobs\ProcessAiEmbedding;

class CharacterPowerObserver
{
    public function saved(CharacterPower $power)
    {
        $characterName = $power->character->fullname ?? 'Unknown';
        $content = "Power: {$power->name} ({$power->type}). Owner: {$characterName}. Level: {$power->power_level}. Description: {$power->description}";

        ProcessAiEmbedding::dispatch($power, $content, 'power');
    }

    public function deleted(CharacterPower $power)
    {
        \App\Models\AiVector::where('vectorable_id', $power->id)
            ->where('vectorable_type', get_class($power))
            ->delete();
    }
}