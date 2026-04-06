<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'priceable_id', 'priceable_type', 'type', 'price', 'tax', 'insurance', 'visa_fee', 'tipping',
    ];

    public function priceable()
    {
        return $this->morphTo();
    }
}
