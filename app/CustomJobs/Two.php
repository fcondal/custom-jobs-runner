<?php

namespace App\CustomJobs;

use Illuminate\Support\Facades\Log;

class Two
{
    public function executeTwo($one, $two)
    {
        Log::channel('background-jobs')->info('This message is called from ' . __METHOD__);
    }
}