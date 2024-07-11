<?php

namespace App\Helper;

class Routes
{
    public static function getIndexRoute(): string
    {
        $user = auth()->user();
        if (!$user) {
            return route('index');
        }

        if ($user->hasRole('ADMIN')) {
            return route('admin.index');
        } else {
            return route('user.index');
        }
    }

    public static function getLogInRoute(): string
    {
        $isSignInViaKeycloak = session('auth_provider') == 'keycloak' ||
            config('auth.provider') == ['keycloak'];

        if ($isSignInViaKeycloak) {
            return route('auth.keycloak.login');
        }

        return route('auth.login');
    }
}
