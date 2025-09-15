<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'access_date',
        'description',
        'is_active',
    ];

    


    public function package()
    {
        return $this->hasMany(Package::class);
    }
}
