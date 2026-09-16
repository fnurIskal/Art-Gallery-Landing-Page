<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;

final class HomeController
{
    /**
     * @param array<string, mixed> $siteConfig
     * @param array{hero: array{columns: array<int, array<int, array{0:string,1:string,2:string,3:string}>>}, archive: array<int, array<string, string>>, reviews: array<int, array<string, string>>} $content
     */
    public function __construct(private Csrf $csrf, private array $siteConfig, private array $content)
    {
    }

    public function index(Request $request): void
    {
        $this->content['story']['resolved_image'] = $this->resolveImage(
            (string) ($this->content['story']['image'] ?? ''),
            (string) ($this->content['story']['fallback'] ?? '')
        );

        Response::view('home', [
            'title' => $this->siteConfig['name'] . ' — Sanatla yeni bir karşılaşma',
            'description' => 'Akdeniz’in ışığında çağdaş sanat, sergiler ve yaratıcı atölyeler.',
            'csrfToken' => $this->csrf->token(),
            'requestToken' => $this->uuid(),
            'site' => $this->siteConfig,
            'content' => $this->content,
        ]);
    }

    /** Use the preferred asset when it has been uploaded, otherwise the fallback. */
    private function resolveImage(string $preferred, string $fallback): string
    {
        $file = BASE_PATH . '/public/assets/images/' . $preferred;

        return $preferred !== '' && is_file($file) ? $preferred : $fallback;
    }

    private function uuid(): string
    {
        $bytes = random_bytes(16);
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);
        $hex = bin2hex($bytes);

        return sprintf('%s-%s-%s-%s-%s', substr($hex, 0, 8), substr($hex, 8, 4), substr($hex, 12, 4), substr($hex, 16, 4), substr($hex, 20));
    }
}
