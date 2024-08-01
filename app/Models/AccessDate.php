<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
    ];
}
