<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'approval_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'asset_request_id',
        'request_status_id',
        'user_id',
        'role_id',
        'stage_order',
        'is_shown',
        'notes',
    ];

    /**
     * Get the asset request for the log entry.
     */
    public function assetRequest(): BelongsTo
    {
        return $this->belongsTo(AssetRequest::class);
    }

    /**
     * Get the status of the log entry.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(RequestStatus::class, 'request_status_id');
    }

    /**
     * Get the user who made the approval action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the role of the user who made the approval action.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
