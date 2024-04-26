<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class PostController extends Controller
{
    private Model $model;

    public function __construct()
    {
        $this->model = new Post();
    }

    /**
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Get posts list",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200", description="Success, return data list"),
     *     @OA\Response(response="500", description="Error, return error message")
     * )
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        //
        try {
            $data = $this->model->all();

            return response()->json($data, 200);
        } catch (\Exception $e) {
            Log::error(__('Error') . ': ' . $e->getMessage());

            return response()->json(['message' => __('Error') . ': ' . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/posts",
     *     summary="Create new posts",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Title",
     *                     maxLength=255
     *                 ),
     *                 @OA\Property(
     *                     property="content",
     *                     type="string",
     *                     description="Content"
     *                 ),
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="integer",
     *                     description="User ID",
     *                     format="int32"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response="201", description="Created successfully"),
     *     @OA\Response(response="400", description="Error, return error list"),
     *     @OA\Response(response="500", description="Error, return error message")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'user_id' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        DB::beginTransaction();

        try {
            $data = $this->model->create($request->all());

            DB::commit();

            return response()->json($data, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error(__('Error') . ': ' . $e->getMessage());

            return response()->json(['message' => __('Error') . ': ' . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     summary="Get post by ID",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Success, return data"),
     *     @OA\Response(response="404", description="Data not found"),
     *     @OA\Response(response="500", description="Error, return error message")
     * )
     */
    public function show(string $id)
    {
        //
        try {
            $data = $this->model::query()->find($id);

            if ($data) {
                return response()->json($data, 200);
            }

            return response()->json(['message' => __('Data not found')], 404);
        } catch (\Exception $e) {
            Log::error(__('Error') . ': ' . $e->getMessage());

            return response()->json(['message' => __('Error') . ': ' . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/posts/{id}",
     *     summary="Update post by ID",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Title",
     *                     maxLength=255
     *                 ),
     *                 @OA\Property(
     *                     property="content",
     *                     type="string",
     *                     description="Content"
     *                 ),
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="integer",
     *                     description="User ID",
     *                     format="int32"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Updated successfully"),
     *     @OA\Response(response="400", description="Error, return error list"),
     *     @OA\Response(response="404", description="Data not found"),
     *     @OA\Response(response="500", description="Error, return error message")
     * )
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'user_id' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        DB::beginTransaction();

        try {
            $data = $this->model::query()->find($id);

            if ($data) {
                $data->update($request->all());

                DB::commit();

                return response()->json($data, 200);
            }

            return response()->json(['message' => __('Data not found')], 404);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error(__('Error') . ': ' . $e->getMessage());

            return response()->json(['message' => __('Error') . ': ' . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     summary="Delete post by ID",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Data deleted"),
     *     @OA\Response(response="404", description="Data not found"),
     *     @OA\Response(response="500", description="Error, return error message")
     * )
     */
    public function destroy(string $id)
    {
        //
        DB::beginTransaction();

        try {
            $data = $this->model::query()->find($id);

            if ($data) {
                $data->delete();

                DB::commit();

                return response()->json(['message' => __('Data deleted')], 200);
            }

            return response()->json(['message' => __('Data not found')], 404);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error(__('Error') . ': ' . $e->getMessage());

            return response()->json(['message' => __('Error') . ': ' . $e->getMessage()], 500);
        }
    }
}
