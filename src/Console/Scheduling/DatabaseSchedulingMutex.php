<?php

declare(strict_types=1);

namespace McMatters\LaravelDatabaseMutex\Console\Scheduling;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\SchedulingMutex;
use McMatters\LaravelDatabaseMutex\Contracts\DatabaseMutexManagerContract as DatabaseMutexManager;

/**
 * Class DatabaseSchedulingMutex
 *
 * @package McMatters\LaravelDatabaseMutex\Console\Scheduling
 */
class DatabaseSchedulingMutex implements SchedulingMutex
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
     * @param \DateTimeInterface $time
     *
     * @return bool
     */
    public function create(Event $event, DateTimeInterface $time): bool
    {
        return (bool) $this->manager->create(
            $event->mutexName().$time->format('Hi'),
            Carbon::now()->addHour()
        );
    }

    /**
     * @param \Illuminate\Console\Scheduling\Event $event
     * @param \DateTimeInterface $time
     *
     * @return bool
     */
    public function exists(Event $event, DateTimeInterface $time): bool
    {
        return $this->manager->exists($event->mutexName().$time->format('Hi'));
    }
}
