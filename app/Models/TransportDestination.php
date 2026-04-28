<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportDestination extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_en',
        'title_ko',
        'title_zh',
        'description',
        'description_en',
        'description_ko',
        'description_zh',
        'sort_order',
        'is_active',
        'image_url',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active'  => 'boolean',
    ];
}
