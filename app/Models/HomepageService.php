<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageService extends Model
{
    use HasFactory;

    protected $table = 'homepage_service';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'homepageServiceTitle',
        'homepageService1',
        'homepageService2',
        'homepageService3',
        'homepageService4',
        'homepageServiceImage',
    ];
}
