<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Novel extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'cover_image',
        'synopsis',
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
    // protected $hidden = [
    //     'published_at',
    //     'created_at',
    //     'updated_at',
    // ];

    /**
     * The attributes that should be appended to the model's array form.
     *
     * @var array
     */
    protected $appends = ['slug', 'cover_url'];

    /**
     * Accessor untuk mendapatkan URL lengkap gambar.
     */
    public function getCoverUrlAttribute()
    {
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }
        return asset('images/default-cover.jpg'); // Path jika gambar kosong
    }

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

    protected static function booted()
    {
        static::creating(function ($novel) {
            if (empty($novel->slug)) {
                $novel->slug = Str::slug($novel->title);
            }
        });
    }
}
