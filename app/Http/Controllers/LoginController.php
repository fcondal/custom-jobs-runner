<?php

namespace App\Http\Controllers;

use App\Constants\RequestKeys;
use App\Constants\RouteNames;
use App\Http\Requests\Login\InvokeRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class LoginController
{
    public function show()
    {
        return view('auth.login');
    }
    public function store(InvokeRequest $request)
    {
        $user = User::where('email', $request->get(RequestKeys::EMAIL))->first();

        if ($user && Hash::check($request->get(RequestKeys::PASSWORD), $user->password)) {
            Auth::login($user);

            return Redirect::route(RouteNames::CUSTOM_JOB_INDEX);
        }

        return Redirect::back()->withErrors(['email' => 'Invalid credentials.']);
    }
}