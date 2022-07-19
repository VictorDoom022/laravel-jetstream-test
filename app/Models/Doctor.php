<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'doctorName',
        'doctorPosition',
        'doctorCredentials',
        'doctorClinicalInterest',
        'doctorAbout',
        'doctorImageSrc'
    ];
}
