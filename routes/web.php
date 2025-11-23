<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\FaceRecognitionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('teacher')) {
        return redirect()->route('teacher.dashboard');
    } elseif ($user->hasRole('student')) {
        return redirect()->route('student.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/teacher/dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');
});

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Custom Test Routes
Route::get('/test-face', function () {
    return view('test-face');
});

Route::get('/debug-enroll', function () {
    try {
        $student = \App\Models\Student::firstOrCreate(
            ['student_id' => '1'],
            [
                'user_id' => 1,
                'department_id' => 1,
                'session_year' => '2024-2025',
                'current_year' => 1,
                'current_semester' => 1,
                'program_type' => 'undergraduate',
                'degree_name' => 'B.Tech',
                'face_enrollment_status' => 'enrolled',
                'face_enrolled_at' => now(),
            ]
        );

        $student->faceSamples()->create([
            'image_path' => 'test.jpg',
            'face_encoding' => json_encode(array_fill(0, 128, 0.1)),
            'quality_score' => 0.99,
            'face_confidence' => 0.99,
            'is_primary' => true,
        ]);

        return "Success: " . $student->id . " Sample Created";
    } catch (\Throwable $e) {
        return $e->getMessage() . "\n" . $e->getTraceAsString();
    }
});
