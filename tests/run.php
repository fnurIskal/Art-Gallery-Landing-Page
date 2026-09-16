<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
spl_autoload_register(static function (string $class): void {
    if (!str_starts_with($class, 'App\\')) {
        return;
    }
    $path = BASE_PATH . '/app/' . str_replace('\\', '/', substr($class, 4)) . '.php';
    if (is_file($path)) {
        require $path;
    }
});

$tests = require __DIR__ . '/Unit/ContactValidatorTest.php';
$failures = 0;

foreach ($tests as $name => $test) {
    try {
        $test();
        echo "✓ {$name}\n";
    } catch (Throwable $exception) {
        $failures++;
        echo "✗ {$name}: {$exception->getMessage()}\n";
    }
}

exit($failures === 0 ? 0 : 1);
