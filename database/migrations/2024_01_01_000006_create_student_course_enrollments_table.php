<?php
// database/migrations/2024_01_01_000006_create_student_course_enrollments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_course_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('restrict');
            $table->foreignId('course_assignment_id')->nullable()->constrained()->onDelete('set null');

            // Enrollment Details
            $table->string('academic_year', 10);
            $table->enum('semester', ['odd', 'even', 'summer']);
            $table->date('enrollment_date');
            $table->enum('enrollment_status', ['enrolled', 'dropped', 'completed', 'failed', 'withdrawn'])->default('enrolled');

            // Section/Batch Assignment
            $table->string('section')->nullable();
            $table->string('batch')->nullable(); // For lab batches

            // Grades
            $table->decimal('internal_marks', 5, 2)->nullable();
            $table->decimal('external_marks', 5, 2)->nullable();
            $table->decimal('total_marks', 5, 2)->nullable();
            $table->string('grade', 2)->nullable(); // A+, A, B+, etc.
            $table->decimal('grade_points', 3, 2)->nullable();
            $table->enum('result_status', ['pass', 'fail', 'absent', 'pending'])->nullable();

            // Attendance Statistics
            $table->integer('total_classes')->default(0);
            $table->integer('classes_attended')->default(0);
            $table->decimal('attendance_percentage', 5, 2)->default(0);

            $table->timestamps();

            // Unique constraint
            $table->unique(['student_id', 'course_id', 'academic_year', 'semester'], 'sce_student_course_unique');

            // Indexes
            $table->index(['student_id', 'academic_year', 'semester'], 'sce_student_year_sem_idx');
            $table->index(['course_id', 'academic_year', 'semester'], 'sce_course_year_sem_idx');
            $table->index('enrollment_status', 'sce_enroll_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_course_enrollments');
    }
};
