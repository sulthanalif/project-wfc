<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory, UUIDGenerator;

    protected $table = 'packages';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        // 'catalog_id',
        'description',
        'image',
    ];


    public function catalog()
    {
        return $this->hasOne(PackageCatalog::class);
    }

    public function product()
    {
        return $this->hasMany(ProductPackage::class);
    }

    public function catalogName()
    {
        return $this->hasOneThrough(Catalog::class, PackageCatalog::class, 'package_id', 'id', 'id', 'catalog_id')->withDefault(['name' => '-']);
    }
}
