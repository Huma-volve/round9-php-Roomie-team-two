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
use App\Http\Controllers\Api\Profile\ProfileController;
use App\Http\Controllers\Api\Profile\HousingPreferenceController;
use App\Http\Controllers\Api\Profile\LifestyleTraitController;
use App\Http\Controllers\Api\Verification\EmailVerificationController;
use App\Http\Controllers\Api\Verification\PhoneVerificationController;
use App\Http\Controllers\Api\Verification\IdVerificationController;
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

   Route::prefix('verification/email')->group(function () {
        Route::post('/send', [EmailVerificationController::class, 'send']);
        Route::post('/verify', [EmailVerificationController::class, 'verify']);
        Route::get('/status', [EmailVerificationController::class, 'status']);
    });

    // Phone Verification Routes
    Route::prefix('verification/phone')->group(function () {
        Route::post('/send', [PhoneVerificationController::class, 'send']);
        Route::post('/verify', [PhoneVerificationController::class, 'verify']);
        Route::get('/status', [PhoneVerificationController::class, 'status']);
    });

    // ID Verification Routes
    Route::prefix('verification/id')->group(function () {
        Route::post('/upload', [IdVerificationController::class, 'upload']);
        Route::get('/status', [IdVerificationController::class, 'status']);
        
        // Admin routes
        Route::post('/approve/{userId}', [IdVerificationController::class, 'approve'])
            ->middleware('admin'); 
        Route::post('/reject/{userId}', [IdVerificationController::class, 'reject'])
            ->middleware('admin');
    });
});