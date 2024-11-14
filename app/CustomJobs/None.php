<?php

namespace App\CustomJobs;

use Illuminate\Support\Facades\Log;

class None
{
    public function executeNone()
    {
        Log::channel('background-jobs')->info('This message is called from ' . __METHOD__);
    }
}