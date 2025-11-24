<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'face_encodings' => 'array',
        'expected_graduation' => 'date',
        'graduation_date' => 'date',
        'face_enrolled_at' => 'datetime',
        'last_face_update' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function faceSamples()
    {
        return $this->hasMany(FaceSample::class);
    }

    public function courseEnrollments()
    {
        return $this->hasMany(StudentCourseEnrollment::class);
    }
}
