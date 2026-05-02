<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndonesiaDestination extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'title', 'location', 'slug', 'description',
        'description_en', 'description_ko', 'description_zh',
        'base_price',
        'duration_days', 'duration_nights', 'airline_info', 'highlights',
        'inclusions', 'exclusions', 'terms_conditions', 'interest_tags', 'itinerary_pdf_path',
        'is_featured', 'featured_badge',
    ];

    protected $casts = [
        'interest_tags' => 'array',
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
