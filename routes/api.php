<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::post('/login', AuthController::class.'@login')
    ->name('auth.login');
Route::post('/register', AuthController::class.'@register')
    ->name('auth.register');
Route::middleware('auth:sanctum')
    ->post('/logout', AuthController::class.'@logout')
    ->name('auth.logout');
