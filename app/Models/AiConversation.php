<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiConversation extends Model
{
    use HasFactory;

    protected $table = 'ai_conversations'; //

    protected $fillable = [
        'novel_id', //
        'session_id', //
    ];

    /**
     * Mendapatkan semua pesan dalam percakapan ini.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(AiMessage::class);
    }

    /**
     * Mendapatkan novel terkait jika ada.
     */
    public function novel(): BelongsTo
    {
        return $this->belongsTo(Novel::class);
    }
}