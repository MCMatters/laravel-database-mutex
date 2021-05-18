<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateMutexesTable
 */
class CreateMutexesTable extends Migration
{
    /**
     * @var string
     */
    protected $table;

    /**
     * CreateMutexesTable constructor.
     */
    public function __construct()
    {
        $this->table = Config::get('database-mutex.table');
    }

    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamp('expires_at');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::drop($this->table);
    }
}
