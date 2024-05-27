<?php

namespace App\Repositories\Attendance;

use Illuminate\Http\Request;

interface AttendanceRepositoryInterface
{
    public const CACHE_KEY = 'attendances';

    public const CHECK_IN = 'check_in';

    public const CHECK_OUT = 'check_out';

    public function log(Request$request);

    public function indexForAdmin(Request $request, $with = []);

    public function indexForStaff(Request $request);
}
