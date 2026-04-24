<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogHeroImage extends Model
{
    use HasFactory;

    protected $table = 'blog_hero_image';

    protected $fillable = [
        'image_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
