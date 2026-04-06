<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    use HasFactory;

    protected $fillable = [
        'itinerable_id', 'itinerable_type', 'day_number', 'title', 'description', 'hotel_info', 'meals_info',
    ];

    public function itinerable()
    {
        return $this->morphTo();
    }
}
