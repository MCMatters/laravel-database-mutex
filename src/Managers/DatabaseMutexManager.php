<?php

declare(strict_types=1);

namespace McMatters\LaravelDatabaseMutex\Managers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use McMatters\LaravelDatabaseMutex\Contracts\DatabaseMutexManagerContract;
use McMatters\LaravelDatabaseMutex\Exceptions\JsonEncodingException;
use McMatters\LaravelDatabaseMutex\Exceptions\MutexExistsException;
use McMatters\LaravelDatabaseMutex\Models\Mutex;

use function json_encode;
use function json_last_error;
use function json_last_error_msg;
use function sha1;

use const false;
use const JSON_ERROR_NONE;
use const null;

/**
 * Class DatabaseMutexManager
 *
 * @package McMatters\LaravelDatabaseMutex\Managers
 */
class DatabaseMutexManager implements DatabaseMutexManagerContract
{
    /**
     * @param string $name
     * @param \Carbon\Carbon|null $expiresAt
     *
     * @return \McMatters\LaravelDatabaseMutex\Models\Mutex
     * @throws \McMatters\LaravelDatabaseMutex\Exceptions\MutexExistsException
     */
    public function create(string $name, Carbon $expiresAt = null): Mutex
    {
        if ($this->exists($name)) {
            throw new MutexExistsException($name);
        }

        $now = Carbon::now();

        $expiresAt = $expiresAt ?? (clone $now)->addSeconds(
            Config::get('database-mutex.expire')
        );

        return Mutex::query()->create([
            'name' => $name,
            'expires_at' => $expiresAt,
            'created_at' => $now,
        ]);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function exists(string $name): bool
    {
        /** @var \McMatters\LaravelDatabaseMutex\Models\Mutex $mutex */
        $mutex = Mutex::query()
            ->where('name', $name)
            ->first();

        if (null === $mutex) {
            return false;
        }

        if ($mutex->isExpired()) {
            $mutex->delete();
        }

        return $mutex->exists;
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public function forget(string $name): void
    {
        Mutex::query()->where('name', $name)->delete();
    }

    /**
     * @return void
     */
    public function forgetExpired(): void
    {
        Mutex::query()->where('expires_at', '<=', Carbon::now())->delete();
    }

    /**
     * @return void
     */
    public function forgetAll(): void
    {
        Mutex::query()->truncate();
    }

    /**
     * @param mixed $payload
     *
     * @return string
     *
     * @throws \McMatters\LaravelDatabaseMutex\Exceptions\JsonEncodingException
     */
    public function getName($payload): string
    {
        $json = json_encode($payload);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonEncodingException(json_last_error_msg());
        }

        return sha1($json);
    }
}
