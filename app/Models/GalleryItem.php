<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_en',
        'title_ko',
        'title_zh',
        'image_path',
        'video_name',
        'youtube_url',
        'sort_order',
        'is_active',
    ];
}
