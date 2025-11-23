<?php
// database/migrations/2024_01_01_000003_create_students_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('student_id', 20)->unique(); // Roll number/Registration number
            $table->foreignId('department_id')->constrained()->onDelete('restrict');

            // Academic Information
            $table->string('enrollment_number')->unique()->nullable();
            $table->string('session_year', 10); // e.g., "2024-2025"
            $table->integer('current_year'); // 1, 2, 3, 4
            $table->integer('current_semester'); // 1-8
            $table->enum('program_type', ['undergraduate', 'postgraduate', 'doctoral', 'diploma']);
            $table->string('degree_name'); // B.Tech, M.Tech, PhD, etc.

            // Student Status
            $table->enum('enrollment_status', [
                'active',
                'inactive',
                'suspended',
                'dropped',
                'graduated',
                'transferred'
            ])->default('active');
            $table->date('expected_graduation')->nullable();
            $table->date('graduation_date')->nullable();

            // Academic Performance
            $table->decimal('cgpa', 4, 2)->nullable();
            $table->integer('total_credits_earned')->default(0);
            $table->integer('total_credits_required')->default(0);
            $table->integer('active_backlogs')->default(0);
            $table->integer('total_backlogs')->default(0);


            // Face Recognition Data
            $table->json('face_encodings')->nullable(); // Store face encodings as JSON
            $table->float('face_confidence_threshold')->default(0.6);
            $table->integer('face_samples_count')->default(0);
            $table->timestamp('face_enrolled_at')->nullable();
            $table->timestamp('last_face_update')->nullable();
            $table->enum('face_enrollment_status', ['pending', 'enrolled', 'failed', 'updating'])->default('pending');
            $table->integer('face_recognition_failures')->default(0);


            $table->timestamps();

            // Indexes
            $table->index('student_id');
            $table->index('enrollment_number');
            $table->index('department_id');
            $table->index('session_year');
            $table->index('current_year');
            $table->index('enrollment_status');
            $table->index('face_enrollment_status');
            $table->index(['department_id', 'session_year', 'current_year']);
            $table->index(['session_year', 'enrollment_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
