<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'location',
        'category',
        'stars',
        'description',
        'primary_image',
    ];

    protected $casts = [
        'stars' => 'integer',
    ];

    public function images()
    {
        return $this->hasMany(HotelImage::class);
    }
}
