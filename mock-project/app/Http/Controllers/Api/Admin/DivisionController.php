<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Division\StoreRequest;
use App\Http\Requests\Api\Admin\Division\UpdateRequest;
use App\Repositories\Division\DivisionRepositoryInterface;
use App\Services\Traits\TLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DivisionController extends Controller
{
    use TLog;

    public function __construct(
        private readonly DivisionRepositoryInterface $modelRepo
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $divisions = $this->modelRepo->list($request);

            return responseApi(['data' => $divisions]);
        } catch (\Exception $e) {
            $this->log($e->getMessage(), 'error');

            return responseApi([
                'message' => __('Failed to get divisions data!')
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
            $division = $this->modelRepo->create($request->validated());

            DB::commit();

            return responseApi([
                'data' => $division,
                'message' => __('Division created successfully!')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->log($e->getMessage(), 'error');

            return responseApi([
                'message' => __('Failed to create division!')
            ], false, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            if (!$division = $this->modelRepo->find($id)) {
                return responseApi([
                    'message' => __('Division not found!')
                ], false);
            }

            return responseApi(['data' => $division]);
        } catch (\Exception $e) {
            $this->log($e->getMessage(), 'error');

            return responseApi([
                'message' => __('Failed to get division data!')
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
            if (!$division = $this->modelRepo->update($id, $request->validated())) {
                DB::rollBack();

                return responseApi([
                    'message' => __('Division not found!')
                ], false, 404);
            }

            DB::commit();

            return responseApi([
                'data' => $division,
                'message' => __('Division updated successfully!')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->log($e->getMessage(), 'error');

            return responseApi([
                'message' => __('Failed to update division!')
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
            if (!$this->modelRepo->delete(fn() => $this->modelRepo->findBy(['id' => $id, 'is_default' => false]))) {
                DB::rollBack();

                return responseApi([
                    'message' => __('Failed to delete division!')
                ], false, 500);
            }

            DB::commit();

            return responseApi([
                'message' => __('Division deleted successfully!')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->log($e->getMessage(), 'error');

            return responseApi([
                'message' => __('Failed to delete division!')
            ], false, 500);
        }
    }

    public function trash(Request $request)
    {
        try {
            $divisions = $this->modelRepo->trashedList($request);

            return responseApi(['data' => $divisions]);
        } catch (\Exception $e) {
            $this->log($e->getMessage(), 'error');

            return responseApi([
                'message' => __('Failed to get trashed divisions data!')
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
                    'message' => __('Division not found!')
                ], false, 404);
            }

            DB::commit();

            return responseApi([
                'message' => __('Division restored successfully!')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->log($e->getMessage(), 'error');

            return responseApi([
                'message' => __('Failed to restore division!')
            ], false, 500);
        }
    }
}
