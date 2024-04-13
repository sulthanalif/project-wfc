<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    use HasFactory, UUIDGenerator;

    protected $table = 'catalogs';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
        // 'image',
    ];

    public function package()
    {
        return $this->hasOne(PackageCatalog::class);
    }
}
