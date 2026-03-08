<?php

namespace App\Traits;

use App\Models\AiVector;

trait HasAiMemory {
    public function aiVector() {
        return $this->morphOne(AiVector::class, 'vectorable');
    }
}