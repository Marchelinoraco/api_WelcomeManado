<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManadoTour extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'title', 'location', 'slug', 'description',
        'description_en', 'description_ko', 'description_zh',
        'base_price', 'price_usd', 'tour_type',
        'duration_days',
        'duration_nights',
        'duration_hours',
        'duration_hours_min',
        'duration_hours_max',
        'highlights',
        'inclusions', 'exclusions', 'terms_conditions', 'itinerary_pdf_path',
        'is_featured', 'featured_badge',
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
