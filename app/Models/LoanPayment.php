<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model
{
    use HasFactory, UUIDGenerator;

    protected $table = 'loan_payments';
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $fillable = [
        'loan_id',
        'pay',
        'date',
        'method',
        'bank_owner_id',
        'description',
        'photo',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }

    public function bankOwner()
    {
        return $this->belongsTo(BankOwner::class, 'bank_owner_id');
    }
}
