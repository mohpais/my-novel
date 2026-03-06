<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AiVector extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'ai_vectors';

    /**
     * Atribut yang dapat diisi melalui mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'novel_id',
        'source_category',
        'source_id',
        'chunk_index',
        'content',
        'embedding',
    ];

    /**
     * Atribut yang harus dikonversi ke tipe data tertentu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'chunk_index' => 'integer',
        'embedding' => 'array',
    ];

    /**
     * Mendapatkan model pemilik vektor ini secara polimorfik.
     * Menggunakan 'source_category' sebagai tipe dan 'source_id' sebagai ID.
     */
    public function source(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'source_category', 'source_id');
    }

    /**
     * Mendapatkan novel terkait.
     */
    public function novel(): BelongsTo
    {
        return $this->belongsTo(Novel::class);
    }
}