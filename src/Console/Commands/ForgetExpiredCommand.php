<?php

declare(strict_types = 1);

namespace McMatters\LaravelDatabaseMutex\Console\Commands;

/**
 * Class ForgetExpiredCommand
 *
 * @package McMatters\LaravelDatabaseMutex\Console\Commands
 */
class ForgetExpiredCommand extends BaseForgetCommand
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
        $this->manager->forgetExpired();

        $this->info('All expired mutexes have been successfully forgotten.');
    }
}
