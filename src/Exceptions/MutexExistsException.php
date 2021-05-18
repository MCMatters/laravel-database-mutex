<?php

declare(strict_types=1);

namespace McMatters\LaravelDatabaseMutex\Exceptions;

use Exception;

/**
 * Class MutexExistsException
 *
 * @package McMatters\LaravelDatabaseMutex\Exceptions
 */
class MutexExistsException extends Exception
{
    /**
     * MutexExistsException constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct("Mutex '{$name}' already exists");
    }
}
