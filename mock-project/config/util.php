<?php

return [
    'frontend_url' => env('FRONTEND_URL', 'http://localhost:3000'),
    'organization_email_domain' => env('ORGANIZATION_EMAIL_DOMAIN', 'amela.vn'),
    'default_password' => env('DEFAULT_PASSWORD'),
    'schedule' => [
        'cache_key' => 'schedules',
        'queue_name' => env('SCHEDULE_QUEUE_NAME', 'schedule'),
        'recipient_types' => [
            'cc' => 'cc',
            'bcc' => 'bcc',
        ],
        'statuses' => [
            'draft' => 'draft',
            'scheduled' => 'scheduled',
            'sent' => 'sent',
            'failed' => 'failed',
        ],
    ],
    'attendance' => [
        'cache_key' => 'attendances',
        'queue_name' => env('ATTENDANCE_QUEUE_NAME', 'attendance'),
    ],
];
