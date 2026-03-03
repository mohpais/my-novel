<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetRequest extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'asset_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_code',
        'cost_center_id',
        'budget_id',
        'user_id',
        'request_status_id',
        'purchase_quantity',
        'name',
        'description',
        'reason',
        'replacement',
        'useful_life',
        'initial_value',
        'currency',
    ];

    /**
     * Get the requester who made the asset request.
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the cost center for the asset request.
     */
    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class);
    }

    /**
     * Get the budget for the asset request.
     */
    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class, 'budget_id');
    }

    /**
     * Get the status of the asset request.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(RequestStatus::class, 'request_status_id');
    }

    /**
     * Get the quotations for the asset request.
     */
    public function quotations(): HasMany
    {
        return $this->hasMany(AssetRequestQuotation::class);
    }

    /**
     * Get the justifications for the asset request.
     */
    public function justifications(): HasMany
    {
        return $this->hasMany(AssetRequestJustification::class);
    }

    /**
     * Get the approval_stages for the asset request.
     */
    public function approvalStages(): HasMany
    {
        return $this->hasMany(ApprovalStage::class);
    }

    /**
     * Get the justifications for the asset request.
     */
    public function approvalLogs(): HasMany
    {
        return $this->hasMany(ApprovalLog::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Prefix
            $prefix = 'AST';

            // Tahun
            $year = date('Y');

            // Hitung jumlah request dengan prefix + tahun
            $count = self::whereYear('created_at', $year)
                ->where('request_code', 'like', "{$prefix}-{$year}-%")
                ->count();

            $next = $count + 1;

            // Format 5 digit urutan
            $sequence = str_pad($next, 5, '0', STR_PAD_LEFT);

            $model->request_code = "{$prefix}-{$year}-{$sequence}";
        });
    }
}
