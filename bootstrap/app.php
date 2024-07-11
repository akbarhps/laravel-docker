<?php

use App\Exceptions\ApiResponseException;
use App\Helper\Routes;
use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\EnsureUserRoleNot;
use App\Http\Middleware\ValidateKeycloakSession;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/internal/status',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => EnsureUserHasRole::class,
            'role_not' => EnsureUserRoleNot::class,
            'validate-keycloak-session' => ValidateKeycloakSession::class,
        ]);
        $middleware->redirectUsersTo(function () {
            return Routes::getIndexRoute();
        });
        $middleware->redirectGuestsTo(function () {
            return Routes::getLogInRoute();
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
