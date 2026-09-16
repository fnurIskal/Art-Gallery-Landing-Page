<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\ContactMessageData;

interface ContactRepositoryInterface
{
    public function create(ContactMessageData $message): int;
}
