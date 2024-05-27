<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Attendance\AttendanceRepositoryInterface;
use App\Services\Traits\TLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    //
    use TLog;

    public function __construct(
        private AttendanceRepositoryInterface $modelRepo
    )
    {
    }

    public function index(Request $request)
    {
        try {
            $attendances = $this->modelRepo->indexForAdmin($request, ['user']);

            return responseApi($attendances->paginate());
        } catch (\Exception $e) {
            $this->log($e->getMessage(), 'error');

            return responseApi($e->getMessage(), false, 500);
        }
    }

    public function show($id)
    {
        try {
            if (!$attendance = $this->modelRepo->find($id, ['user'])) {
                return responseApi([
                    'message' => __('Attendance not found!')
                ], false, 404);
            }

            return responseApi([
                'data' => $attendance
            ]);
        } catch (\Exception $e) {
            $this->log($e->getMessage(), 'error');

            return responseApi([
                'message' => __('Failed to show attendance!')
            ], false, 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'check_out' => 'required|date_format:H:i:s'
        ]);

        DB::beginTransaction();

        try {
            if (!$attendance = $this->modelRepo->update($id, $request->all())) {
                return responseApi([
                    'message' => __('Attendance not found!')
                ], false, 404);
            }

            DB::commit();

            return responseApi([
                'data' => $attendance,
                'message' => __('Attendance updated successfully!')
            ]);
        } catch (\Exception $e) {
            $this->log($e->getMessage(), 'error');

            DB::rollBack();

            return responseApi([
                'message' => __('Failed to update attendance!')
            ], false, 500);
        }
    }

    public function missed(Request $request)
    {
        try {
            $attendances = $this->modelRepo->indexForAdmin($request)->whereNull('check_out');

            return responseApi($attendances->paginate());
        } catch (\Exception $e) {
            $this->log($e->getMessage(), 'error');

            return responseApi($e->getMessage(), false, 500);
        }
    }
}
