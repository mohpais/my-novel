<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'item', 'amount', 'used', 'category', 'cost_center_id'
    ];

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class);
    }

    public function assets()
    {
        return $this->hasMany(AssetRequest::class);
    }

    public function getRemainingAttribute()
    {
        return $this->amount - $this->used;
    }
}
