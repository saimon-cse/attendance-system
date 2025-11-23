<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class FacultyController extends Controller
{
    public function index()
    {
        $faculties = Faculty::with(['user', 'department'])->get();
        return view('admin.faculty.index', compact('faculties'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('admin.faculty.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'employee_id' => ['required', 'string', 'unique:faculty,student_id'],
            'department_id' => ['required', 'exists:departments,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('teacher');

        Faculty::create([
            'user_id' => $user->id,
            'student_id' => $request->employee_id, // Mapping UI 'employee_id' to DB 'student_id'
            'department_id' => $request->department_id,
        ]);

        return redirect()->route('admin.faculty.index')->with('success', 'Faculty created successfully.');
    }

    public function edit(Faculty $faculty)
    {
        $departments = Department::all();
        return view('admin.faculty.edit', compact('faculty', 'departments'));
    }

    public function update(Request $request, Faculty $faculty)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$faculty->user_id],
            'employee_id' => ['required', 'string', 'unique:faculty,student_id,'.$faculty->id],
            'department_id' => ['required', 'exists:departments,id'],
        ]);

        $faculty->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $faculty->update([
            'student_id' => $request->employee_id,
            'department_id' => $request->department_id,
        ]);

        return redirect()->route('admin.faculty.index')->with('success', 'Faculty updated successfully.');
    }

    public function destroy(Faculty $faculty)
    {
        $faculty->user->delete(); // This cascades to faculty if configured, but safe to delete user
        // If cascade not set on DB, we should delete faculty first or user first depending on constraint
        // Assuming cascade delete on user_id in faculty table
        $faculty->delete();
        return redirect()->route('admin.faculty.index')->with('success', 'Faculty deleted successfully.');
    }
}
