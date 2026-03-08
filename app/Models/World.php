<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class World extends Model
{
    protected $fillable = ['novel_id', 'name', 'description'];

    public function novel(): BelongsTo
    {
        return $this->belongsTo(Novel::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }
}