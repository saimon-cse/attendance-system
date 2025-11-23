<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'course_code',
        'course_name',
        'course_description',
        'credits',
        'semester',
        'category'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function assignments()
    {
        return $this->hasMany(CourseAssignment::class);
    }
}
