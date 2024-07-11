<?php

use Illuminate\Support\Facades\Route;

$MIDDLEWARE_ROLE = 'USER';

$middlewares = ['auth', "role:$MIDDLEWARE_ROLE"];
if (session('auth_provider') === 'keycloak') {
    $middlewares[] = 'validate-keycloak-session';
}

Route::prefix('user')->middleware($middlewares)->group(function () {
    Route::view('/', 'welcome')->name('user.index');
});
