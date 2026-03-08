<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $fillable = ['world_id', 'parent_id', 'name', 'type', 'description', 'climate'];

    public function world(): BelongsTo
    {
        return $this->belongsTo(World::class);
    }

    // Relasi Rekursif untuk Hierarki
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(Character::class, 'character_locations')
                    ->withPivot('status')
                    ->withTimestamps();
    }
}
