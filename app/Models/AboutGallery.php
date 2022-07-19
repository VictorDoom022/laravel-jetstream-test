<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutGallery extends Model
{
    use HasFactory;

    protected $table = 'about_gallery';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'galleryImageSrc',
        'galleryImagePosition',
    ];
}
