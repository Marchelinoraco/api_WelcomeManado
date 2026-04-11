<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelInfoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'category_key',
        'title',
        'title_en',
        'title_ko',
        'title_zh',
        'description',
        'description_en',
        'description_ko',
        'description_zh',
        'image_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];
}
