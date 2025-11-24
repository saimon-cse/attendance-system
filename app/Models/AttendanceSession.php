<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_assignment_id',
        'session_date',
        'classroom',
        'status',
    ];

    protected $casts = [
        'session_date' => 'date',
    ];

    public function courseAssignment()
    {
        return $this->belongsTo(CourseAssignment::class);
    }
}
