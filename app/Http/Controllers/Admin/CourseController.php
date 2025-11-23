<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Department;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('department')->get();
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('admin.courses.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_name' => 'required|string|max:255',
            'course_code' => 'required|string|max:20|unique:courses',
            'department_id' => 'required|exists:departments,id',
            'credits' => 'nullable|integer|min:1',
            'semester' => 'nullable|integer|min:1',
        ]);

        Course::create($request->all());

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
    }

    public function edit(Course $course)
    {
        $departments = Department::all();
        return view('admin.courses.edit', compact('course', 'departments'));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'course_name' => 'required|string|max:255',
            'course_code' => 'required|string|max:20|unique:courses,course_code,' . $course->id,
            'department_id' => 'required|exists:departments,id',
            'credits' => 'nullable|integer|min:1',
            'semester' => 'nullable|integer|min:1',
        ]);

        $course->update($request->all());

        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully.');
    }
}
