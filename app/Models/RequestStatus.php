<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequestStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'actor'
    ];

    public function flows()
    {
        return $this->hasMany(AssetRequestFlow::class, 'status');
    }

    public function Actor()
    {
        return $this->belongsTo(Role::class, 'actor', 'code');
    }
}
