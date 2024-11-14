<?php

namespace App\CustomJobs;

use Illuminate\Support\Facades\Log;

class Four
{
    public function executeFour($one, $two, $third, $fourth)
    {
        Log::channel('background-jobs')->info('This message is called from ' . __METHOD__);
    }
}