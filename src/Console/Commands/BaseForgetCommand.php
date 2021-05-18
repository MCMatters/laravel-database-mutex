<?php

declare(strict_types=1);

namespace McMatters\LaravelDatabaseMutex\Console\Commands;

use Illuminate\Console\Command;
use McMatters\LaravelDatabaseMutex\Contracts\DatabaseMutexManagerContract as DatabaseMutexManager;

/**
 * Class BaseForgetCommand
 *
 * @package McMatters\LaravelDatabaseMutex\Console\Commands
 */
class BaseForgetCommand extends Command
{
    /**
     * @var \McMatters\LaravelDatabaseMutex\Contracts\DatabaseMutexManagerContract
     */
    protected $manager;

    /**
     * BaseForgetCommand constructor.
     *
     * @param \McMatters\LaravelDatabaseMutex\Contracts\DatabaseMutexManagerContract $manager
     */
    public function __construct(DatabaseMutexManager $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }
}
