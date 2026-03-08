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
        'fullname',
        'title',
        'epithet',
        'role',
        'gender',
        'age',
        'height_cm',
        'appearance',
        'personality',
        'motivation', // Apa yang diinginkan karakter atau tujuan utamanya
        'backstory', // Sejarah singkat masa lalu karakter
        'faction_affiliation', // Organisasi atau kelompok tempat karakter bernaung.
        'status',
    ];

    /**
     * Atribut yang harus dikonversi ke tipe data tertentu.
     * * @var array<string, string>
     */
    protected $casts = [
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