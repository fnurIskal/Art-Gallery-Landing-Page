<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<string, callable> */
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET ' . $path] = $handler;
    }

    public function post(string $path, callable $handler): void
    {
        $this->routes['POST ' . $path] = $handler;
    }

    public function dispatch(Request $request): void
    {
        $handler = $this->routes[$request->method() . ' ' . $request->path()] ?? null;
        if ($handler === null) {
            if (str_starts_with($request->path(), '/api/')) {
                Response::json(['success' => false, 'message' => 'İstenen kaynak bulunamadı.'], 404);
            }
            Response::view('errors/404', ['title' => 'Sayfa bulunamadı'], 404);
        }

        $handler($request);
    }
}
