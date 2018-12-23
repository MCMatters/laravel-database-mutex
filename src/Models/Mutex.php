<?php

declare(strict_types = 1);

namespace McMatters\LaravelDatabaseMutex\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use const false;

/**
 * Class Mutex
 *
 * @package McMatters\LaravelDatabaseMutex\Models
 */
class Mutex extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'expires_at',
        'created_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'expires_at' => 'timestamp',
        'created_at' => 'timestamp',
    ];

    /**
     * Mutex constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(Config::get('database.mutex.table'));
        parent::__construct($attributes);
    }

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->getAttribute('expires_at') <= Carbon::now();
    }
}
