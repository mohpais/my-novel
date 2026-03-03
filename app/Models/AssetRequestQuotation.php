<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetRequestQuotation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'asset_request_quotations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'asset_request_id',
        'purchase_quantity',
        'vendor_name',
        'currency',
        'base_rate',
        'amount',
        'tax_rate',
        'vat_amount',
        'total',
        'luxury_goods',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the asset request that the quotation belongs to.
     */
    public function assetRequest(): BelongsTo
    {
        return $this->belongsTo(AssetRequest::class);
    }

    /**
     * Get the user who created the quotation.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the quotation.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
