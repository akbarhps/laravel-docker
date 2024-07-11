<?php

use Illuminate\Support\Facades\Route;

$MIDDLEWARE_ROLE = 'ADMIN';

$middlewares = ['auth', "role:$MIDDLEWARE_ROLE"];
if (session('auth_provider') === 'keycloak') {
    $middlewares[] = 'validate-keycloak-session';
}

Route::prefix("admin")->middleware($middlewares)->group(function () {
    Route::view('/', 'welcome')->name('admin.index');
});
