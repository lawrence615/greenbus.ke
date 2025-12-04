<?php

namespace App\Enums;

enum BookingStatus: string
{
    case PENDING_PAYMENT = 'pending_payment';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
    case COMPLETED = 'completed';
    case REFUNDED = 'refunded';
    case EXPIRED = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::PENDING_PAYMENT => 'Pending Payment',
            self::CONFIRMED => 'Confirmed',
            self::CANCELLED => 'Cancelled',
            self::COMPLETED => 'Completed',
            self::REFUNDED => 'Refunded',
            self::EXPIRED => 'Expired',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING_PAYMENT => 'yellow',
            self::CONFIRMED => 'green',
            self::CANCELLED => 'red',
            self::COMPLETED => 'blue',
            self::REFUNDED => 'gray',
            self::EXPIRED => 'gray',
        };
    }

    public function isPaid(): bool
    {
        return in_array($this, [self::CONFIRMED, self::COMPLETED]);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this, [self::PENDING_PAYMENT, self::CONFIRMED]);
    }
}
