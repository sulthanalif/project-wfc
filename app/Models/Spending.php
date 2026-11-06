<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spending extends Model
{
    /** Harga satuan dari total baris. total_amount adalah sumber total; amount di pengadaan berisi total, di input manual berisi harga satuan. */
    public function getUnitPriceAttribute(): float
    {
        return (float) $this->total_amount / max((float) ($this->qty ?: 1), 1);
    }

    use HasFactory, UUIDGenerator;

    protected $table = 'spendings';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $fillable = [
        'information',
        'spending_type_id',
        'amount',
        'method',
        'qty',
        'bank',
        'date',
        'bank_owner_id',
        'total_amount',
    ];

    public function spendingType()
    {
        return $this->belongsTo(SpendingType::class);
    }

    public function bankOwner()
    {
        return $this->belongsTo(BankOwner::class);
    }
}
