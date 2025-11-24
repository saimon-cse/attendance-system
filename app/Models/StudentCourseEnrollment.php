<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCourseEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'course_assignment_id',
        'academic_year',
        'semester',
        'section',
        'batch',
    ];

    protected $casts = [];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function courseAssignment()
    {
        return $this->belongsTo(CourseAssignment::class);
    }
}
