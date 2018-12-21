<?php

declare(strict_types = 1);

namespace McMatters\LaravelDatabaseMutex\Console\Commands;

use Illuminate\Console\Command;
use McMatters\LaravelDatabaseMutex\Managers\DatabaseMutexManager;

/**
 * Class ForgetAllCommand
 *
 * @package McMatters\LaravelDatabaseMutex\Console\Commands
 */
class ForgetAllCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'database-mutex:forget-all';

    /**
     * @var string
     */
    protected $description = 'Forget all mutexes';

    /**
     * @return void
     */
    public function handle(): void
    {
        (new DatabaseMutexManager())->forgetAll();

        $this->info('All mutexes have been successfully forgotten.');
    }
}
