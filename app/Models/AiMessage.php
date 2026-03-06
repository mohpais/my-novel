<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiMessage extends Model
{
    use HasFactory;

    protected $table = 'ai_messages'; //

    protected $fillable = [
        'ai_conversation_id', //
        'role', //
        'content', //
    ];

    /**
     * Mendapatkan percakapan pemilik pesan ini.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(AiConversation::class, 'ai_conversation_id');
    }
}