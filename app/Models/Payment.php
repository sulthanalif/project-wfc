<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    public $incrementing = false;
    protected $primaryKey;
    protected $keyType = 'string';

    protected $fillable = [
        'order_id',
        'image',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
