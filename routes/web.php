<?php

declare(strict_types=1);

/** @var App\Core\Router $router */
/** @var App\Controllers\HomeController $homeController */
$router->get('/', [$homeController, 'index']);
