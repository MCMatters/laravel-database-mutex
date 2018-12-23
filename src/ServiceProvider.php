<?php

declare(strict_types = 1);

namespace McMatters\LaravelDatabaseMutex;

use Illuminate\Console\Scheduling\EventMutex;
use Illuminate\Console\Scheduling\SchedulingMutex;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\Facades\Config;
use Laravel\Lumen\Application as LumenApplication;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use McMatters\LaravelDatabaseMutex\Console\Commands\ForgetAllCommand;
use McMatters\LaravelDatabaseMutex\Console\Commands\ForgetExpiredCommand;
use McMatters\LaravelDatabaseMutex\Console\Scheduling\DatabaseEventMutex;
use McMatters\LaravelDatabaseMutex\Console\Scheduling\DatabaseSchedulingMutex;
use McMatters\LaravelDatabaseMutex\Contracts\DatabaseMutexManagerContract;
use McMatters\LaravelDatabaseMutex\Managers\DatabaseMutexManager;

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

        $this->registerManager();
        $this->registerConsoleCommands();
        $this->registerSchedulingMutexes();
    }

    /**
     * @return void
     */
    protected function registerManager(): void
    {
        $this->app->singleton(
            DatabaseMutexManagerContract::class,
            DatabaseMutexManager::class
        );
    }

    /**
     * @return void
     */
    protected function registerConsoleCommands(): void
    {
        $this->app->singleton(
            'command.laravel-database-mutex.forget-all',
            function ($app) {
                return new ForgetAllCommand(
                    $app->make(DatabaseMutexManagerContract::class)
                );
            }
        );

        $this->app->singleton(
            'command.laravel-database-mutex.forget-expired',
            function ($app) {
                return new ForgetExpiredCommand(
                    $app->make(DatabaseMutexManagerContract::class)
                );
            }
        );

        $this->commands([
            'command.laravel-database-mutex.forget-all',
            'command.laravel-database-mutex.forget-expired',
        ]);
    }

    /**
     * @return void
     */
    protected function registerSchedulingMutexes(): void
    {
        if (Config::get('database-mutex.register_scheduling_mutexes')) {
            $this->app->singleton(EventMutex::class, DatabaseEventMutex::class);
            $this->app->singleton(SchedulingMutex::class, DatabaseSchedulingMutex::class);
        }
    }
}
