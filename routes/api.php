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


// OTP Management Routes
Route::prefix('otp')->group(function () {
    Route::post('/verify', [OtpController::class, 'verify']);
    Route::post('/verify-reset', [OtpController::class, 'verifyResetOtp']);
    Route::post('/resend', [OtpController::class, 'resendOtp'])
        ->middleware('throttle:3,1');;
});



Route::get('/auth/google/redirect', [SocialAuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);
