<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;

    protected $fillable = [
        'detail_profile_id',
        'content'
    ];

    public function detailProfile()
    {
        return $this->belongsTo(DetailProfile::class);
    }


}
