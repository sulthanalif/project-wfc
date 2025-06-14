<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, UUIDGenerator;

    protected $table = 'orders';
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $fillable = [
        'agent_id',
        'order_number',
        'total_price',
        'order_date',
        // 'access_date',
        'status',
        'payment_status',
        'delivery_status',
        'description',
    ];

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function detail()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }


    public function distributions()
    {
        return $this->hasMany(Distribution::class);
    }

    public function isAllItemDistributed()
    {
        return $this->detail->pluck('qty')->sum() === $this->distributions->pluck('detail')->flatten()->pluck('qty')->sum();
    }
}
