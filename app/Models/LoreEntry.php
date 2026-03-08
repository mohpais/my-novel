<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoreEntry extends Model
{
    protected $fillable = ['novel_id', 'category', 'title', 'content'];

    public function novel(): BelongsTo
    {
        return $this->belongsTo(Novel::class);
    }
}
