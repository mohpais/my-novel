<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CostCenter extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'description', 'business_unit_id', 'profit_center_id'
    ];

    public function businessUnit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    public function profitCenter()
    {
        return $this->belongsTo(ProfitCenter::class);
    }

    public function assets()
    {
        return $this->hasMany(AssetRequest::class);
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }
}
