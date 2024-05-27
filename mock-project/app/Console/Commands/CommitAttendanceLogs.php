<?php

namespace App\Console\Commands;

use App\Repositories\Attendance\AttendanceRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommitAttendanceLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:commit-attendance-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var \Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application|mixed
     */
    private mixed $attendanceRepository;

    public function __construct()
    {
        parent::__construct();

        $this->attendanceRepository = app(AttendanceRepositoryInterface::class);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $attendances = $this->attendanceRepository->getFromCache();

        DB::beginTransaction();

        try {
            Log::info('Committing attendance logs');

            Log::info('Attendances: ' . $attendances->toJson());

            $this->attendanceRepository->createMany($attendances->toArray());

            $this->attendanceRepository->flushCache();

            Log::info('Attendance logs committed');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            Log::info('Failed to commit attendance logs');

            Log::error($e->getMessage());
        }
    }
}
