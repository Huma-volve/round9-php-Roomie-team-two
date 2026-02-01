<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminUserController;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'create'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'store'])->name('admin.login.store');
    Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('admin.logout')->middleware('auth');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
});




Route::middleware('auth:sanctum')->group(function () {
    Route::post('booking/calculate-price', [BookingController::class, 'calculateTotalPrice']);
    Route::post('bookings', [BookingController::class, 'store']);
    Route::get('bookings', [BookingController::class, 'getUserBookings']);
    Route::get('bookings/{booking}', [BookingController::class, 'show']);
    Route::delete('bookings/{booking}', [BookingController::class, 'cancel']);
});
