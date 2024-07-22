<?php

namespace App\Models;

use App\Helpers\UUIDGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory, UUIDGenerator;

    protected $table = 'reviews';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $fillable = [
        'review_page_id',
        'name',
        'as',
        'rating',
        'body',
        'image'
    ];

    public function reviewPage()
    {
        return $this->belongsTo(ReviewPage::class);
    }
}
