<?php

declare(strict_types=1);

namespace App\Validation;

use App\DTO\ContactMessageData;

final class ValidationResult
{
    /** @param array<string, string> $errors */
    public function __construct(public ?ContactMessageData $data, public array $errors)
    {
    }

    public function isValid(): bool
    {
        return $this->data instanceof ContactMessageData && $this->errors === [];
    }
}
