<?php
// database/migrations/2024_01_01_000003_create_students_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faculty', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('student_id', 20)->unique(); // Roll number/Registration number
            $table->foreignId('department_id')->constrained()->onDelete('restrict');

            // Academic Information
          
           
        


            // Face Recognition Data

            $table->timestamps();

           
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
