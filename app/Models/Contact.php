<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subTitle',
        'address',
        'email',
        'phoneNumber',
        'mapUrl'
    ];

    public function numbers()
    {
        return $this->hasMany(ContactNumber::class);
    }
}
