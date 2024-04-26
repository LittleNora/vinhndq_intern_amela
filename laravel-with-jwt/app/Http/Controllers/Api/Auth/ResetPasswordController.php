<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use OpenApi\Annotations as OA;

class ResetPasswordController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/forgot-password",
     *     summary="Create reset password token",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="Email",
     *                     format="email"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Reset password link sent"),
     *     @OA\Response(response="401", description="Invalid email, return error message"),
     *     @OA\Response(response="500", description="Unable to send reset password link, return error message")
     * )
     */
    public function createResetPasswordToken(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status === Password::RESET_LINK_SENT
                ? response()->json(['message' => __('Reset password link sent on your email id.')])
                : response()->json(['error' => __('Unable to send reset password link')], 500);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json(['error' => __('Unable to send reset password link')], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/auth/reset-password",
     *     summary="Reset user password",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="token",
     *                     type="string",
     *                     description="Reset password token"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="Email",
     *                     format="email"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="New password",
     *                     minLength=6
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     type="string",
     *                     description="Confirm new password",
     *                     minLength=6
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Password reset successfully"),
     *     @OA\Response(response="400", description="Invalid input data, return validation errors"),
     *     @OA\Response(response="500", description="Unable to reset password, return error message")
     * )
     */
    public function resetPassword(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        DB::beginTransaction();

        try {
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                    ])->save();

                    event(new PasswordReset($user));
                }
            );

            DB::commit();

            return $status === Password::PASSWORD_RESET
                ? response()->json(['message' => __('Password has been reset successfully.')])
                : response()->json(['error' => __('Unable to reset password.')], 500);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error($e->getMessage());

            return response()->json(['error' => __('Unable to reset password.')], 500);
        }
    }
}
