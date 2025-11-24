<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;

class FaceRecognitionService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.face_recognition.url', 'http://127.0.0.1:8000');
    }

    public function detectFace(UploadedFile $image)
    {
        $response = Http::attach(
            'file', file_get_contents($image->getRealPath()), $image->getClientOriginalName()
        )->post("{$this->baseUrl}/detect");

        if ($response->failed()) {
            return null;
        }

        return $response->json();
    }

    public function getEmbedding(UploadedFile $image)
    {
        $response = Http::attach(
            'file', file_get_contents($image->getRealPath()), $image->getClientOriginalName()
        )->post("{$this->baseUrl}/embed");

        if ($response->failed()) {
            return null;
        }

        return $response->json();
    }


    public function verifyFaces(array $sourceEmbedding, array $targetEmbedding)
    {
        $response = Http::post("{$this->baseUrl}/verify", [
            'source_embedding' => $sourceEmbedding,
            'target_embedding' => $targetEmbedding,
        ]);

        if ($response->failed()) {
            return null;
        }

        return $response->json();
    }

    public function extractEmbedding(string $base64Image)
    {
        // Remove data:image/jpeg;base64, prefix if present
        $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
        
        // Decode base64 to binary
        $imageData = base64_decode($base64Image);
        
        // Create a temporary file
        $tempFile = tmpfile();
        $tempFilePath = stream_get_meta_data($tempFile)['uri'];
        fwrite($tempFile, $imageData);
        
        // Send to Python service
        $response = Http::attach(
            'file', $imageData, 'face.jpg'
        )->post("{$this->baseUrl}/embed");

        // Close temp file
        fclose($tempFile);

        if ($response->failed()) {
            throw new \Exception('Python service returned error: ' . $response->body());
        }

        return $response->json();
    }

    public function identify(string $base64Image, array $candidates)
    {
        // Remove prefix if present
        $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);

        $response = Http::post("{$this->baseUrl}/identify", [
            'probe_image' => $base64Image,
            'candidates' => $candidates,
            'threshold' => 0.4, // Adjust threshold as needed
        ]);

        if ($response->failed()) {
            return null;
        }

        return $response->json();
    }
}
