<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;


use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\HousingPreferenceController;
use App\Http\Controllers\Api\LifestyleTraitController;
use App\Http\Controllers\Api\VerificationController;
// ---------------------------
// Authentication Routes
// ---------------------------

// Register a new user
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->name('register');

// Login
Route::post('/login', [AuthenticatedSessionController::class, 'login'])
    ->name('login');

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'logout'])
    ->middleware('auth:sanctum')
    ->name('logout');

// ---------------------------
// Password Reset Routes
// ---------------------------

// Send password reset link
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');

// Reset password
Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->name('password.store');

// ---------------------------
// Email Verification Routes
// ---------------------------

// Verify email
Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth:sanctum', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

// Resend email verification notification
Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth:sanctum', 'throttle:6,1'])
    ->name('verification.send');

// ---------------------------
// User Management Routes
// ---------------------------

// Delete user account
Route::delete('/user/destroy', [UserController::class, 'destroy'])
    ->middleware('auth:sanctum');


Route::post('/verify-otp', [OtpController::class, 'verify']);
Route::post('/verify-otp-reset', [OtpController::class, 'verifyResetOtp']);
Route::post('/resend-otp', [OtpController::class, 'resendOtp']);



Route::get('/auth/google/redirect', [SocialAuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);

Route::middleware('auth:sanctum')->group(function () {
    
    // Profile Routes
    Route::prefix('profile')->group(function () {
        // Get profile
        Route::get('/', [ProfileController::class, 'show']);
        
        // Update basic info (name, job_title, gender, aboutme, image)
        Route::post('/basic-info', [ProfileController::class, 'updateBasicInfo']);
        
        // Update password
        Route::post('/password', [ProfileController::class, 'updatePassword']);
        
        // Delete profile image
        Route::delete('/image', [ProfileController::class, 'deleteImage']);
    });

    // Housing Preferences Routes (CRUD)
    Route::prefix('housing-preferences')->group(function () {
        Route::get('/', [HousingPreferenceController::class, 'index']);           // Get all
        Route::post('/', [HousingPreferenceController::class, 'store']);          // Create
        Route::get('/{id}', [HousingPreferenceController::class, 'show']);        // Get one
        Route::put('/{id}', [HousingPreferenceController::class, 'update']);      // Update
        Route::delete('/{id}', [HousingPreferenceController::class, 'destroy']);  // Delete
    });

    // Lifestyle Trait Routes (Single - Create/Update)
    Route::prefix('lifestyle-trait')->group(function () {
        Route::get('/', [LifestyleTraitController::class, 'show']);                      // Get
        Route::post('/', [LifestyleTraitController::class, 'createOrUpdate']);           // Create or Update
        Route::delete('/', [LifestyleTraitController::class, 'destroy']);                // Delete
    });

    // Verification Routes
    Route::prefix('verification')->group(function () {
        // Get verification status
        Route::get('/status', [VerificationController::class, 'getStatus']);
        
        // Email verification
        Route::post('/email/send', [VerificationController::class, 'sendEmailVerification']);
        Route::post('/email/verify', [VerificationController::class, 'verifyEmail']);
        
        // Phone verification
        Route::post('/phone/send', [VerificationController::class, 'sendPhoneVerification']);
        Route::post('/phone/verify', [VerificationController::class, 'verifyPhone']);
        
        // ID document verification
        Route::post('/id/upload', [VerificationController::class, 'uploadIdDocument']);
        
        // Admin routes (add admin middleware)
        Route::post('/id/approve/{userId}', [VerificationController::class, 'approveId']);
        Route::post('/id/reject/{userId}', [VerificationController::class, 'rejectId']);
    });
});