<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\PasswordController;
use App\Http\Controllers\Api\Auth\VerificationController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->name('login');

Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('refresh', [AuthController::class, 'refresh'])->name('refresh');

Route::prefix('password')->name('password.')->group(function () {
    Route::put('/', [PasswordController::class, 'updatePassword'])->name('update')->middleware(['auth:api', 'verified']);
    Route::post('forgot', [PasswordController::class, 'createResetPasswordToken'])->name('forgot');
    Route::post('reset', [PasswordController::class, 'resetPassword'])->name('reset');
});

Route::prefix('email')->group(function () {
    Route::get('verify/{id}/{hash}', [VerificationController::class, 'verify'])->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::get('resend', [VerificationController::class, 'sendVerificationEmail'])->middleware('auth:api')
        ->name('verification.resend');
});
