<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationFacility extends Model
{
    protected $fillable = [
        'name',
        'klas',
        'address',
        'image',
        'description',
        'latitude',
        'longitude',
    ];
}
