<?php
// database/migrations/2024_01_01_000005_create_course_assignments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('faculty_id')->constrained('faculty')->onDelete('cascade');
            $table->string('academic_year', 10); // "2024-2025"
            $table->enum('semester', ['odd', 'even', 'summer']);

            // Assignment Details
            $table->boolean('is_course_coordinator')->default(false);
            $table->boolean('is_primary')->default(true);
            $table->enum('role', ['lecturer', 'tutor', 'lab_instructor', 'teaching_assistant'])->default('lecturer');

            // Section/Division Details
            $table->string('section')->nullable(); // A, B, C or null for all
            $table->string('batch')->nullable(); // For lab batches
            $table->integer('max_students')->nullable();

            // Schedule
            // $table->json('time_slots')->nullable(); // Array of time slots
            // $table->string('room_number')->nullable();

            $table->timestamps();

            // Unique constraint
            $table->unique(['course_id', 'faculty_id', 'academic_year', 'semester', 'section'], 'unique_course_assignment');

            // Indexes
            $table->index(['faculty_id', 'academic_year']);
            $table->index(['course_id', 'academic_year', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_assignments');
    }
};
