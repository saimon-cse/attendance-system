<?php

namespace App\Http\Controllers;

use App\Services\FaceRecognitionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FaceRecognitionController extends Controller
{
    protected $faceService;

    public function __construct(FaceRecognitionService $faceService)
    {
        $this->faceService = $faceService;
    }

    public function detect(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240', // 10MB max
        ]);

        $result = $this->faceService->detectFace($request->file('image'));

        if (!$result) {
            return response()->json(['error' => 'Face detection failed'], 500);
        }

        return response()->json($result);
    }

    public function enroll(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|max:10240',
                'student_id' => 'required', // Removed exists check for demo
            ]);

            $result = $this->faceService->getEmbedding($request->file('image'));

            if (!$result || isset($result['message'])) {
                return response()->json([
                    'error' => 'Could not extract face embedding',
                    'python_response' => $result
                ], 400);
            }

            // Create or update student (simplified for demo)
            // In a real app, you'd likely look up an existing student
            $student = \App\Models\Student::firstOrCreate(
                ['student_id' => $request->student_id],
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

            // Save Face Sample
            $student->faceSamples()->create([
                'image_path' => 'path/to/image.jpg', // We should save the file
                'face_encoding' => json_encode($result['embedding']),
                'quality_score' => $result['det_score'], // Using detection score as proxy for quality
                'face_confidence' => $result['det_score'],
                'is_primary' => true,
            ]);
            
            return response()->json([
                'message' => 'Face enrolled successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server Error',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function identify(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240',
        ]);

        // 1. Get embedding from Python service
        $result = $this->faceService->getEmbedding($request->file('image'));

        if (!$result || isset($result['message'])) {
            return response()->json([
                'error' => 'Could not extract face embedding',
                'python_response' => $result
            ], 400);
        }

        $inputEmbedding = $result['embedding'];
        $bestMatch = null;
        $highestSimilarity = -1;
        $threshold = 0.5; // Similarity threshold (adjustable)

        // 2. Fetch all face samples (Optimize this for production!)
        // In production, use a vector DB or raw SQL cosine similarity if supported
        $samples = \App\Models\FaceSample::with('student')->get();

        foreach ($samples as $sample) {
            $storedEmbedding = json_decode($sample->face_encoding);
            
            // Calculate Cosine Similarity
            $similarity = $this->calculateSimilarity($inputEmbedding, $storedEmbedding);

            if ($similarity > $highestSimilarity) {
                $highestSimilarity = $similarity;
                $bestMatch = $sample;
            }
        }

        if ($highestSimilarity > $threshold && $bestMatch) {
            return response()->json([
                'message' => 'Face identified',
                'student' => $bestMatch->student,
                'similarity' => $highestSimilarity,
                'bbox' => $result['bbox']
            ]);
        }

        return response()->json([
            'message' => 'Unknown person',
            'similarity' => $highestSimilarity,
            'bbox' => $result['bbox']
        ]);
    }

    private function calculateSimilarity($vecA, $vecB)
    {
        // Dot product
        $dotProduct = 0;
        $normA = 0;
        $normB = 0;

        foreach ($vecA as $i => $val) {
            $dotProduct += $val * $vecB[$i];
            $normA += $val * $val;
            $normB += $vecB[$i] * $vecB[$i];
        }

        if ($normA == 0 || $normB == 0) return 0;

        return $dotProduct / (sqrt($normA) * sqrt($normB));
    }
}
