<?php

namespace App\Support;

use App\Models\CustomJob;
use Exception;
use Illuminate\Support\Facades\Log;

if (!function_exists('runBackgroundJob')) {
    /**
     * @throws Exception
     */
    function runBackgroundJob(
        string $className,
        string $method,
        array $parameters = [],
        int $delay = 0,
        string $priority = CustomJob::PRIORITY_DEFAULT
    ): void {
        CustomJob::create([
            'class_name' => $className,
            'method'     => $method,
            'parameters' => $parameters,
            'delay'      => $delay,
            'status'     => $status = CustomJob::STATUS_PENDING,
            'priority'   => $priority,
        ]);

        Log::channel('background-jobs')->info("Custom job: $className::$method - Status: $status");
    }
}