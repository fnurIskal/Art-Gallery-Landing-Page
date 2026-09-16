<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Csrf;
use App\Core\RateLimiter;
use App\Core\Request;
use App\Core\Response;
use App\Services\ContactService;
use App\Validation\ContactValidator;
use PDOException;

final class ContactController
{
    public function __construct(
        private Csrf $csrf,
        private RateLimiter $rateLimiter,
        private ContactValidator $validator,
        private ContactService $service
    ) {
    }

    public function store(Request $request): void
    {
        $input = $request->input();
        $token = $request->header('X-CSRF-Token') ?? (isset($input['_token']) ? (string) $input['_token'] : null);

        if (!$this->csrf->verify($token)) {
            Response::json(['success' => false, 'message' => 'Oturum doğrulanamadı. Sayfayı yenileyip tekrar deneyin.'], 403);
        }

        if ($this->rateLimiter->tooManyAttempts('contact:' . $request->ip())) {
            Response::json(['success' => false, 'message' => 'Çok fazla deneme yapıldı. Lütfen birkaç dakika sonra tekrar deneyin.'], 429);
        }

        $result = $this->validator->validate($input);
        if (!$result->isValid()) {
            Response::json([
                'success' => false,
                'message' => 'Lütfen işaretlenen alanları kontrol edin.',
                'errors' => $result->errors,
            ], 422);
        }

        try {
            $this->service->submit($result->data);
            Response::json([
                'success' => true,
                'message' => 'Mesajınız bize ulaştı. En kısa sürede sizinle iletişime geçeceğiz.',
            ], 201);
        } catch (PDOException $exception) {
            if ((string) $exception->getCode() === '23000') {
                Response::json(['success' => true, 'message' => 'Mesajınız daha önce alınmış. Teşekkür ederiz.'], 200);
            }
            error_log('[contact] ' . $exception->getMessage());
            Response::json(['success' => false, 'message' => 'Mesaj şu anda kaydedilemedi. Lütfen daha sonra tekrar deneyin.'], 500);
        } catch (\Throwable $exception) {
            error_log('[contact] ' . $exception->getMessage());
            Response::json(['success' => false, 'message' => 'Beklenmeyen bir sorun oluştu. Lütfen tekrar deneyin.'], 500);
        }
    }
}
