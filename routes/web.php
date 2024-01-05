<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RoomController::class, 'index']);

Route::get('/room/{id}', [RoomController::class, 'show']);

Route::get('/login', [LoginController::class, 'index']);

Route::get('/register', [LoginController::class, 'create']);

Route::get('/profile', [ProfileController::class, 'index']);
