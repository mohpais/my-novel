<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetRequestJustification extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'asset_request_justifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'asset_request_id',
        'question_id',
        'answer',
    ];

    /**
     * Get the asset request that the justification belongs to.
     */
    public function assetRequest(): BelongsTo
    {
        return $this->belongsTo(AssetRequest::class);
    }

    /**
     * Get the question associated with the justification.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
