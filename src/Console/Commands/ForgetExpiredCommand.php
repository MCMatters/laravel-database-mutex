<?php

declare(strict_types = 1);

namespace McMatters\LaravelDatabaseMutex\Console\Commands;

use Illuminate\Console\Command;
use McMatters\LaravelDatabaseMutex\Managers\DatabaseMutexManager;

/**
 * Class ForgetExpiredCommand
 *
 * @package McMatters\LaravelDatabaseMutex\Console\Commands
 */
class ForgetExpiredCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'database-mutex:forget-expired';

    /**
     * @var string
     */
    protected $description = 'Forget all expired mutexes';

    /**
     * @return void
     */
    public function handle(): void
    {
        (new DatabaseMutexManager())->forgetExpired();

        $this->info('All expired mutexes have been successfully forgotten.');
    }
}
