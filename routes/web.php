<?php

use App\Constants\RouteNames;
use App\Http\Controllers\CustomJobController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Models\CustomJob;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'show'])->name(RouteNames::LOGIN_SHOW);
Route::post('/', [LoginController::class, 'store'])->name(RouteNames::LOGIN_STORE);

Route::post('logout', [LogoutController::class, 'store'])->name(RouteNames::LOGOUT_STORE);

Route::get('custom-jobs', [CustomJobController::class, 'index'])
    ->name(RouteNames::CUSTOM_JOB_INDEX)
    ->middleware('auth');