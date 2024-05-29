<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSubProduct extends Model
{
    use HasFactory, UUIDGenerator;

    protected $table = 'product_sub_products';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $fillable = [
        'product_id',
        'sub_product_id',
        'amount',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function subProduct()
    {
        return $this->belongsTo(SubProduct::class);
    }
}
