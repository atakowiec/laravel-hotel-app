<?php

use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RoomController::class, 'index']);

Route::get('/room/{id}', [RoomController::class, 'show']);

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});

Route::get('/profile', function () {
    return view('profile');
});
