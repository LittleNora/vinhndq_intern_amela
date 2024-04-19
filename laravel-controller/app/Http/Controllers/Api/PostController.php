<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\UpdateRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;
use Yajra\DataTables\Facades\DataTables;

class PostController extends Controller
{
    public function __construct(
        private Post $model,
    )
    {
        $this->authorizeResource(Post::class, 'post');
    }

    /**
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Get list posts",
     *     tags={"Post"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=500, description="Server Error"),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Header(
     *         header="Authorization",
     *         description="Bearer {token}",
     *         required=true,
     *         @OA\Schema(type="string")
     *     )
     * )
     */
    public function index()
    {
        return response()->json([
            'posts' => $this->model::with('user:id,name')->orderByDesc('id')->paginate(),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/posts",
     *     summary="Create a new post",
     *     tags={"Post"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Post data",
     *         @OA\JsonContent(
     *             required={"title", "content"},
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="content", type="string"),
     *         )
     *     ),
     *     @OA\Response(response=201, description="Created"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=500, description="Server Error"),
     * )
     */
    public function store(StoreRequest $request): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {
            $model = $this->model->fill($request->all());

            $model->user_id = auth()->id();

            $model->save();

            DB::commit();

            return response()->json([
                'message' => __('Data created successfully!'),
                'data' => $model,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error($e->getMessage());

            return response()->json([
                'message' => __('Failed to create data!'),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/posts/{post}",
     *     summary="Get a post by ID",
     *     tags={"Post"},
     *     security={{"sanctum": {}}, {"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=500, description="Server Error"),
     * )
     */
    public function show(Post $post): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'post' => $post,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/posts/{post}",
     *     summary="Update a post",
     *     tags={"Post"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Post data",
     *         @OA\JsonContent(
     *             required={"title", "content"},
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="content", type="string"),
     *         )
     *     ),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=500, description="Server Error"),
     * )
     */
    public function update(UpdateRequest $request, Post $post): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {
            $post->fill($request->all());

            $post->save();

            DB::commit();

            return response()->json([
                'message' => __('Data updated successfully!'),
                'data' => $post,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => __('Failed to update data!'),
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/posts/{post}",
     *     summary="Delete a post",
     *     tags={"Post"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="post",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response=204, description="No Content"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=500, description="Server Error"),
     * )
     */
    public function destroy(Post $post): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {
            $post->delete();

            DB::commit();

            return response()->json([
                'message' => __('Data deleted successfully!'),
            ], 204);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => __('Failed to delete data!'),
            ], 500);
        }
    }
}
