<?php

namespace App\Console;

use App\Jobs\SendScheduleEmail;
use App\Repositories\Schedule\ScheduleRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;

class Kernel extends ConsoleKernel
{

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('app:schedule-command')
            ->before(fn() => Artisan::call('app:commit-schedule-changes-from-cache-to-database-at-end-day'))
            ->daily();

        $schedule->command('app:commit-attendance-logs')->daily();

        $schedule->command('app:schedule-by-minute-command')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
