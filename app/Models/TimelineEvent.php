<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimelineEvent extends Model
{
    protected $fillable = ['novel_id', 'event_name', 'year_marker', 'order_priority', 'description'];

    public function novel(): BelongsTo
    {
        return $this->belongsTo(Novel::class);
    }
}
