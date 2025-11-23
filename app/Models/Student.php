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

    public function faceSamples()
    {
        return $this->hasMany(FaceSample::class);
    }
}
