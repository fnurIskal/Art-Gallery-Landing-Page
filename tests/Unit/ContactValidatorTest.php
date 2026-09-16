<?php

declare(strict_types=1);

use App\Validation\ContactValidator;

$assert = static function (bool $condition, string $message): void {
    if (!$condition) {
        throw new RuntimeException($message);
    }
};

return [
    'otomatik doldurulan eski web sitesi alanı gerçek kullanıcıyı engellemez' => static function () use ($assert): void {
        $result = (new ContactValidator())->validate([
            'full_name' => 'Arayüz Testi', 'email' => 'test@example.com',
            'phone' => '0555 111 22 33', 'message' => 'Geçerli bir ziyaret bilgi talebidir.',
            'request_token' => '123e4567-e89b-12d3-a456-426614174000',
            'website' => 'https://autofill.example', 'contact_check' => '',
        ]);
        $assert($result->isValid(), 'Tarayıcı otomatik doldurması geçerli formu engelledi.');
    },
    'geçerli iletişim verisi kabul edilir' => static function () use ($assert): void {
        $result = (new ContactValidator())->validate([
            'full_name' => 'Ayşe Yılmaz',
            'email' => 'ayse@example.com',
            'phone' => '+90 555 111 22 33',
            'message' => 'Hafta sonu aile ziyareti hakkında bilgi almak istiyorum.',
            'request_token' => '123e4567-e89b-12d3-a456-426614174000',
            'contact_check' => '',
        ]);
        $assert($result->isValid(), 'Geçerli veri reddedildi.');
    },
    'boş ve hatalı alanlar reddedilir' => static function () use ($assert): void {
        $result = (new ContactValidator())->validate([
            'full_name' => '', 'email' => 'yanlış', 'phone' => '12', 'message' => 'kısa', 'request_token' => '',
        ]);
        $assert(!$result->isValid(), 'Hatalı veri kabul edildi.');
        $assert(count($result->errors) >= 5, 'Beklenen alan hataları üretilmedi.');
    },
    'html etiketleri saklanmadan temizlenir' => static function () use ($assert): void {
        $result = (new ContactValidator())->validate([
            'full_name' => '<b>Deniz Kaya</b>',
            'email' => 'deniz@example.com',
            'phone' => '0555 111 22 33',
            'message' => '<script>alert(1)</script> Güvenli bir ziyaret mesajıdır.',
            'request_token' => '123e4567-e89b-12d3-a456-426614174000',
        ]);
        $assert($result->isValid(), 'Temizlenebilir veri reddedildi.');
        $assert(!str_contains($result->data->message, '<script>'), 'HTML etiketi temizlenmedi.');
        $assert($result->data->fullName === 'Deniz Kaya', 'Ad alanı doğru normalize edilmedi.');
    },
    'honeypot doluysa istek reddedilir' => static function () use ($assert): void {
        $result = (new ContactValidator())->validate([
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '0555 111 22 33',
            'message' => 'Geçerli uzunlukta bir test mesajıdır.',
            'request_token' => '123e4567-e89b-12d3-a456-426614174000',
            'contact_check' => 'spam.example',
        ]);
        $assert(!$result->isValid(), 'Bot alanı dolu istek kabul edildi.');
    },
];
