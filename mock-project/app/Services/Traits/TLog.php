<?php

namespace App\Services\Traits;

use Illuminate\Support\Facades\Log;

trait TLog
{

    public function log($message, $type = 'info')
    {
        Log::info(now()->format('H:i:s d-m-Y') . ' ' . str_repeat('=', 20));
        Log::$type($message);
        Log::info(str_repeat('=', 20));
    }
}
