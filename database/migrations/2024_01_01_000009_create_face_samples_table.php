<?php
// database/migrations/2024_01_01_000009_create_face_samples_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('face_samples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');

            // Image Details
            $table->string('image_path');


            // Face Encoding
            $table->text('face_encoding'); // 128-dimensional vector
            $table->string('encoding_model')->default('mobilefacenet'); // Model used for encoding
            $table->string('encoding_version')->default('1.0');

            // Quality Metrics
            $table->decimal('quality_score', 5, 4); // Overall quality 0-1
            $table->decimal('face_confidence', 5, 4); // Face detection confidence
            $table->decimal('sharpness_score', 5, 4)->nullable();
            $table->decimal('brightness_score', 5, 4)->nullable();
            $table->decimal('contrast_score', 5, 4)->nullable();

            // Face Attributes
            $table->enum('pose', ['frontal', 'left_profile', 'right_profile', 'up', 'down'])->default('frontal');
            $table->enum('lighting_condition', ['excellent', 'good', 'fair', 'poor'])->default('good');
            $table->boolean('has_mask')->default(false);
            $table->boolean('has_glasses')->default(false);
            $table->boolean('has_beard')->default(false);
            $table->json('facial_landmarks')->nullable(); // Eye, nose, mouth positions


            // Sample Status
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_active')->default(true);
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['student_id', 'is_primary', 'is_active']);
            $table->index('quality_score');
            $table->index('verification_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('face_samples');
    }
};
