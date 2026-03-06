<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Character extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     * * @var string
     */
    protected $table = 'characters';

    /**
     * Atribut yang dapat diisi melalui mass assignment.
     * * @var array<int, string>
     */
    protected $fillable = [
        'novel_id',
        'name',
        'role',
        'gender',
        'age',
        'height_cm',
        'appearance',
        'personality',
        'abilities',
        'description',
        'embedding',
        'status',
    ];

    /**
     * Atribut yang harus dikonversi ke tipe data tertentu.
     * * @var array<string, string>
     */
    protected $casts = [
        'embedding' => 'array',
        'age' => 'integer',
        'height_cm' => 'integer',
    ];

    /**
     * Mendapatkan novel yang memiliki karakter ini.
     */
    public function novel(): BelongsTo
    {
        return $this->belongsTo(Novel::class);
    }
}