<?php

namespace App\Http\Controllers;

use App\Constants\RouteNames;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function store()
    {
        Auth::logout();

        return redirect()->route(RouteNames::LOGIN_SHOW);
    }
}