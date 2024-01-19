<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RoomController::class, 'index'])
    ->name("home");

Route::get('/room/{id}', [RoomController::class, 'show'])
    ->name("room");

Route::get('/login', [LoginController::class, 'index'])
    ->name("login");

Route::get('/register', [LoginController::class, 'create'])
    ->name("register");

Route::get('/logout', [LoginController::class, 'destroy'])
    ->name("logout");

Route::get('/profile', [ProfileController::class, 'index'])
    ->name("profile")
    ->middleware("auth");

Route::get('/admin', [AdminController::class, 'index'])
    ->name("admin")
    ->middleware("auth");

Route::get('/edit-room', [AdminController::class, 'create'])
    ->name("edit-room")
    ->middleware("auth");
