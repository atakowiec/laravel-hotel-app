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
    ->name("login")
    ->middleware("guest");

Route::get('/register', [LoginController::class, 'create'])
    ->name("register")
    ->middleware("guest");

Route::get('/logout', [LoginController::class, 'destroy'])
    ->name("logout")
    ->middleware("auth");

Route::get('/profile', [ProfileController::class, 'index'])
    ->name("profile")
    ->middleware("auth");

Route::get('/admin', [AdminController::class, 'index'])
    ->name("admin")
    ->middleware("admin");

Route::get('/edit-room', [AdminController::class, 'create'])
    ->name("edit-room")
    ->middleware("admin");
