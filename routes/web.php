<?php

use Illuminate\Support\Facades\Route;

if (in_array('keycloak', config('auth.provider'))) {
    require __DIR__ . "/Auth/keycloak.php";
}

if (in_array('database', config('auth.provider'))) {
    require __DIR__ . "/Auth/web.php";
    require __DIR__ . "/Auth/api.php";
}

require __DIR__ . "/Admin/web.php";
require __DIR__ . "/User/web.php";

Route::view('/', 'welcome')->name('index');
