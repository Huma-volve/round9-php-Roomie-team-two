<?php

use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\SearchController;
 use App\Http\Controllers\RoomDetails\RoomDetailsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




Route::middleware('auth:sanctum')->group(function () {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/search', [SearchController::class, 'search']);
    Route::get('/room-details/{id}', [RoomDetailsController::class, 'getAllRoomDetails']);
});