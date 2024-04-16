<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\UpdateRequest;
use App\Jobs\SendMailWhenCreatedNewPost;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{

    private Model $model;

    private string $root;

    public function __construct()
    {
        $this->authorizeResource(Post::class, 'post');

        $this->model = new Post();

        $this->root = 'posts';
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

//        $data = $this->model::query()->join('users', 'users.id', '=', 'posts.user_id')
//            ->select('posts.*', 'users.name as user_name')
//            ->orderByDesc('posts.id')
//            ->get();
//
//        dd($data);
//
//        return view("{$this->root}.index", [
//            'posts' => $data,
//        ]);

        return view("{$this->root}.index", [
            'posts' => $this->model::with('user:id,name')->orderByDesc('id')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        //
        DB::beginTransaction();
        try {

            $model = $this->model->fill($request->all());

            $model->user_id = auth()->user()->id;

            $model->save();

            DB::commit();

            $job = new SendMailWhenCreatedNewPost($model->user);

            dispatch($job);

            return response()->json([
                'message' => __('Data created successfully!'),
                'post' => $model->load('user:id,name'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error($e->getMessage());

            return response()->json([
                'message' => __('Failed to create data!'),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
        return response()->json([
            'post' => $post,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Post $post)
    {
        //
        DB::beginTransaction();
        try {
            $post->update($request->all());

            DB::commit();

            return response()->json([
                'message' => __('Data updated successfully!'),
                'post' => $post->load('user:id,name'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => __('Failed to update data!'),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
        DB::beginTransaction();
        try {
            $post->delete();

            DB::commit();

            return response()->json([
                'message' => __('Data deleted successfully!'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => __('Failed to delete data!'),
            ], 500);
        }
    }
}
