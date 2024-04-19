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
        'stock',
        'price',
        'days'
    ];

    public function supplier()
    {
        return $this->hasOne(ProductSupplier::class);
    }

    public function detail()
    {
        return $this->hasOne(ProductDetail::class);
    }

    public function package()
    {
        return $this->hasOne(ProductPackage::class);
    }

    public function supplierName()
    {
        return $this->hasOneThrough(Supplier::class, ProductSupplier::class, 'product_id', 'id', 'id', 'supplier_id')->withDefault(['name' => '-']);
    }

    public function packagerName()
    {
        return $this->hasOneThrough(Package::class, ProductPackage::class, 'product_id', 'id', 'id', 'package_id')->withDefault(['name' => '-']);
    }
}
