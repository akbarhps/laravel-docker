<?php

namespace App\Http\Controllers\Auth;

use App\Helper\Routes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignUpRequest;
use App\Services\AuthenticationService;
use Illuminate\Support\Facades\RateLimiter;

class RegisterController extends Controller
{
    public function index()
    {
        return view("auth.register");
    }

    public function store(SignUpRequest $request, AuthenticationService $service)
    {
        $request->ensureIsNotRateLimited();

        $form = $request->validated();
        $user = $service->register($form);

        auth()->login($user);
        session()->regenerate();

        RateLimiter::hit($request->throttleKey());
        return redirect()->intended(Routes::getIndexRoute());
    }

}
