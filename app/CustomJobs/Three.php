<?php

namespace App\CustomJobs;

use Illuminate\Support\Facades\Log;

class Three
{
    public function executeThree($one, $two, $third)
    {
        Log::channel('background-jobs')->info('This message is called from ' . __METHOD__);
    }
}