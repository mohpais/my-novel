<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalStage extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'approval_stages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'asset_request_id',
        'request_status_id',
        'role_id',
        'actioned_by',
        'actioned_at',
        'stage_order',
        'is_locked_to_user',
        'assigned_user_id',
    ];

    /**
     * Get the asset request for the approval stage.
     */
    public function assetRequest(): BelongsTo
    {
        return $this->belongsTo(AssetRequest::class);
    }

    /**
     * Get the status of the approval stage.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(RequestStatus::class, 'request_status_id');
    }

    /**
     * Get the user associated with the approval stage.
     */
    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actioned_by');
    }

    /**
     * Get the role associated with the approval stage.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Get the specific user assigned to the approval stage.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}
