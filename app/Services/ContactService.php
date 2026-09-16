<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\ContactMessageData;
use App\Repositories\ContactRepositoryInterface;

final class ContactService
{
    public function __construct(private ContactRepositoryInterface $repository)
    {
    }

    public function submit(ContactMessageData $message): int
    {
        return $this->repository->create($message);
    }
}
