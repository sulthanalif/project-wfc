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
        'date',
        'description',
    ];

    public function loanPayments()
    {
        return $this->hasMany(LoanPayment::class, 'loan_id');
    }
}
