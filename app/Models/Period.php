<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function status(): Attribute
    {
        return Attribute::make(
            get: function () {
                $now = now();
                $startDate = $this->start_date;
                $endDate = $this->end_date;

                if ($now->between($startDate, $endDate)) {
                    return 'active'; // Current date is between start and end date
                } elseif ($now->greaterThan($endDate)) {
                    return 'inactive'; // Current date is past end date
                } else {
                    return 'upcoming'; // Current date is before start date
                }
            }
        );
    }


    public function package()
    {
        return $this->hasMany(Package::class);
    }
}
