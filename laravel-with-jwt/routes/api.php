<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Http\Controllers\Api\Auth\PasswordController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
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
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('password', [PasswordController::class, 'update'])->name('password');
    Route::post('forgot-password', [ResetPasswordController::class, 'createResetPasswordToken'])->name('forgot-password');
    Route::post('reset-password', [ResetPasswordController::class, 'resetPassword'])->name('reset-password');

    Route::get('refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth:api'])->group(function () {
    Route::prefix('profile')->name('profile.')->middleware('verify')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    Route::resource('posts', PostController::class)->except(['create', 'edit'])->names('api.posts');

    Route::prefix('email')->group(function () {
        Route::get('/verify/{id}/{hash}', [EmailVerificationController::class, 'verifyEmail'])
            ->middleware(['signed'])->name('verification.verify');
        Route::post('/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->name('verification.send');
    });
});
