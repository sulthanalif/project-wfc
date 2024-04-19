<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;

    protected $table = 'product_details';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey;

    protected $fillable = [
        'product_id',
        'description',
        'image',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
