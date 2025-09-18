<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory, UUIDGenerator;

    protected $table = 'loans';
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $fillable = [
        'borrower_name',
        'amount',
        'method',
        'bank_owner_id',
        'status_payment',
        'date',
        'description',
    ];

    public function loanPayments()
    {
        return $this->hasMany(LoanPayment::class, 'loan_id');
    }

    public function bankOwner()
    {
        return $this->belongsTo(BankOwner::class, 'bank_owner_id');
    }
}
