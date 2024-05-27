<?php

namespace App\Console\Commands\Admin;

use App\Jobs\SendScheduleEmail;
use App\Repositories\Schedule\ScheduleRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ScheduleByMinuteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:schedule-by-minute-command';

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
        $cachedSchedules = $this->modelRepo->getFromCache()->filter(function ($schedule) {
            $send = Carbon::make($schedule->date . ' ' . $schedule->send_at)->format('Y-m-d H:i');

            $now = now()->format('Y-m-d H:i');

            return $send === $now;
        });


        foreach ($cachedSchedules as $cachedSchedule) {
            SendScheduleEmail::dispatch($cachedSchedule);
        }

        $this->modelRepo->updateStatusToCache($cachedSchedules->pluck('id')->toArray(), config('util.schedule.statuses.sent'));
    }
}
