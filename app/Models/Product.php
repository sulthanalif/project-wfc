<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, UUIDGenerator;

    protected $table = 'products';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
        'stock',
        'price',
        'image',
    ];

    public function supplier()
    {
        return $this->hasOne(ProductSupplier::class);
    }

    public function supplierName()
    {
        return $this->hasOneThrough(Supplier::class, ProductSupplier::class, 'product_id', 'id', 'id', 'supplier_id')->withDefault(['name' => '-']);
    }
}
