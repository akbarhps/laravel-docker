<?php

namespace App\Http\Middleware;

use App\Helper\Routes;
use App\Models\Keycloak\KeycloakToken;
use App\Models\Keycloak\KeycloakUser;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ValidateKeycloakSession
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            /* @var KeycloakToken $keycloakToken */
            $keycloakToken = session(KeycloakToken::SESSION_KEY);
            if (!$keycloakToken) {
                throw new Exception('Token not found in session');
            }

            if (!$keycloakToken->isAccessTokenExpired()) {
                return $next($request);
            }

            if ($keycloakToken->isRefreshTokenExpired()) {
                throw new Exception('Refresh token is expired');
            }

            $keycloakToken->refreshToken();
            $keycloakUser = KeycloakUser::getFromToken($keycloakToken->accessToken);

            session()->put(KeycloakToken::SESSION_KEY, $keycloakToken);
            session()->put(KeycloakUser::SESSION_KEY, $keycloakUser);
        } catch (Exception $e) {
            Log::info("VerifyKeycloakSession@handle: validating session error: {$e->getMessage()}", [
                'token' => session(KeycloakToken::SESSION_KEY),
                'user' => session(KeycloakUser::SESSION_KEY),
            ]);

            return redirect()->route(Routes::getLogInRoute());
        }

        return $next($request);
    }
}
