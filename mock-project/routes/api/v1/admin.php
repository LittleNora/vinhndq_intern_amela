<?php

use App\Http\Controllers\Api\Admin\AttendanceController;
use App\Http\Controllers\Api\Admin\DivisionController;
use App\Http\Controllers\Api\Admin\ScheduleController;
use App\Http\Controllers\Api\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('divisions')->name('divisions.')->group(function () {
    Route::get('/', [DivisionController::class, 'index'])->name('index');
    Route::get('/trash', [DivisionController::class, 'trash'])->name('trash');
    Route::post('/restore/{id}', [DivisionController::class, 'restore'])->name('restore');
    Route::post('/', [DivisionController::class, 'store'])->name('store');
    Route::get('{id}', [DivisionController::class, 'show'])->name('show')->where('id', '[0-9]+');
    Route::put('{id}', [DivisionController::class, 'update'])->name('update')->where('id', '[0-9]+');
    Route::delete('{id}', [DivisionController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
});

Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/trash', [UserController::class, 'trash'])->name('trash');
    Route::post('/restore/{id}', [UserController::class, 'restore'])->name('restore');
    Route::get('{id}', [UserController::class, 'show'])->name('show')->where('id', '[0-9]+');
    Route::put('{id}', [UserController::class, 'update'])->name('update')->where('id', '[0-9]+');
    Route::delete('{id}', [UserController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
});

Route::prefix('schedules')->name('schedules.')->group(function () {
    Route::get('/', [ScheduleController::class, 'index'])->name('index');
    Route::post('/', [ScheduleController::class, 'store'])->name('store');
    Route::get('{id}', [ScheduleController::class, 'show'])->name('show')->where('id', '[0-9]+');
    Route::put('{id}', [ScheduleController::class, 'update'])->name('update')->where('id', '[0-9]+');
//    Route::delete('{id}', [ScheduleController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
});

Route::prefix('attendances')->name('attendances.')->group(function () {
    Route::get('/', [AttendanceController::class, 'index'])->name('index');
    Route::get('missed', [AttendanceController::class, 'missed'])->name('missed');
    Route::get('{id}', [AttendanceController::class, 'show'])->name('show')->where('id', '[0-9]+');
    Route::put('{id}', [AttendanceController::class, 'update'])->name('update')->where('id', '[0-9]+');
});
