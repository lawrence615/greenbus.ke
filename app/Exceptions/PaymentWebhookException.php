<?php

namespace App\Exceptions;

use Exception;

class PaymentWebhookException extends Exception
{
    public static function invalidSignature(): self
    {
        return new self('Invalid webhook signature', 400);
    }

    public static function invalidPayload(string $message = 'Invalid payload'): self
    {
        return new self($message, 400);
    }

    public static function processingFailed(string $message): self
    {
        return new self("Webhook processing failed: {$message}", 500);
    }
}
