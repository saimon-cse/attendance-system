<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CourseAssignment;
use App\Models\AttendanceSession;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $faculty = $user->faculty;

        if (!$faculty) {
            return view('teacher.dashboard', ['assignments' => collect()]);
        }

        $assignments = CourseAssignment::with('course')
            ->where('faculty_id', $faculty->id)
            ->get();

        return view('teacher.dashboard', compact('assignments'));
    }

    public function createSession()
    {
        $user = Auth::user();
        $faculty = $user->faculty;

        if (!$faculty) {
            return redirect()->route('teacher.dashboard')->with('error', 'Faculty profile not found.');
        }

        $assignments = CourseAssignment::with('course')
            ->where('faculty_id', $faculty->id)
            ->get();

        return view('teacher.sessions.create', compact('assignments'));
    }

    public function storeSession(Request $request)
    {
        $request->validate([
            'course_assignment_id' => 'required|exists:course_assignments,id',
            'session_date' => 'required|date',
            'classroom' => 'required|string|max:255',
        ]);

        $session = AttendanceSession::create([
            'course_assignment_id' => $request->course_assignment_id,
            'session_date' => $request->session_date,
            'classroom' => $request->classroom,
            'status' => 'active',
        ]);

        return redirect()->route('teacher.sessions.show', $session)->with('success', 'Session started successfully.');
    }

    public function showSession(AttendanceSession $session)
    {
        //  $enrolledStudents = \App\Models\StudentCourseEnrollment::where('course_id', $session->courseAssignment->course_id)
        //     ->with('student.user')
        //     ->get()
        //     ->pluck('student');

        // dd($enrolledStudents, $session);
        return view('teacher.sessions.show', compact('session'));
    }

    public function markAttendance(Request $request, AttendanceSession $session)
    {
        try {
            $request->validate(['image' => 'required|string']);

            // Ensure relationships are loaded
            $session->load('courseAssignment');

            if (!$session->courseAssignment) {
                return response()->json(['success' => false, 'message' => 'Course Assignment not found for this session']);
            }

            // 1. Get enrolled students for this course
            $enrolledStudents = \App\Models\StudentCourseEnrollment::where('course_id', $session->courseAssignment->course_id)
                ->with('student.user')
                ->get()
                ->pluck('student');

            // 2. Prepare candidates for Python service
            $candidates = [];
            foreach ($enrolledStudents as $student) {
                if ($student && !empty($student->face_encodings)) {
                    // Assuming face_encodings is array of arrays, take the first one (primary)
                    // Check if face_encodings is a string (JSON) or array
                    $encodings = is_string($student->face_encodings) ? json_decode($student->face_encodings, true) : $student->face_encodings;
                    
                    if (!empty($encodings) && isset($encodings[0])) {
                         $candidates[] = [
                            'id' => (string) $student->id,
                            'embedding' => $encodings[0]
                        ];
                    }
                }
            }

            if (empty($candidates)) {
                return response()->json(['success' => false, 'message' => 'No students enrolled with face data']);
            }

            // 3. Call Python Service
            $faceService = app(\App\Services\FaceRecognitionService::class);
            $result = $faceService->identify($request->image, $candidates);

            if ($result && $result['match']) {
                $studentId = $result['student_id'];
                $student = $enrolledStudents->firstWhere('id', $studentId);

                if (!$student) {
                     return response()->json(['success' => false, 'message' => 'Matched student not found in enrollment list']);
                }

                // 4. Mark Attendance
                $attendance = \App\Models\Attendance::firstOrCreate(
                    [
                        'attendance_session_id' => $session->id,
                        'student_id' => $studentId
                    ],
                    [
                        'status' => 'present',
                        'captured_at' => now()
                    ]
                );

                return response()->json([
                    'success' => true,
                    'student' => [
                        'name' => $student->user->name,
                        'id' => $student->student_id
                    ]
                ]);
            }

            return response()->json(['success' => false, 'message' => 'No match found']);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Server Error: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
