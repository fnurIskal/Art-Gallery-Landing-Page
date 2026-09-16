<?php

declare(strict_types=1);

use App\Controllers\ContactController;
use App\Controllers\HomeController;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\Env;
use App\Core\RateLimiter;
use App\Core\Request;
use App\Core\Router;
use App\Repositories\PdoContactRepository;
use App\Services\ContactService;
use App\Validation\ContactValidator;

define('BASE_PATH', dirname(__DIR__));

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $path = BASE_PATH . '/app/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($path)) {
        require $path;
    }
});

Env::load(BASE_PATH . '/.env');

$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
session_set_cookie_params([
    'httponly' => true,
    'secure' => $secure,
    'samesite' => 'Lax',
    'path' => '/',
]);
session_save_path(BASE_PATH . '/storage/sessions');
session_start();

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: strict-origin-when-cross-origin');
header("Permissions-Policy: camera=(), microphone=(), geolocation=()");
header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; style-src 'self'; script-src 'self'; connect-src 'self'; font-src 'self'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'");

$siteConfig = require BASE_PATH . '/config/app.php';
$databaseConfig = require BASE_PATH . '/config/database.php';
$landingContent = require BASE_PATH . '/resources/data/landing.php';
$csrf = new Csrf();
$database = new Database($databaseConfig);
$repository = new PdoContactRepository($database);
$service = new ContactService($repository);
$validator = new ContactValidator();
$rateLimiter = new RateLimiter(BASE_PATH . '/storage/cache');

$homeController = new HomeController($csrf, $siteConfig, $landingContent);
$contactController = new ContactController($csrf, $rateLimiter, $validator, $service);
$router = new Router();

require BASE_PATH . '/routes/web.php';
require BASE_PATH . '/routes/api.php';

return [$router, new Request()];
