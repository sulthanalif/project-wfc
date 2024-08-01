<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administration extends Model
{
    use HasFactory, UUIDGenerator;

    protected $table = 'administrations';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'ktp',
        // 'kk',
        'sPerjanjian',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
