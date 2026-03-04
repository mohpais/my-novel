<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Novel extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'cover_image',
        'synopsis',
        'author',
        'status',
        'total_views',
        'published_at',
    ];

    protected $attributes = [
        'status' => 'Ongoing',
        'total_views' => 0,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'total_views' => 'integer',
        'status' => 'string',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'published_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be appended to the model's array form.
     *
     * @var array
     */
    protected $appends = ['slug'];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'novel_genre');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'novel_tag');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class)->orderBy('number');
    }

    /**
     * Get the slug attribute.
     *
     * @return string
     */
    public function getSlugAttribute()
    {
        return $this->attributes['slug'] ?? str_slug($this->title);
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

    protected static function booted()
    {
        static::creating(function ($novel) {
            if (empty($novel->slug)) {
                $novel->slug = str_slug($novel->title);
            }
        });
    }
}
