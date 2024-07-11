<?php

namespace App\Http\Controllers\Auth\Password;

use App\Http\Requests\Auth\Password\ForgotPasswordRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;

class ForgotPasswordController
{
    public function index()
    {
        return view('auth.password.forgot');
    }

    public function store(ForgotPasswordRequest $request)
    {
        $request->ensureIsNotRateLimited();
        $form = $request->validated();

        $status = Password::sendResetLink($form->only('email'));

        if ($status !== Password::RESET_LINK_SENT) {
            return back()->withErrors(['email' => __($status)]);
        }

        RateLimiter::hit($request->throttleKey());
        return back()->with(['status' => __($status)]);
    }
}
