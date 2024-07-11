<?php

namespace App\Helper;

use Laravel\Socialite\Facades\Socialite;

class SocialiteKeycloak
{
    public const DRIVER = 'keycloak';

    public static function getDriver()
    {
        return Socialite::driver(self::DRIVER);
    }

    public static function redirectToLoginPage()
    {
        return self::getDriver()->redirect();
    }

    public static function getSignedInUser()
    {
        return self::getDriver()->user();
    }

    public static function refreshToken(string $refreshToken)
    {
        return self::getDriver()->refreshToken(refreshToken: $refreshToken);
    }

    public static function getUserFromToken(string $accessToken)
    {
        return self::getDriver()->userFromToken(token: $accessToken);
    }

    public static function getSignOutUrl(string $redirectUrl, string $idToken)
    {
        return self::getDriver()->getLogoutUrl(
            redirectUri: $redirectUrl,
            clientId: config('services.keycloak.client_id'),
            idTokenHint: $idToken,
        );
    }
}
