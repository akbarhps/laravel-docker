<?php

namespace App\Http\Controllers\Auth\Password;

use App\Http\Requests\Auth\Password\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController
{
    public function index()
    {
        return view('auth.password.reset');
    }

    public function store(ResetPasswordRequest $request)
    {
        $request->ensureIsNotRateLimited();
        $form = $request->validated();

        $status = Password::reset($form->only('email', 'password', 'token'), function (User $user, string $password) {
            $user->forceFill(['password' => Hash::make($password)])
                ->setRememberToken(Str::random(60));
            $user->save();

            event(new PasswordReset($user));
        });

        if ($status !== Password::PASSWORD_RESET) {
            RateLimiter::hit($request->throttleKey());
            return back()->withErrors(['email' => [__($status)]]);
        }

        return redirect()->route('login')->with('status', __($status));
    }
}
