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
        'vectorable_id',
        'vectorable_type',
        'content',
        'embedding',
    ];

    /**
     * Atribut yang harus dikonversi ke tipe data tertentu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'embedding' => 'array',
    ];

    /**
     * Mendefinisikan relasi polymorphic ke model yang dapat memiliki vektor AI.
     */
    public function vectorable() {
        return $this->morphTo();
    }

    /**
     * Mendapatkan novel terkait.
     */
    public function novel(): BelongsTo
    {
        return $this->belongsTo(Novel::class);
    }
}