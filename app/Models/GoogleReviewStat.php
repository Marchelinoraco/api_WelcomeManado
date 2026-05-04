<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleReviewStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'rating',
        'review_count',
        'last_updated',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'review_count' => 'integer',
        'last_updated' => 'datetime',
    ];

    /**
     * Get the latest Google review stats
     */
    public static function getLatest()
    {
        return self::latest()->first() ?? self::create([
            'rating' => 4.9,
            'review_count' => 39,
            'last_updated' => now(),
        ]);
    }
}
