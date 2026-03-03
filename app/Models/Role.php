<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Role extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'name', 'description', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'created_by',
        'updated_by',
    ];

    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the creator of the role.
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the updater of the role.
     *
     * @return BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
