<?php

use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\SearchController;
use App\Http\Controllers\RoomDetails\RoomDetailsController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ---------------------------
// Routes (Home / Search / Room Details)
// ---------------------------
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/search', [SearchController::class, 'search']);
    Route::get('/room-details/{id}', [RoomDetailsController::class, 'getAllRoomDetails']);
});

// ---------------------------
// Authentication Routes
// ---------------------------

// Register a new user
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');

// Login
Route::post('/login', [AuthenticatedSessionController::class, 'login'])->name('login');

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'logout'])
    ->middleware('auth:sanctum')
    ->name('logout');

// ---------------------------
// Password Reset Routes
// ---------------------------
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');

// ---------------------------
// Email Verification Routes
// ---------------------------
Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth:sanctum', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth:sanctum', 'throttle:6,1'])
    ->name('verification.send');

// ---------------------------
// User Management Routes
// ---------------------------
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::delete('/user/destroy', [UserController::class, 'destroy'])->middleware('auth:sanctum');

// ---------------------------
// OTP Management Routes
// ---------------------------
Route::prefix('otp')->group(function () {
    Route::post('/verify', [OtpController::class, 'verify']);
    Route::post('/verify-reset', [OtpController::class, 'verifyResetOtp']);
    Route::post('/resend', [OtpController::class, 'resendOtp'])->middleware('throttle:3,1');
});

// ---------------------------
// Social Login Routes
// ---------------------------
Route::get('/auth/google/redirect', [SocialAuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);

// ---------------------------
// Bookings Routes (Auth Required)
// ---------------------------
Route::middleware('auth:sanctum')->group(function () {
    Route::post('booking/calculate-price', [BookingController::class, 'calculateTotalPrice']);
    Route::post('bookings', [BookingController::class, 'store']);
    Route::get('bookings', [BookingController::class, 'getUserBookings']);
    Route::get('bookings/{booking}', [BookingController::class, 'show']);
    Route::delete('bookings/{booking}', [BookingController::class, 'cancel']);
});
// --------------------------- 
// Review Routes 
// ---------------------------
Route::middleware('auth:sanctum')->group(function () {
Route::post('/bookings/{booking_id}/reviews', [ReviewsController::class, 'create']);
Route::put('/reviews/{review_id}', [ReviewsController::class, 'update']);
Route::delete('/reviews/{review_id}', [ReviewsController::class, 'delete']);
Route::get('/my-reviews', [ReviewsController::class, 'myReviews']);
});
