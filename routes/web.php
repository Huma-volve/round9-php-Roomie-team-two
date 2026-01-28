<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

require __DIR__.'/auth.php';



Route::middleware('auth:sanctum')->group(function () {
    Route::post('booking/calculate-price', [BookingController::class, 'calculateTotalPrice']);
    Route::post('bookings', [BookingController::class, 'store']);
    Route::get('bookings', [BookingController::class, 'getUserBookings']);
    Route::get('bookings/{booking}', [BookingController::class, 'show']);
    Route::delete('bookings/{booking}', [BookingController::class, 'cancel']);
});
