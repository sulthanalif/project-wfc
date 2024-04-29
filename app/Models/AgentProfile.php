<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentProfile extends Model
{
    use HasFactory, UUIDGenerator;

    protected $table = 'agent_profiles';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'name',
        'photo',
        'phone_number',
        'rt',
        'rw',
        'village',
        'district',
        'regency',
        'province',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
