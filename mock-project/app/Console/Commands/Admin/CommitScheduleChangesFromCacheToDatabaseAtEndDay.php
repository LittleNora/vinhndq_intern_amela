<?php

namespace App\Console\Commands\Admin;

use App\Repositories\Schedule\ScheduleRepositoryInterface;
use App\Services\Traits\TLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CommitScheduleChangesFromCacheToDatabaseAtEndDay extends Command
{
    use TLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:commit-schedule-changes-from-cache-to-database-at-end-day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private ScheduleRepositoryInterface $modelRepo;

    public function __construct()
    {
        parent::__construct();

        $this->modelRepo = app(ScheduleRepositoryInterface::class);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $schedules = $this->modelRepo->getFromCacheGroupByStatus(['sent', 'failed']);

        $sentSchedules = $schedules->get('sent')->pluck('id')->toArray();

        $failedSchedules = $schedules->get('failed')->pluck('id')->toArray();

        DB::beginTransaction();

        try {
            $this->modelRepo->updateStatus($sentSchedules, config('util.schedule.statuses.sent'));

            $this->modelRepo->updateStatus($failedSchedules, config('util.schedule.statuses.failed'));

            $this->modelRepo->flushCache();

            DB::commit();

            $this->log('Commit schedule changes from cache to database at end day successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->log('Commit schedule changes from cache to database at end day failed: ' . $e->getMessage());
        }


    }
}
