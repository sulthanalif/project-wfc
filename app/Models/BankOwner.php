<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankOwner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'account_name',
        'account_number',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
