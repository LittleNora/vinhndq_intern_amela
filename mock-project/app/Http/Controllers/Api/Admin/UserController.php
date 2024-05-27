<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\User\StoreRequest;
use App\Http\Requests\Api\Admin\User\UpdateRequest;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Traits\TLog;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use TLog;

    public function __construct(
        private readonly UserRepositoryInterface $modelRepo
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $users = $this->modelRepo->list($request, ['division']);

            return responseApi(['data' => $users]);
        } catch (\Exception $e) {
            $this->log($e->getMessage(), 'error');

            return responseApi([
                'message' => __('Failed to get users data!')
            ], false, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = $this->modelRepo->create($request->validated(), ['division']);

            event(new Registered($user));

            DB::commit();

            return responseApi([
                'data' => $user,
                'message' => __('User created successfully!')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->log($e->getMessage(), 'error');

            return responseApi([
                'message' => __('Failed to create user!')
            ], false, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            if (!$user = $this->modelRepo->find($id, ['division'])) {
                return responseApi([
                    'message' => __('User not found!')
                ], false, 404);
            }

            return responseApi(['data' => $user]);
        } catch (\Exception $e) {
            $this->log($e->getMessage(), 'error');

            return responseApi([
                'message' => __('Failed to get user data!')
            ], false, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        DB::beginTransaction();

        try {
            if (!$user = $this->modelRepo->update($id, $request->validated(), ['division'])) {
                DB::rollBack();

                return responseApi([
                    'message' => __('User not found!')
                ], false, 404);
            }

            DB::commit();

            return responseApi([
                'data' => $user,
                'message' => __('User updated successfully!')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->log($e->getMessage(), 'error');

            return responseApi([
                'message' => __('Failed to update user!')
            ], false, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            if (!$this->modelRepo->delete(fn() => $this->modelRepo->findBy(['id' => $id, ['id', '!=', auth()->id()]]))) {
                DB::rollBack();

                return responseApi([
                    'message' => __('User not found!')
                ], false, 404);
            }

            DB::commit();

            return responseApi([
                'message' => __('User deleted successfully!')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->log($e->getMessage(), 'error');

            return responseApi([
                'message' => __('Failed to delete user!')
            ], false, 500);
        }
    }

    public function trash(Request $request)
    {
        try {
            $users = $this->modelRepo->trashedList($request, ['division']);

            return responseApi(['data' => $users]);
        } catch (\Exception $e) {
            $this->log($e->getMessage(), 'error');

            return responseApi([
                'message' => __('Failed to get trashed users data!')
            ], false, 500);
        }
    }

    public function restore(string $id)
    {
        DB::beginTransaction();

        try {
            if (!$this->modelRepo->restore($id)) {
                DB::rollBack();

                return responseApi([
                    'message' => __('User not found!')
                ], false, 404);
            }

            DB::commit();

            return responseApi([
                'message' => __('User restored successfully!')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->log($e->getMessage(), 'error');

            return responseApi([
                'message' => __('Failed to restore user!')
            ], false, 500);
        }
    }
}
