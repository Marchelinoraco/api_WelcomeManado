<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManadoTour extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'title', 'slug', 'description', 'base_price', 'tour_type',
        'duration_days',
        'duration_nights',
        'duration_hours',
        'duration_hours_min',
        'duration_hours_max',
        'highlights',
        'inclusions', 'exclusions', 'terms_conditions',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function prices()
    {
        return $this->morphMany(TourPrice::class, 'priceable');
    }

    public function itineraries()
    {
        return $this->morphMany(Itinerary::class, 'itinerable');
    }

    public function galleries()
    {
        return $this->morphMany(Gallery::class, 'galleryable');
    }
}
