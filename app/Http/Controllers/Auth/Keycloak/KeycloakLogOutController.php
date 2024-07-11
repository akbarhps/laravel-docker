<?php

namespace App\Http\Controllers\Auth\Keycloak;

use App\Helper\Routes;
use App\Helper\SocialiteKeycloak;
use App\Http\Controllers\Controller;
use App\Models\Keycloak\KeycloakToken;
use Illuminate\Http\RedirectResponse;

class KeycloakLogOutController extends Controller
{
    public function index(): RedirectResponse
    {
        /* @var KeycloakToken $keycloakToken */
        $keycloakToken = session(KeycloakToken::SESSION_KEY);

        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        $redirectUrl = Routes::getIndexRoute();
        if (!$keycloakToken) {
            return redirect()->to($redirectUrl);
        }

        $signOutUrl = SocialiteKeycloak::getSignOutUrl($redirectUrl, $keycloakToken->idToken);
        return redirect($signOutUrl);
    }

    public function callbackHandler(): RedirectResponse
    {
        return redirect()->to(Routes::getIndexRoute());
    }
}
