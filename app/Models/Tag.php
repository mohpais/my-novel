<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = ['name', 'description', 'slug', 'is_active', 'created_by', 'updated_by'];
    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => true,
    ];
    
    protected $appends = ['slug'];

    public function novels(): HasMany
    {
        return $this->hasMany(Novel::class);
    }

    /**
     * Get the slug attribute.
     *
     * @return string
     */
    public function getSlugAttribute()
    {
        return $this->attributes['slug'] ?? Str::slug($this->title);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::saving(function ($tag) {
            if (empty($tag->slug) || $tag->isDirty('name')) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }
}
