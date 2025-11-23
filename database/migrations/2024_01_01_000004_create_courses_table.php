<?php
// database/migrations/2024_01_01_000004_create_courses_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_code', 20)->unique();
            $table->string('course_name', 100);
            $table->text('course_description')->nullable();
            $table->foreignId('department_id')->constrained()->onDelete('restrict');

            // Course Details
            $table->integer('year_level')->nullable(); // 1, 2, 3, 4
            $table->integer('semester')->nullable(); // 1-8
            $table->integer('credits')->nullable();
            // $table->enum('course_type', [''])->nullable()->default();
            $table->enum('category', ['theory', 'practical', 'theory_practical'])->default('theory')->nullable();



            // Status
            // $table->boolean('is_active')->default(true);
            // $table->string('academic_year', 10)->nullable();

            $table->timestamps();

            // // Indexes
            // $table->index('course_code');
            // $table->index(['department_id', 'year_level']);
            // $table->index(['department_id', 'semester']);
            // $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
