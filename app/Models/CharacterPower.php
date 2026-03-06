<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CharacterPower extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     * * @var string
     */
    protected $table = 'character_powers';

    /**
     * Atribut yang dapat diisi melalui mass assignment.
     * * @var array<int, string>
     */
    protected $fillable = [
        'character_id',
        'category',
        'type',
        'stance',
        'name',
        'description',
        'power_level',
    ];

    /**
     * Atribut yang harus dikonversi ke tipe data tertentu.
     * * @var array<string, string>
     */
    protected $casts = [
        'power_level' => 'integer',
    ];

    /**
     * Mendapatkan karakter pemilik kekuatan ini.
     */
    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }
}