<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorldBuilding extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'world_buildings';

    /**
     * Atribut yang dapat diisi melalui mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'novel_id',
        'title',
        'category',
        'content',
        'embedding',
    ];

    /**
     * Cast atribut ke tipe data tertentu.
     * * Karena kolom 'embedding' bertipe JSON di migrasi, 
     * kita sebaiknya meng-cast-nya menjadi array.
     */
    protected $casts = [
        'embedding' => 'array',
    ];

    /**
     * Mendapatkan novel yang memiliki elemen world building ini.
     */
    public function novel(): BelongsTo
    {
        return $this->belongsTo(Novel::class);
    }
}