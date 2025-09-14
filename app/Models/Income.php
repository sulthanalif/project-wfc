<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory, UUIDGenerator;

    protected $table = 'incomes';
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $fillable = [
        'information',
        'amount',
        'date',
        'method',
        'bank',
        'bank_owner_id',
    ];

    public function bankOwner()
    {
        return $this->belongsTo(BankOwner::class);
    }
}
