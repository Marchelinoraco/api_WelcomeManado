<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = ['galleryable_id', 'galleryable_type', 'image_path', 'is_primary'];

    public function galleryable()
    {
        return $this->morphTo();
    }
}
