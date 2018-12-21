<?php

declare(strict_types = 1);

namespace McMatters\LaravelDatabaseMutex;

use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use McMatters\LaravelDatabaseMutex\Console\Commands\ForgetAllCommand;
use McMatters\LaravelDatabaseMutex\Console\Commands\ForgetExpiredCommand;

/**
 * Class ServiceProvider
 *
 * @package McMatters\LaravelDatabaseMutex
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/database-mutex.php' => $this->app->configPath('database-mutex.php')
            ], 'config');

            $this->publishes([
                __DIR__.'/../migrations' => $this->app->databasePath('migrations'),
            ], 'migrations');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('database-mutex');
        }
    }

    /**
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/database-mutex.php',
            'database-mutex'
        );

        $this->app->singleton(
            'command.laravel-database-mutex.forget-all',
            function () {
                return new ForgetAllCommand();
            }
        );

        $this->app->singleton(
            'command.laravel-database-mutex.forget-expired',
            function () {
                return new ForgetExpiredCommand();
            }
        );

        $this->commands([
            'command.laravel-database-mutex.forget-all',
            'command.laravel-database-mutex.forget-expired',
        ]);
    }
}
