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
        'user_id',
        'name',
        // 'as',
        'rating',
        'body',
        'image',
        'publish'
    ];

    public function isPublish()
    {
        return $this->publish;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewPage()
    {
        return $this->belongsTo(ReviewPage::class);
    }
}
