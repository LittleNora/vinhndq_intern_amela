<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class PasswordController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/password",
     *     summary="Update user password",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="current_password",
     *                     type="string",
     *                     description="Current password"
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
     *     @OA\Response(response="200", description="Password updated successfully"),
     *     @OA\Response(response="401", description="Unauthorized, return error message"),
     *     @OA\Response(response="500", description="Unable to update password, return error message")
     * )
     */
    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|current_password:api',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        DB::beginTransaction();

        try {
            $user = $request->user();

            $user->password = Hash::make($request->password);

            $user->save();

            DB::commit();

            return response()->json(['message' => __('Password has been updated successfully.')]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error($e->getMessage());

            return response()->json(['error' => __('Unable to update password')], 500);
        }

    }
}
