<?php

namespace App\Jobs;

use App\Models\Attendance;
use App\Repositories\Attendance\AttendanceRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LogAttendance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    private AttendanceRepositoryInterface $attendanceRepository;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        //
        $this->data = $data;

        $this->attendanceRepository = app(AttendanceRepositoryInterface::class);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $attendance = $this->attendanceRepository->getFromCache($this->data['user_id'], 'user_id');

        $attendanceIsNotEmpty = $attendance->isNotEmpty();

        $logType = $attendanceIsNotEmpty ? AttendanceRepositoryInterface::CHECK_OUT : AttendanceRepositoryInterface::CHECK_IN;

        $timestampType = !$attendanceIsNotEmpty ? 'updated_at' : 'created_at';

        $attendance = !$attendanceIsNotEmpty ? $attendance : collect();

        $attendance->put('user_id', $this->data['user_id']);

        $attendance->put($logType, $this->data['time']);

        $attendance->put('date', $this->data['date']);

        $attendance->put($timestampType, now());

        $this->attendanceRepository->cacheOneItem($attendance, 'user_id');
    }
}
