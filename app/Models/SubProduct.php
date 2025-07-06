<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubProduct extends Model
{
    use HasFactory, UUIDGenerator;

    protected $table = 'sub_products';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'unit',
        // 'amount',
        'price',
    ];

    public function product()
    {
        return $this->hasMany(ProductSubProduct::class);
    }

}
