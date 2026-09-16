<?php

declare(strict_types=1);

namespace App\Core;

final class RateLimiter
{
    public function __construct(
        private string $directory,
        private int $maxAttempts = 5,
        private int $windowSeconds = 600
    ) {
    }

    public function tooManyAttempts(string $key): bool
    {
        if (!is_dir($this->directory)) {
            mkdir($this->directory, 0775, true);
        }

        $file = $this->directory . '/' . hash('sha256', $key) . '.json';
        $handle = fopen($file, 'c+');
        if ($handle === false) {
            return false;
        }

        try {
            flock($handle, LOCK_EX);
            $raw = stream_get_contents($handle);
            $state = json_decode($raw ?: '{}', true);
            $now = time();
            $startedAt = (int) ($state['started_at'] ?? $now);
            $attempts = (int) ($state['attempts'] ?? 0);

            if ($now - $startedAt >= $this->windowSeconds) {
                $startedAt = $now;
                $attempts = 0;
            }

            if ($attempts >= $this->maxAttempts) {
                return true;
            }

            rewind($handle);
            ftruncate($handle, 0);
            fwrite($handle, json_encode(['started_at' => $startedAt, 'attempts' => $attempts + 1]));
            fflush($handle);
            return false;
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }
}
