<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Attendance\AttendanceRepositoryInterface;
use App\Services\Traits\TLog;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    use TLog;

    public function __construct(
        private AttendanceRepositoryInterface $modelRepo
    )
    {
    }

    public function index(Request $request)
    {
        try {
            $attendances = $this->modelRepo->indexForStaff($request);

            return responseApi($attendances->paginate());
        } catch (\Exception $e) {
            return responseApi($e->getMessage(), false, 500);
        }
    }

    public function missed(Request $request)
    {
        try {
            $attendances = $this->modelRepo->indexForStaff($request)->whereNull('check_out');

            return responseApi($attendances->paginate());
        } catch (\Exception $e) {
            return responseApi($e->getMessage(), false, 500);
        }
    }

    public function logAttendance(Request $request)
    {
        try {
            $this->modelRepo->log($request);

            return responseApi('Attendance logged successfully');
        } catch (\Exception $e) {
            return responseApi($e->getMessage(), false, 500);
        }
    }
}
