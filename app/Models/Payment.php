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
        // 'remaining_payment',
        'method',
        'bank',
        'bank_owner_id',
        // 'installment',
        'note',
        'date',
        'status',
        'photo',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function bankOwner()
    {
        return $this->belongsTo(User::class, 'bank_owner_id');
    }
}
