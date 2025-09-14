<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spending extends Model
{
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
