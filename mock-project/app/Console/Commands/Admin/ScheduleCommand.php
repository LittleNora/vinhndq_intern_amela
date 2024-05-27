<?php

namespace App\Console\Commands\Admin;

use App\Models\Schedule;
use App\Repositories\Schedule\ScheduleRepositoryInterface;
use App\Services\Traits\TLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ScheduleCommand extends Command
{
    use TLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:schedule-command';

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
        $schedules = $this->modelRepo->getFromCacheByDate();

        $this->log('Schedules to cache: ' . $schedules->count());

        $this->modelRepo->cache($schedules);
    }
}
