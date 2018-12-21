<?php

declare(strict_types = 1);

namespace McMatters\LaravelDatabaseMutex\Contracts;

use Carbon\Carbon;
use McMatters\LaravelDatabaseMutex\Models\Mutex;

interface DatabaseMutexManagerContract
{
    /**
     * @param string $name
     * @param \Carbon\Carbon|null $expiresAt
     *
     * @return \McMatters\LaravelDatabaseMutex\Models\Mutex
     */
    public function create(string $name, Carbon $expiresAt = null): Mutex;

    /**
     * @param string $name
     *
     * @return bool
     */
    public function exists(string $name): bool;

    /**
     * @param string $name
     *
     * @return void
     */
    public function forget(string $name): void;
}
