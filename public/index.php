<?php

declare(strict_types=1);

[$router, $request] = require dirname(__DIR__) . '/bootstrap/app.php';
$router->dispatch($request);
