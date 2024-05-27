<?php

namespace App\Repositories\Schedule;

use Illuminate\Http\Request;

interface ScheduleRepositoryInterface
{
    public const CACHE_KEY = 'schedules';

    public const STATUS = [
        'draft' => [
            'value' => 0,
            'label' => 'Draft'
        ],
        'scheduled' => [
            'value' => 1,
            'label' => 'Scheduled'
        ],
        'sent' => [
            'value' => 2,
            'label' => 'Sent'
        ],
        'failed' => [
            'value' => 3,
            'label' => 'Failed'
        ],
    ];

    public const TYPE = [
        'cc' => [
            'value' => 0,
            'label' => 'CC'
        ],
        'bcc' => [
            'value' => 1,
            'label' => 'BCC'
        ],
    ];

    public function updateStatus(string|array $id, $status);

    public function getFromCacheGroupByStatus($status = null);

    public function updateStatusToCache(string|array $id, $status);
}
