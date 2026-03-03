<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessUnit extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'Description'];

    public function costCenters()
    {
        return $this->hasMany(CostCenter::class);
    }
}
