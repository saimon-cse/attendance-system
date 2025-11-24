<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseAssignment;
use App\Models\Course;
use App\Models\Faculty;
use Illuminate\Http\Request;

class CourseAssignmentController extends Controller
{
    public function index()
    {
        $assignments = CourseAssignment::with(['course', 'faculty.user'])->get();
        return view('admin.assignments.index', compact('assignments'));
    }

    public function create(Request $request)
    {
        $courses = Course::all();
        $faculties = Faculty::with('user')->get();
        $selectedCourseId = $request->query('course_id');
        return view('admin.assignments.create', compact('courses', 'faculties', 'selectedCourseId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'faculty_id' => 'required|exists:faculty,id',
            'session_year' => 'required|string',
            'semester' => 'required|integer',
            'section' => 'required|string',
        ]);

        CourseAssignment::create([
            'course_id' => $request->course_id,
            'faculty_id' => $request->faculty_id,
            'academic_year' => $request->session_year,
            'semester' => $request->semester,
            'section' => $request->section,
        ]);

        return redirect()->route('admin.assignments.index')->with('success', 'Course assigned successfully.');
    }

    public function destroy(CourseAssignment $assignment)
    {
        $assignment->delete();
        return redirect()->route('admin.assignments.index')->with('success', 'Assignment removed successfully.');
    }
}
