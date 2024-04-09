<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, UUIDGenerator;

    protected $table = 'catalogs';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'catalog_id',
        'description',
    ];


    public function catalog()
    {
        return $this->belongsTo(Catalog::class);
    }
}
