<?php

declare(strict_types=1);

namespace McMatters\LaravelDatabaseMutex\Console\Commands;

/**
 * Class ForgetAllCommand
 *
 * @package McMatters\LaravelDatabaseMutex\Console\Commands
 */
class ForgetAllCommand extends BaseForgetCommand
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
        $this->manager->forgetAll();

        $this->info('All mutexes have been successfully forgotten.');
    }
}
