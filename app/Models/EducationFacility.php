<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationFacility extends Model
{
    protected $fillable = [
        'name',
        'klas',
        'address',
        'gallery',
        'description',
        'school_code',
        'accreditation',
        'principal_name',
        'phone',
        'email',
        'website',
        'student_capacity',
        'teacher_count',
        'opening_hours',
        'video_url',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'gallery' => 'array',
        'opening_hours' => 'array',
    ];
}
