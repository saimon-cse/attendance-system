<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaceRecognitionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('face')->group(function () {
    Route::post('/detect', [FaceRecognitionController::class, 'detect']);
    Route::post('/enroll', [FaceRecognitionController::class, 'enroll']);
    Route::post('/identify', [FaceRecognitionController::class, 'identify']);
});
