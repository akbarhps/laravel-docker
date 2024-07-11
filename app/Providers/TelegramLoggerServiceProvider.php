<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class TelegramLoggerServiceProvider
 * @package Logger
 */
class TelegramLoggerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(config_path("telegram-logger.php"), "telegram-logger");
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(resource_path("views/vendor/laravel-telegram-logging"), "laravel-telegram-logging");
        $this->publishes([resource_path("views") => resource_path("views/vendor/laravel-telegram-logging")], "views");
        $this->publishes([config_path("telegram-logger.php") => config_path("telegram-logger.php")], "config");
    }
}
