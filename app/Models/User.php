<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Helpers\UUIDGenerator;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
// use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, UUIDGenerator, HasRoles;

    protected $table = 'users';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    // public function getJWTIdentifier()
    // {
    //     return $this->getKey();
    // }

    // public function getJWTCustomClaims()
    // {
    //     return [];
    // }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // 'name',
        'email_verified_at',
        'email',
        'password',
        'active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isActive()
    {
        return $this->active;
    }

    public function agentProfile ()
    {
        return $this->hasOne(AgentProfile::class);
    }

    public function adminProfile ()
    {
        return $this->hasOne(AdminProfile::class);
    }

    public function administration ()
    {
        return $this->hasOne(Administration::class);
    }

    public function subAgent ()
    {
        return $this->hasMany(SubAgent::class, 'agent_id');
    }

    public function order()
    {
        return $this->hasMany(Order::class, 'agent_id');
    }


}
