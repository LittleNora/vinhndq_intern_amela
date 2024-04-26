<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class ProfileController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/profile",
     *     summary="Get user profile",
     *     tags={"Profile"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200", description="Success, return user profile"),
     *     @OA\Response(response="400", description="Error, return error message")
     * )
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $user = $request->user();

            return response()->json(['user' => $user]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json(['error' => __('Unable to get user profile')], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/profile",
     *     summary="Update user profile",
     *     tags={"Profile"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name",
     *                     maxLength=255
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="Email",
     *                     format="email",
     *                     maxLength=255
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Profile updated successfully"),
     *     @OA\Response(response="400", description="Error, return error message")
     * )
     */
    public function update(UpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = $request->user();

            $user->fill($request->validated());

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            DB::commit();

            return response()->json([
                'message' => __('Profile updated successfully'),
                'data' => $user
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error($e->getMessage());

            return response()->json(['error' => __('Unable to update profile')], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/profile",
     *     summary="Delete user profile",
     *     tags={"Profile"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="Current password"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="User deleted successfully"),
     *     @OA\Response(response="400", description="Error, return error message")
     * )
     */
    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'current_password'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        DB::beginTransaction();

        try {
            $user = $request->user();

            auth('api')->logout();

            $user->delete();

            DB::commit();

            return response()->json(['message' => __('User deleted successfully')]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error($e->getMessage());

            return response()->json(['error' => __('Unable to delete user')], 500);
        }
    }
}
