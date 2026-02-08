<?php

use App\Http\Controllers\Admin\AdminPaymentController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Api\BookingController;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'create'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'store'])->name('admin.login.store');
    Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('admin.logout')->middleware('auth');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');


    //======== Bookings Routes ======== //
    Route::get('bookings', [AdminBookingController::class, 'index'])->name('admin.bookings.index');
    Route::get('bookings/{booking}', [AdminBookingController::class, 'show'])->name('admin.bookings.show');
    Route::delete('bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('admin.bookings.cancel');
    Route::post('bookings/{booking}/confirm', [AdminBookingController::class, 'markAsConfirmed'])->name('admin.bookings.confirm');
    Route::post('bookings/{booking}/complete', [AdminBookingController::class, 'markAsCompleted'])->name('admin.bookings.complete');


    //======== Payments Routes ======== //
    Route::get('payments', [AdminPaymentController::class, 'index'])->name('admin.payments.index');
    Route::get('payments/{payment}', [AdminPaymentController::class, 'show'])->name('admin.payments.show');
    Route::post('payments/{payment}/mark-paid', [AdminPaymentController::class, 'markAsPaid'])->name('admin.payments.mark-paid');
    Route::post('payments/{payment}/mark-failed', [AdminPaymentController::class, 'markAsFailed'])->name('admin.payments.mark-failed');
    Route::post('payments/{payment}/refund', [AdminPaymentController::class, 'refundPayment'])->name('admin.payments.refund');
    Route::put('payments/{payment}/status', [AdminPaymentController::class, 'updateStatus'])->name('admin.payments.update-status');
});




// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('booking/calculate-price', [BookingController::class, 'calculateTotalPrice']);
//     Route::post('bookings', [BookingController::class, 'store']);
//     Route::get('bookings', [BookingController::class, 'getUserBookings']);
//     Route::get('bookings/{booking}', [BookingController::class, 'show']);
//     Route::delete('bookings/{booking}', [BookingController::class, 'cancel']);
// });