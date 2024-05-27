<?php

namespace App\Observers;

use App\Models\ScheduleAttachment;
use App\Services\Traits\TUpload;

class ScheduleAttachmentObserve
{
    use TUpload;

    /**
     * Handle the ScheduleAttachment "created" event.
     */
    public function created(ScheduleAttachment $scheduleAttachment): void
    {
        //
    }

    /**
     * Handle the ScheduleAttachment "updated" event.
     */
    public function updated(ScheduleAttachment $scheduleAttachment): void
    {
        //
    }

    /**
     * Handle the ScheduleAttachment "deleted" event.
     */
    public function deleted(ScheduleAttachment $scheduleAttachment): void
    {
        //
        $this->deleteFile($scheduleAttachment->getRawOriginal('path'));
    }

    /**
     * Handle the ScheduleAttachment "restored" event.
     */
    public function restored(ScheduleAttachment $scheduleAttachment): void
    {
        //
    }

    /**
     * Handle the ScheduleAttachment "force deleted" event.
     */
    public function forceDeleted(ScheduleAttachment $scheduleAttachment): void
    {
        //
    }
}
