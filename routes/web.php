<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/chat/{id}', function ($id) {
    return view('chat');
});

Route::get('/profile', function () {
    return view('profile');
});

Route::get('/list-conversation', function () {
    return view('conversation.list-conversation');
});

Route::get('/create-conversation', function () {
    return view('conversation.create-conversation');
});

Route::get('/conversation/{id}', function ($id) {
    return view('conversation');
});