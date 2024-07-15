<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'titleHistory',
        'bodyHistory',
        'image',
        'titleVM',
        'vision',
    ];

    public function mission()
    {
        return $this->hasMany(Mission::class);
    }
}
