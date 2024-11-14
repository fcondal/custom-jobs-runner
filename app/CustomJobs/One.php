<?php

namespace App\CustomJobs;

use Illuminate\Support\Facades\Log;

class One
{
    public function executeOne($one)
    {
        Log::channel('background-jobs')->info('This message is called from ' . __METHOD__);
    }
}