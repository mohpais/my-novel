<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Glossary extends Model
{
    protected $fillable = ['novel_id', 'term', 'pronunciation', 'definition'];

    public function novel(): BelongsTo
    {
        return $this->belongsTo(Novel::class);
    }
}
