<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Schedule\StoreRequest;
use App\Repositories\Schedule\ScheduleRepositoryInterface;
use App\Services\Traits\TLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    use TLog;

    public function __construct(
        private readonly ScheduleRepositoryInterface $modelRepo
    )
    {
    }

    public function index(Request $request)
    {
        try {
            $schedules = $this->modelRepo->list($request, ['recipients']);

            return responseApi(['data' => $schedules]);
        } catch (\Exception $e) {
            $this->log($e->getMessage(), 'error');

            return responseApi([
                'message' => __('Failed to get schedules data!')
            ], false, 500);
        }
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $schedule = $this->modelRepo->create($request->all(), ['recipients', 'attachments']);

            DB::commit();

            return responseApi([
                'data' => $schedule,
                'message' => __('Schedule created successfully!')
            ], 201);
        } catch (\Exception $e) {
            $this->log($e->getMessage(), 'error');

            DB::rollBack();

            return responseApi([
                'message' => __('Failed to create schedule!')
            ], false, 500);
        }
    }

    public function show($id)
    {
        try {
            if (!$schedule = $this->modelRepo->find($id, ['recipients', 'attachments'])) {
                return responseApi([
                    'message' => __('Schedule not found!')
                ], false, 404);
            }

            return responseApi(['data' => $schedule]);
        } catch (\Exception $e) {
            $this->log($e->getMessage(), 'error');

            return responseApi([
                'message' => __('Failed to get schedule data!')
            ], false, 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            if (!$schedule = $this->modelRepo->update($id, $request->all(), ['recipients', 'attachments'])) {
                DB::rollBack();

                return responseApi([
                    'message' => __('Schedule not found!')
                ], false, 404);
            }

            DB::commit();

            return responseApi([
                'data' => $schedule,
                'message' => __('Schedule updated successfully!')
            ]);
        } catch (\Exception $e) {
            $this->log($e->getMessage(), 'error');

            DB::rollBack();

            return responseApi([
                'message' => __('Failed to update schedule!')
            ], false, 500);
        }
    }

    public function destroy($id)
    {
        try {
            if (!$this->modelRepo->delete($id)) {
                return responseApi([
                    'message' => __('Schedule not found!')
                ], false, 404);
            }

            return responseApi([
                'message' => __('Schedule deleted successfully!')
            ]);
        } catch (\Exception $e) {
            $this->log($e->getMessage(), 'error');

            return responseApi([
                'message' => __('Failed to delete schedule!')
            ], false, 500);
        }
    }
}
