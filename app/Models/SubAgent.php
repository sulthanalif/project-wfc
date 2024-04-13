<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubAgent extends Model
{
    use HasFactory, UUIDGenerator;

    protected $table = 'sub_agents';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $fillable = [
        'agent_id',
        'name',
        'address',
        'phone_number',
    ];

    public function agent()
    {
        return $this->belongsTo(User::class);
    }
}
