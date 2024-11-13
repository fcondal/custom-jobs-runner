<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('custom-job:run')->description('Execute Custom Jobs')->everyFiveSeconds();
