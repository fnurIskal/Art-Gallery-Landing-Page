<?php

declare(strict_types=1);

use App\Core\Env;

return [
    'host' => Env::get('DB_HOST', 'mysql'),
    'port' => (int) Env::get('DB_PORT', '3306'),
    'database' => Env::get('DB_DATABASE', 'museum'),
    'username' => Env::get('DB_USERNAME', 'museum'),
    'password' => Env::get('DB_PASSWORD', 'museum'),
];
