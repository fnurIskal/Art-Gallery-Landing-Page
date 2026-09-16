<?php

declare(strict_types=1);

use App\Core\Env;

return [
    'name' => Env::get('APP_NAME', 'Mersin Modern'),
    'url' => Env::get('APP_URL', 'http://localhost:8080'),
    'debug' => Env::bool('APP_DEBUG', false),
    'whatsapp_url' => Env::get('WHATSAPP_URL', ''),
    'campaign_end_at' => Env::get('CAMPAIGN_END_AT', '2026-10-31T23:59:59+03:00'),
];
