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
}
