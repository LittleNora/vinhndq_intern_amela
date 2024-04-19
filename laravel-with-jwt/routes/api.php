<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('auth')->name('api.auth.')->group(function () {
    Route::post('register', [\App\Http\Controllers\Api\Auth\AuthController::class, 'register'])->name('register');
    Route::post('login', [\App\Http\Controllers\Api\Auth\AuthController::class, 'login'])->name('login');
    Route::post('password', [\App\Http\Controllers\Api\Auth\PasswordController::class, 'update'])->name('password');
    Route::post('forgot-password', [\App\Http\Controllers\Api\Auth\ResetPasswordController::class, 'createResetPasswordToken'])->name('forgot-password');
    Route::post('reset-password', [\App\Http\Controllers\Api\Auth\ResetPasswordController::class, 'resetPassword'])->name('reset-password');

    Route::get('refresh', [\App\Http\Controllers\Api\Auth\AuthController::class, 'refresh'])->name('refresh');
    Route::post('logout', [\App\Http\Controllers\Api\Auth\AuthController::class, 'logout'])->name('logout');
});

Route::middleware('auth:api')->group(function () {
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\ProfileController::class, 'index'])->name('index');
        Route::put('/', [\App\Http\Controllers\Api\ProfileController::class, 'update'])->name('update');
        Route::delete('/', [\App\Http\Controllers\Api\ProfileController::class, 'destroy'])->name('destroy');
    });

    Route::resource('posts', \App\Http\Controllers\Api\PostController::class)->except(['create', 'edit'])->names('api.posts');
});
