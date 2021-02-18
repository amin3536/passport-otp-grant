<?php

namespace Amin3536\PassportOtpGrant;

use Illuminate\Support\ServiceProvider;

class PassportOtpGrantServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'amin3536');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'amin3536');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/passport-otp-grant.php', 'passport-otp-grant');

        // Register the service the package provides.
        $this->app->singleton('passport-otp-grant', function ($app) {
            return new PassportOtpGrant;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['passport-otp-grant'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/passport-otp-grant.php' => config_path('passport-otp-grant.php'),
        ], 'passport-otp-grant.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/amin3536'),
        ], 'passport-otp-grant.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/amin3536'),
        ], 'passport-otp-grant.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/amin3536'),
        ], 'passport-otp-grant.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
