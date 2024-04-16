<?php

use Illuminate\Support\Facades\Route;

// Admin Auth
use App\Http\Controllers\User\Auth\RegisterController;
use App\Http\Controllers\User\Auth\LoginController;
use App\Http\Controllers\User\Auth\ForgotPasswordController;
use App\Http\Controllers\User\Auth\ResetPasswordController;

Route::middleware('guest')->group(function () {

    // Register
    Route::get('register', [RegisterController::class, 'view'])
        ->name('register');
    Route::post('register', [RegisterController::class, 'post'])
        ->name('register.store');

    // Login
    Route::get('login', [LoginController::class, 'view'])
        ->name('login');
    Route::post('login', [LoginController::class, 'post'])
        ->name('login.store');

    // Forgot Password
    Route::get('forgot-password', [ForgotPasswordController::class, 'view'])
        ->name('forgot.password');
    Route::post('forgot-password', [ForgotPasswordController::class, 'post'])
        ->name('forgot.password.store');

    // Reset Password
    Route::get('reset/{email}/{token}', [ResetPasswordController::class, 'view'])
        ->name('password.reset');
    Route::post('reset', [ResetPasswordController::class, 'post'])
        ->name('password.reset.store');
});

Route::middleware('auth')->group(function () {
    // Logout
    Route::get('/logout', function () {
    })->name('logout');

    // User Dashboard
    Route::get('dashboard', function () {
    })->name('dashboard');
});
