<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSupplier extends Model
{
    use HasFactory, UUIDGenerator;

    protected $table = 'product_suppliers';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey ;

    protected $fillable = [
        'product_id',
        'supplier_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
