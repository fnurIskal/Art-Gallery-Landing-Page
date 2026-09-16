<?php

declare(strict_types=1);

namespace App\DTO;

final class ContactMessageData
{
    public function __construct(
        public string $fullName,
        public string $email,
        public string $phone,
        public string $message,
        public string $requestToken
    ) {
    }
}
