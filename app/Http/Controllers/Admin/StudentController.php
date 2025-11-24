<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Department;
use App\Services\FaceRecognitionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class StudentController extends Controller
{
    protected $faceRecognitionService;

    public function __construct(FaceRecognitionService $faceRecognitionService)
    {
        $this->faceRecognitionService = $faceRecognitionService;
    }

    public function index()
    {
        $students = Student::with(['user', 'department'])->get();
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('admin.students.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'student_id' => ['required', 'string', 'unique:students'],
            'department_id' => ['required', 'exists:departments,id'],
            'session_year' => ['required', 'string'],
            'current_year' => ['required', 'integer', 'min:1', 'max:4'],
            'current_semester' => ['required', 'integer', 'min:1', 'max:8'],
            'program_type' => ['required', 'in:undergraduate,postgraduate,doctoral,diploma'],
            'degree_name' => ['required', 'string'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('student');

        $student = Student::create([
            'user_id' => $user->id,
            'student_id' => $request->student_id,
            'department_id' => $request->department_id,
            'session_year' => $request->session_year,
            'current_year' => $request->current_year,
            'current_semester' => $request->current_semester,
            'program_type' => $request->program_type,
            'degree_name' => $request->degree_name,
            'enrollment_number' => $request->enrollment_number,
        ]);

        return redirect()->route('admin.students.edit', $student)->with('success', 'Student created successfully. Now enroll face data.');
    }

    public function edit(Student $student)
    {
        $departments = Department::all();
        return view('admin.students.edit', compact('student', 'departments'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$student->user_id],
            'student_id' => ['required', 'string', 'unique:students,student_id,'.$student->id],
            'department_id' => ['required', 'exists:departments,id'],
            'session_year' => ['required', 'string'],
            'current_year' => ['required', 'integer', 'min:1', 'max:4'],
            'current_semester' => ['required', 'integer', 'min:1', 'max:8'],
            'program_type' => ['required', 'in:undergraduate,postgraduate,doctoral,diploma'],
            'degree_name' => ['required', 'string'],
        ]);

        $student->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $student->update([
            'student_id' => $request->student_id,
            'department_id' => $request->department_id,
            'session_year' => $request->session_year,
            'current_year' => $request->current_year,
            'current_semester' => $request->current_semester,
            'program_type' => $request->program_type,
            'degree_name' => $request->degree_name,
            'enrollment_number' => $request->enrollment_number,
            'enrollment_status' => $request->enrollment_status ?? 'active',
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->user->delete(); // Cascade to student
        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully.');
    }

    public function enrollFace(Request $request, Student $student)
    {
        $request->validate([
            'face_image' => 'required|string', // Base64 image
        ]);

        // $student->load(['faceSamples']);

        try {
            // Extract embedding from Python service
            $response = $this->faceRecognitionService->extractEmbedding($request->face_image);

            if (!isset($response['embedding'])) {
                return response()->json(['error' => 'Failed to extract face embedding'], 400);
            }

            // Update student face data
            $student->update([
                'face_encodings' => [$response['embedding']], // Store as array
                'face_enrolled_at' => now(),
                'last_face_update' => now(),
                'face_enrollment_status' => 'enrolled',
                'face_samples_count' => 1,
            ]);

            return response()->json(['success' => true, 'message' => 'Face enrolled successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
