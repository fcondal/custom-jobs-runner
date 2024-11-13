<?php

namespace App\Console\Commands;

use App\Actions\CustomJob\ValidateParameters;
use App\Models\CustomJob;
use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ExecuteCustomJob extends Command
{
    protected $signature   = 'custom-job:run';
    protected $description = 'Command to execute custom jobs';

    public function handle(): void
    {
        $customJob = CustomJob::where('status', CustomJob::STATUS_PENDING)
            ->whereRaw("created_at + INTERVAL delay SECOND <= NOW()")
            ->orderByRaw("FIELD(priority, 'high', 'default', 'low')")
            ->orderBy('created_at')
            ->first();

        if (!$customJob) {
            return;
        }

        $this->mainProcess($customJob);
    }

    private function mainProcess(CustomJob $customJob)
    {
        try {
            $this->updateCustomJobStatus($customJob, CustomJob::STATUS_PROCESSING);

            $validateParameters = new ValidateParameters($customJob);
            $validateParameters->execute();

            $instance = new $customJob->class_name();
            call_user_func_array([$instance, $customJob->method], $customJob->parameters);

            $this->updateCustomJobStatus($customJob, CustomJob::STATUS_COMPLETED);
        } catch (Exception $e) {

            if (Config::get('custom_jobs.retries') > $customJob->retries_executed) {
                $customJob->retries_executed += 1;
                $customJob->save();

                return $this->mainProcess($customJob);
            }

            $this->updateCustomJobStatus($customJob, CustomJob::STATUS_FAILED);

            Log::channel('background-jobs-errors')
                ->error("Failed Custom Job: $customJob->class_name::$customJob->method - Error: " . $e->getMessage());
        }
    }

    private function updateCustomJobStatus(CustomJob $customJob, string $status): void
    {
        $customJob->status = $status;
        $customJob->save();

        $log = Log::channel('background-jobs');
        $log->info("Custom Job: $customJob->class_name::$customJob->method - Status: $status");
    }

}
