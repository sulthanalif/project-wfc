<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    use HasFactory, UUIDGenerator;

    protected $table = 'distributions';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $fillable = [
        'distribution_number',
        'date',
        'police_number',
        'driver',
        // 'agent_id',
        'order_id',
        'status',
        'is_delivery',
        'print_count'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function detail()
    {
        return $this->hasMany(DistributionDetail::class);
    }
}
