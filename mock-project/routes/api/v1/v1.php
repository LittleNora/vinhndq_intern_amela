<?php

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
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

Route::middleware(['auth:api', 'verified'])->group(function () {
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'profile'])->name('show');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('avatar', [ProfileController::class, 'updateAvatar'])->name('update.avatar');
    });

    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::get('missed', [AttendanceController::class, 'missed'])->name('missed');
        Route::post('log', [AttendanceController::class, 'logAttendance'])->name('log');
    });

    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/search', [UserController::class, 'search'])->name('search');
        Route::get('{id}', [UserController::class, 'show'])->name('show');
    });
});

