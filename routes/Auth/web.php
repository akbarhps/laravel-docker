<?php

use App\Http\Controllers\Auth\LogInController;
use App\Http\Controllers\Auth\LogOutController;
use App\Http\Controllers\Auth\Password\ForgotPasswordController;
use App\Http\Controllers\Auth\Password\ResetPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

// Database authentication routes
Route::prefix("/auth")->group(function () {
    Route::middleware("guest")->group(function () {
        Route::controller(LogInController::class)->group(function () {
            Route::get("/login", "index")->name("auth.login");
            Route::post("/login", "store")->name("auth.login.store");
        });

        Route::controller(RegisterController::class)->group(function () {
            Route::get("/register", "index")->name("auth.register");
            Route::post("/register", "store")->name("auth.register.store");
        });

        Route::controller(ForgotPasswordController::class)->group(function () {
            Route::get("/forgot-password", "index")->name("auth.forgot-password");
            Route::post("/forgot-password", "store")->name("auth.forgot-password.store");
        });

        Route::controller(ResetPasswordController::class)->group(function () {
            Route::get("/reset-password", "index")->name("auth.reset-password");
            Route::post("/reset-password", "store")->name("auth.reset-password.store");
        });
    });

    Route::middleware("auth")->group(function () {
        Route::get("/logout", LogOutController::class)->name("auth.logout");
    });
});
