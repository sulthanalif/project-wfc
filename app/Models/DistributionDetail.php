<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributionDetail extends Model
{
    use HasFactory;

    protected $table = 'distribution_details';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $fillable = [
        'distribution_id',
        'product_id',
        'qty',
    ];

    public function distribution()
    {
        return $this->belongsTo(Distribution::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
