<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reward extends Model
{
    use HasFactory;

    protected $table = 'rewards';

    protected $fillable = [
        'period_id',
        'title',
        'target_qty',
        'description',
        'image'
    ];

    public function isActive(): Attribute
    {
        return Attribute::make(
            get: function () {
                $active = now()->between(
                    $this->period->start_date,
                    $this->period->end_date
                );
                return $active;
            }
        );
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class, 'period_id', 'id');
    }

}
