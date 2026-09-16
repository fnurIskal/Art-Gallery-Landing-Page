<?php

declare(strict_types=1);

/** @var App\Core\Router $router */
/** @var App\Controllers\ContactController $contactController */
$router->post('/api/contact', [$contactController, 'store']);
