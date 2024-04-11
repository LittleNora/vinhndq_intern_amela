<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\UpdateRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function __construct(
        private Post $model
    )
    {

    }

    //
    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->model->fill($request->validated());
            $this->model->user_id = auth('admin')->id();
            $this->model->save();
            DB::commit();
            return redirect()->route('admin.dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return back()->with('error', 'Failed to create post');
        }
    }

    public function detail($id)
    {
        $item = $this->model::query()->findOrFail($id);
        return view('admin.posts.detail', compact('item'));
    }

    public function edit(Post $post)
    {
//        $item = $this->model::query()->findOrFail($id);
        $item = $post;
        return view('admin.posts.edit', compact('item'));
    }

    public function update(UpdateRequest $request, Post $post)
    {
//        if (Gate::denies('update', $post)) {
//            return back()->with('error', 'You are not authorized to update this post');
//        }

        DB::beginTransaction();
        try {
            $post->fill($request->validated());
            $post->save();
            DB::commit();
            return redirect()->route('admin.dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return back()->with('error', 'Failed to update post');
        }
    }

    public function destroy(Post $post)
    {
        DB::beginTransaction();
        try {
//            $this->model = $this->model::query()->findOrFail($id);
            $post->delete();
            DB::commit();
            return redirect()->route('admin.dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return back()->with('error', 'Failed to delete post');
        }
    }
}
