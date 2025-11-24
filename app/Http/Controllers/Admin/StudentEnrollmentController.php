<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentCourseEnrollment;
use App\Models\Student;
use App\Models\Course;
use App\Models\CourseAssignment;
use Illuminate\Http\Request;

class StudentEnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = StudentCourseEnrollment::with(['student.user', 'course', 'courseAssignment.faculty.user'])->get();
        return view('admin.enrollments.index', compact('enrollments'));
    }

    public function create(Request $request)
    {
        $students = Student::with('user')->get();
        $courses = Course::with('department')->get();
        $assignments = CourseAssignment::with(['course', 'faculty.user'])->get();
        $selectedCourseId = $request->query('course_id');
        return view('admin.enrollments.create', compact('students', 'courses', 'assignments', 'selectedCourseId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'course_assignment_id' => 'nullable|exists:course_assignments,id',
            'academic_year' => 'required|string',
            'semester' => 'required|in:odd,even,summer',
            'section' => 'nullable|string',
        ]);

        StudentCourseEnrollment::create([
            'student_id' => $request->student_id,
            'course_id' => $request->course_id,
            'course_assignment_id' => $request->course_assignment_id,
            'academic_year' => $request->academic_year,
            'semester' => $request->semester,
            // 'enroll'
            'section' => $request->section,
        ]);

        return redirect()->route('admin.enrollments.index')->with('success', 'Student enrolled in course successfully.');
    }

    public function destroy(StudentCourseEnrollment $enrollment)
    {
        $enrollment->delete();
        return redirect()->route('admin.enrollments.index')->with('success', 'Enrollment removed successfully.');
    }
}
