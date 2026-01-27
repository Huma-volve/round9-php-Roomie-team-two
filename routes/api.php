<?php

use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\HousingPreferenceController;
use App\Http\Controllers\Api\LifestyleTraitController;
use App\Http\Controllers\Api\VerificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Custom API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    
    // Profile Routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);
        Route::post('/basic-info', [ProfileController::class, 'updateBasicInfo']);
        Route::delete('/image', [ProfileController::class, 'deleteImage']);
    });

    // Housing Preferences Routes
    Route::prefix('housing-preferences')->group(function () {
        Route::get('/', [HousingPreferenceController::class, 'index']);
        Route::post('/', [HousingPreferenceController::class, 'store']);
        Route::get('/{id}', [HousingPreferenceController::class, 'show']);
        Route::put('/{id}', [HousingPreferenceController::class, 'update']);
        Route::delete('/{id}', [HousingPreferenceController::class, 'destroy']);
    });

    // Lifestyle Trait Routes
    Route::prefix('lifestyle-trait')->group(function () {
        Route::get('/', [LifestyleTraitController::class, 'show']);
        Route::post('/', [LifestyleTraitController::class, 'createOrUpdate']);
        Route::delete('/', [LifestyleTraitController::class, 'destroy']);
    });

    // Verification Routes
    Route::prefix('verification')->group(function () {
        Route::get('/status', [VerificationController::class, 'getStatus']);
        
        // Email verification
        Route::post('/email/send', [VerificationController::class, 'sendEmailVerification']);
        Route::post('/email/verify', [VerificationController::class, 'verifyEmail']);
        
        // Phone verification
        Route::post('/phone/send', [VerificationController::class, 'sendPhoneVerification']);
        Route::post('/phone/verify', [VerificationController::class, 'verifyPhone']);
        
        // ID document verification
        Route::post('/id/upload', [VerificationController::class, 'uploadIdDocument']);
        
        // Admin routes
        Route::middleware('admin')->group(function () {
            Route::post('/id/approve/{userId}', [VerificationController::class, 'approveId']);
            Route::post('/id/reject/{userId}', [VerificationController::class, 'rejectId']);
        });
    });
});