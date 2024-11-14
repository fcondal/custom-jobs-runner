<?php

namespace App\CustomJobs;

use Illuminate\Support\Facades\Log;

class Five
{
    public function executeFive($one, $two, $third, $fourth, $fifth)
    {
        Log::channel('background-jobs')->info('This message is called from ' . __METHOD__);
    }
}