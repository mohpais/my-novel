<?php

namespace App\Observers;

use App\Models\Location;
use App\Jobs\ProcessAiEmbedding;

class LocationObserver
{
    public function saved(Location $location)
    {
        $parentName = $location->parent->name ?? 'None';
        $content = "Location: {$location->name}. Type: {$location->type}. Part of: {$parentName}. Climate: {$location->climate}. Description: {$location->description}";
        
        ProcessAiEmbedding::dispatch($location, $content, $location->type);
    }

    public function deleted(Location $location)
    {
        \App\Models\AiVector::where('vectorable_id', $location->id)
            ->where('vectorable_type', get_class($location))
            ->delete();
    }
}