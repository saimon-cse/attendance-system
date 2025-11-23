<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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
