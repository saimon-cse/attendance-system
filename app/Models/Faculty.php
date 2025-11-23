<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $table = 'faculty'; // Explicitly define table name as it's singular in migration

    protected $fillable = [
        'user_id',
        'student_id', // This seems to be the column name in DB for employee ID
        'department_id',
    ];

    protected $casts = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function courseAssignments()
    {
        return $this->hasMany(CourseAssignment::class);
    }
}
