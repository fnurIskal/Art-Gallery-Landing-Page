<?php

declare(strict_types=1);

namespace App\Core;

final class Csrf
{
    public function token(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }

        return (string) $_SESSION['_csrf'];
    }

    public function verify(?string $token): bool
    {
        return is_string($token) && $token !== '' && hash_equals($this->token(), $token);
    }
}
