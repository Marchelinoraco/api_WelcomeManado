<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transportation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'price',
        'available',
        'image_url',
        'description',
        'description_en',
        'description_ko',
        'description_zh',
    ];

    protected $casts = [
        'price' => 'integer',
        'available' => 'boolean',
    ];

    public function bookings()
    {
        return $this->hasMany(TransportationBooking::class);
    }
}
