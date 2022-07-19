<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePoint extends Model
{
    use HasFactory;

    protected $table = 'service_points';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'serviceID',
        'servicePointTitle',
        'servicePointDescription',
        'servicePointAttachmentSrc',
    ];
}
