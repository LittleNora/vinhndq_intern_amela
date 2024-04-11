<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('author')->name('author.')->group(function () {
    Route::middleware('auth:author')->group(function () {
        Route::get('dashboard', function () {
            return view('author.dashboard');
        })->name('dashboard');
    });
});

Route::middleware('auth:admin')->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('dashboard', function () {

            $posts = App\Models\Post::query()->orderByDesc('id')->get();

            return view('admin.dashboard', compact('posts'));
        })->name('dashboard');

        Route::prefix('posts')->name('posts.')->group(function () {
            Route::get('create', [\App\Http\Controllers\PostController::class, 'create'])->name('create');
            Route::post('create', [\App\Http\Controllers\PostController::class, 'store'])->name('store');
            Route::get('{post}/edit', [\App\Http\Controllers\PostController::class, 'edit'])->name('edit')->middleware('can:update,post');
            Route::post('{post}', [\App\Http\Controllers\PostController::class, 'update'])->name('update')->middleware('can:update,post');
            Route::delete('{post}/delete', [\App\Http\Controllers\PostController::class, 'destroy'])->name('destroy')->middleware('can:delete,post');
        });
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
