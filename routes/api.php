<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConversationController;

Route::post('/login', [AuthController::class, 'login']);
Route::get('/user', [AuthController::class, 'user']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/chat', [ChatController::class, 'chat']);

// Conversation routes
Route::post('/conversations', [ConversationController::class, 'store']);
Route::get('/conversations', [ConversationController::class, 'getConversations']);
