<?php

namespace App\Http\Controllers;

use App\Constants\RouteNames;
use App\Models\CustomJob;
use Illuminate\Http\Request;

class CustomJobController extends Controller
{
    public function index()
    {
        $customJobs = CustomJob::orderByDesc('created_at')->paginate();

        return view(RouteNames::CUSTOM_JOB_INDEX, ['customJobs' => $customJobs]);
    }
}
