<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiLog extends Model
{
    use HasFactory;

    protected $table = 'ai_logs'; //

    protected $fillable = [
        'provider', //
        'model', //
        'prompt_tokens', //
        'completion_tokens', //
        'response_time', //
    ];

    /**
     * Atribut yang harus dikonversi ke tipe data tertentu.
     */
    protected $casts = [
        'prompt_tokens' => 'integer', //
        'completion_tokens' => 'integer', //
        'response_time' => 'float', //
    ];

    /**
     * Accessor untuk mendapatkan total token.
     */
    public function getTotalTokensAttribute(): int
    {
        return $this->prompt_tokens + $this->completion_tokens;
    }
}