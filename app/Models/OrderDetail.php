<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_details';
    public $incrementing = false;
    protected $primaryKey;
    protected $keyType = 'string';

    protected $fillable = [
        'order_id',
        'product_id',
        'sub_agent_id',
        'sub_price',
        'qty',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function subAgent()
    {
        return $this->belongsTo(SubAgent::class, 'sub_agent_id');
    }
}
