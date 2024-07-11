<?php

use Illuminate\Support\Facades\Route;

// Database api authentication routes
Route::prefix("/auth/api/v1")->group(function () {
    Route::middleware("guest")->group(function () {
//        Route::post("/login", APISignInController::class)->name("api.auth.login");
//        Route::post("/register", APISignInController::class)->name("api.auth.register");
    });

    Route::middleware("auth")->group(function () {
//        Route::post("/logout", APISignOutController::class)->name("api.auth.logout");
    });
});
