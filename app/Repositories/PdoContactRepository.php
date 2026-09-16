<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\DTO\ContactMessageData;

final class PdoContactRepository implements ContactRepositoryInterface
{
    public function __construct(private Database $database)
    {
    }

    public function create(ContactMessageData $message): int
    {
        $pdo = $this->database->connection();
        $statement = $pdo->prepare(
            'INSERT INTO contact_messages (full_name, email, phone, message, request_token)
             VALUES (:full_name, :email, :phone, :message, :request_token)'
        );
        $statement->execute([
            ':full_name' => $message->fullName,
            ':email' => $message->email,
            ':phone' => $message->phone,
            ':message' => $message->message,
            ':request_token' => $message->requestToken,
        ]);

        return (int) $pdo->lastInsertId();
    }
}
