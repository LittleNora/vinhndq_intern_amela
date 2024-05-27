<?php

namespace App\Enums;

enum QueueChannel: string
{
    case DEFAULT = 'default';

    case ATTENDANCE = 'attendance';

    case SCHEDULE = 'scheduleEmail';

    case AUTHENTICATION = 'auth';
}
