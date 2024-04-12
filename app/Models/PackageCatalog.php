<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageCatalog extends Model
{
    use HasFactory;

    protected $table = 'package_catalog';
    public $incrementing = false;
    protected $primaryKey;
    protected $keyType = 'string';

    protected $fillable = [
        'package_id',
        'catalog_id',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function catalog()
    {
        return $this->belongsTo(Catalog::class);
    }
}
