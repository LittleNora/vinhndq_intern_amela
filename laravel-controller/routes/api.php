<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::resource('posts', \App\Http\Controllers\Api\PostController::class)
    ->except('create', 'edit')->names('api.posts');

Route::middleware('auth:sanctum')->group(function () {

    Route::get('user', function (Request $request) {
        return $request->user();
    });
});

Route::middleware('guest:sanctum')->post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ], [
        'email.required' => __('Email is required!'),
        'email.email' => __('Email is not valid!'),
        'password.required' => __('Password is required!')
    ]);

    if (Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
            'user' => Auth::user(),
            'token' => $request->user()->createToken('token-name')->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }

    return response()->json([
        'message' => __('Login failed!')
    ], 401);
});
