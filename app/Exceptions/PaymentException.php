<?php

namespace App\Exceptions;

use Exception;

class PaymentException extends Exception
{
    public static function checkoutFailed(string $message): self
    {
        return new self("Failed to create checkout session: {$message}", 500);
    }

    public static function bookingNotFound(string $reference): self
    {
        return new self("Booking not found: {$reference}", 404);
    }

    public static function paymentNotFound(string $reference): self
    {
        return new self("Payment not found: {$reference}", 404);
    }

    public static function invalidState(string $message): self
    {
        return new self("Invalid payment state: {$message}", 400);
    }

    public static function alreadyProcessed(): self
    {
        return new self('Payment has already been processed', 400);
    }
}
