<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case INITIATED = 'initiated';
    case PROCESSING = 'processing';
    case SUCCEEDED = 'succeeded';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::INITIATED => 'Initiated',
            self::PROCESSING => 'Processing',
            self::SUCCEEDED => 'Succeeded',
            self::FAILED => 'Failed',
            self::CANCELLED => 'Cancelled',
            self::REFUNDED => 'Refunded',
        };
    }

    public function isSuccessful(): bool
    {
        return $this === self::SUCCEEDED;
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::SUCCEEDED, self::FAILED, self::CANCELLED, self::REFUNDED]);
    }
}
