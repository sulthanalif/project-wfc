<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'period_id',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}
