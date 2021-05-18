<?php

declare(strict_types=1);

namespace McMatters\LaravelDatabaseMutex\Console\Scheduling;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\EventMutex;
use McMatters\LaravelDatabaseMutex\Contracts\DatabaseMutexManagerContract as DatabaseMutexManager;

/**
 * Class DatabaseEventMutex
 *
 * @package McMatters\LaravelDatabaseMutex\Console\Scheduling
 */
class DatabaseEventMutex implements EventMutex
{
    /**
     * @var \McMatters\LaravelDatabaseMutex\Contracts\DatabaseMutexManagerContract
     */
    protected $manager;

    /**
     * CacheEventMutex constructor.
     *
     * @param \McMatters\LaravelDatabaseMutex\Contracts\DatabaseMutexManagerContract $manager
     */
    public function __construct(DatabaseMutexManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param \Illuminate\Console\Scheduling\Event $event
     *
     * @return bool
     */
    public function create(Event $event): bool
    {
        return (bool) $this->manager->create(
            $event->mutexName(),
            Carbon::now()->addMinutes($event->expiresAt)
        );
    }

    /**
     * @param \Illuminate\Console\Scheduling\Event $event
     *
     * @return bool
     */
    public function exists(Event $event): bool
    {
        return $this->manager->exists($event->mutexName());
    }

    /**
     * @param  \Illuminate\Console\Scheduling\Event $event
     *
     * @return void
     */
    public function forget(Event $event): void
    {
        $this->manager->forget($event->mutexName());
    }
}
