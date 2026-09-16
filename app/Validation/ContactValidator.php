<?php

declare(strict_types=1);

namespace App\Validation;

use App\DTO\ContactMessageData;

final class ContactValidator
{
    /** @param array<string, mixed> $input */
    public function validate(array $input): ValidationResult
    {
        $fullName = $this->cleanText($input['full_name'] ?? '');
        $email = strtolower(trim((string) ($input['email'] ?? '')));
        $phone = $this->cleanPhone($input['phone'] ?? '');
        $message = $this->cleanMultiline($input['message'] ?? '');
        $requestToken = trim((string) ($input['request_token'] ?? ''));
        $website = trim((string) ($input['contact_check'] ?? ''));
        $errors = [];

        if ($website !== '') {
            $errors['form'] = 'Form doğrulanamadı.';
        }
        if ($this->length($fullName) < 2 || $this->length($fullName) > 120) {
            $errors['full_name'] = 'Ad soyad 2–120 karakter olmalıdır.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $this->length($email) > 254) {
            $errors['email'] = 'Geçerli bir e-posta adresi girin.';
        }
        if (!preg_match('/^\+?[0-9][0-9 ()-]{8,23}[0-9]$/', $phone)) {
            $errors['phone'] = 'Geçerli bir telefon numarası girin.';
        }
        if ($this->length($message) < 10 || $this->length($message) > 5000) {
            $errors['message'] = 'Mesaj 10–5000 karakter olmalıdır.';
        }
        if (!preg_match('/^[0-9a-f-]{36}$/i', $requestToken)) {
            $errors['form'] = 'İstek kimliği geçersiz. Sayfayı yenileyip tekrar deneyin.';
        }

        if ($errors !== []) {
            return new ValidationResult(null, $errors);
        }

        return new ValidationResult(new ContactMessageData($fullName, $email, $phone, $message, $requestToken), []);
    }

    private function cleanText(mixed $value): string
    {
        $value = strip_tags(trim((string) $value));
        return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value) ?? '';
    }

    private function cleanMultiline(mixed $value): string
    {
        $value = str_replace(["\r\n", "\r"], "\n", (string) $value);
        return $this->cleanText($value);
    }

    private function cleanPhone(mixed $value): string
    {
        return preg_replace('/\s+/u', ' ', $this->cleanText($value)) ?? '';
    }

    private function length(string $value): int
    {
        return function_exists('mb_strlen') ? mb_strlen($value) : strlen($value);
    }
}
