<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, UUIDGenerator;

    protected $table = 'payments';
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $fillable = [
        'order_id',
        'invoice_number',
        'pay',
        'remaining_payment',
        'method',
        'installment'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
