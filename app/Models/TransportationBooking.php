<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportationBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'transportation_id',
        'customer_name',
        'customer_phone',
        'booking_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
    ];

    public function transportation()
    {
        return $this->belongsTo(Transportation::class);
    }
}

