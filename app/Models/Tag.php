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

    protected static function booted(): void
    {
        static::saving(function ($role) {
            if (empty($role->slug) || $role->isDirty('name')) {
                $role->slug = Str::slug($role->name);
            }
        });
    }
}
