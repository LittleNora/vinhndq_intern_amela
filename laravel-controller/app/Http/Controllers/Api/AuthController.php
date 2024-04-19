<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    //

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="User login",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User login credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=401, description="Invalid credentials"),
     * )
     */
    public function postLogin(Request $request): \Illuminate\Http\JsonResponse
    {
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
    }
}
