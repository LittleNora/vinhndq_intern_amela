<?php

namespace App\Jobs;

use App\Enums\QueueChannel;
use App\Mail\ScheduleMail;
use App\Repositories\Schedule\ScheduleRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendScheduleEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $schedule;

    /**
     * Create a new job instance.
     */
    public function __construct($schedule)
    {
        //
        $this->onQueue(QueueChannel::SCHEDULE->value);

        $this->schedule = $schedule;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        Mail::to($this->schedule->send_to)
            ->cc($this->schedule->recipients->where('type', ScheduleRepositoryInterface::TYPE['cc']['value'])->pluck('email')->toArray())
            ->bcc($this->schedule->recipients->where('type', ScheduleRepositoryInterface::TYPE['bcc']['value'])->pluck('email')->toArray())
            ->send(new ScheduleMail($this->schedule));
    }

    /**
     * Handle a job failure.
     */
    public function failed(?Throwable $exception): void
    {
        app(ScheduleRepositoryInterface::class)->updateStatusToCache($this->schedule->id, config('util.schedule.statuses.failed'));
    }
}
