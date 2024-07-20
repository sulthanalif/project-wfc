<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpendingType extends Model
{
    use HasFactory, UUIDGenerator;

    protected $table = 'spending_types';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name'
    ];

    public function spendings()
    {
        return $this->hasMany(Spending::class);
    }
}
