<?php

namespace App\Http\Controllers\Auth;

use App\Helper\Routes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignInRequest;
use App\Services\AuthenticationService;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LogInController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function store(SignInRequest $request, AuthenticationService $service)
    {
        $request->ensureIsNotRateLimited();

        try {
            $form = $request->validated();
            $user = $service->logInViaWeb(email: $form['email'], password: $form['password']);

            auth()->login($user, $form['remember'] ?? false);
            session()->regenerate();

            return redirect()->intended(Routes::getIndexRoute());
        } catch (ValidationException $e) {
            RateLimiter::hit($request->throttleKey());
            throw $e;
        }
    }
}
