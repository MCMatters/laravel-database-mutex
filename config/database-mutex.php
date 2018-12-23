<?php

declare(strict_types = 1);

return [
    // Table name for storing mutexes.
    'table' => 'mutexes',

    // Default expire time in seconds (default: 30 minutes).
    'expire' => 1800,

    // Determine whether should register database mutexes instead the default Laravel ones.
    'register_scheduling_mutexes' => false,
];
