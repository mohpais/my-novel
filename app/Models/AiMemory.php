<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AiMemory extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'ai_memories';

    /**
     * Atribut yang dapat diisi melalui mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'novel_id',
        'source_id',
        'source_type',
        'title',
        'content',
        'importance',
        'embedding',
    ];

    /**
     * Atribut yang harus dikonversi ke tipe data tertentu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'importance' => 'integer',
        'embedding' => 'array',
    ];

    /**
     * Mendapatkan model pemilik memori ini (Polymorphic).
     * Menghubungkan source_id dan source_type ke model terkait.
     *
     */
    public function source(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Mendapatkan novel yang terkait dengan memori ini.
     *
     */
    public function novel(): BelongsTo
    {
        return $this->belongsTo(Novel::class);
    }
}