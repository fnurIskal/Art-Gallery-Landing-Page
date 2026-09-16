<?php

declare(strict_types=1);

namespace App\Core;

final class Response
{
    /** @param array<string, mixed> $payload */
    public static function json(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    /** @param array<string, mixed> $data */
    public static function view(string $view, array $data = [], int $status = 200): void
    {
        http_response_code($status);
        extract($data, EXTR_SKIP);
        $viewFile = BASE_PATH . '/resources/views/' . $view . '.php';
        if (!is_file($viewFile)) {
            throw new \RuntimeException('View not found.');
        }

        ob_start();
        require $viewFile;
        $content = (string) ob_get_clean();
        require BASE_PATH . '/resources/views/layout.php';
        exit;
    }
}
