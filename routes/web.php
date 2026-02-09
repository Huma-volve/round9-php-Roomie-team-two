<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\NotificationController;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'create'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'store'])->name('admin.login.store');
    Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('admin.logout')->middleware('auth');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    
    // ============================
    // 🔔 Notification Routes
    // ============================
    Route::prefix('notifications')->name('notifications.')->group(function () {
        // عرض كل الإشعارات
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        
        // تعليم إشعار واحد مقروء
        Route::post('/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
        
        // تعليم كل الإشعارات مقروءة
        Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
        
        // حذف إشعار
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        
        // جلب عدد الإشعارات الغير مقروءة (AJAX)
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
        
        // جلب آخر 10 إشعارات (للـ Dropdown)
        Route::get('/recent', [NotificationController::class, 'getRecent'])->name('recent');
    });

    // Other admin routes...
});

Route::get('/dashboard', function () {
    return view('blank'); 
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('booking/calculate-price', [BookingController::class, 'calculateTotalPrice']);
    Route::post('bookings', [BookingController::class, 'store']);
    Route::get('bookings', [BookingController::class, 'getUserBookings']);
    Route::get('bookings/{booking}', [BookingController::class, 'show']);
    Route::delete('bookings/{booking}', [BookingController::class, 'cancel']);
});