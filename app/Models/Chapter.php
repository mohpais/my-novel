<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Chapter extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'novel_id',
        'title',
        'number',
        'order',
        'content',
        'title',
        'slug',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'released_date',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'number' => 'integer',
        'content' => 'string',
    ];

    public function novel()
    {
        return $this->belongsTo(Novel::class);
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
