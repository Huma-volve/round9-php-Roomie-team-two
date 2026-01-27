<?php

use App\Http\Controllers\ConversationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/conversations', [ConversationController::class, 'index']);
    Route::post('/conversations/start/{adminId}', [ConversationController::class, 'startConversation']); 
    Route::get('/conversations/{id}', [ConversationController::class, 'show']);
    
    Route::get('/message/search', [MessageController::class , 'search']); 
    Route::post('/message/store/{id}', [MessageController::class , 'store']);
});
