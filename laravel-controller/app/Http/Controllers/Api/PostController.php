<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\UpdateRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class PostController extends Controller
{
    public function __construct(
        private Post $model,
    )
    {
        $this->authorizeResource(Post::class, 'post');
    }

    public function index()
    {
        return response()->json([
            'posts' => $this->model::with('user:id,name')->orderByDesc('id')->paginate(),
        ]);
    }

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

    public function show(Post $post): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'post' => $post,
        ]);
    }

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
