<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Role;
use App\Models\Bookmark;
use App\Models\Favorite;

/**
 * Class User
 *
 * Represents a user in the system.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $password
 * @property string|null $picture
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $two_factor_secret
 * @property bool $two_factor_confirmed
 * @property \Illuminate\Support\Carbon|null $last_login_at
 * @property string|null $last_login_ip
 * @property int $status
 * @property string|null $verification_token
 * @property \Illuminate\Support\Carbon|null $verification_token_expires_at
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_number',
        'name',
        'email',
        'password',
        'role_id',
        'department_id',
        'position_id',
        'location_id',
        'cost_center_id',
        'business_unit_id',
        'picture',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 'status', 'created_at', 'updated_at'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class);
    }

    public function businessUnit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    public function approvals()
    {
        return $this->hasMany(AssetRequestWorkflow::class, 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function location()
    {
        return $this->belongsTo(Province::class, 'location_id');
    }
}
