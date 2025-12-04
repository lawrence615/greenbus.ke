<?php

namespace App\Contracts;

use App\Models\Booking;
use App\Models\Payment;

interface PaymentGatewayInterface
{
    /**
     * Create a checkout session for the booking.
     *
     * @param Booking $booking
     * @param string $successUrl
     * @param string $cancelUrl
     * @return array{session_id: string, checkout_url: string}
     */
    public function createCheckoutSession(
        Booking $booking,
        string $successUrl,
        string $cancelUrl
    ): array;

    /**
     * Verify webhook signature and return the event payload.
     *
     * @param string $payload Raw request body
     * @param string $signature Signature header value
     * @return array The verified event data
     * @throws \App\Exceptions\PaymentWebhookException
     */
    public function verifyWebhookSignature(string $payload, string $signature): array;

    /**
     * Retrieve a checkout session by ID.
     *
     * @param string $sessionId
     * @return array
     */
    public function retrieveSession(string $sessionId): array;

    /**
     * Get the provider name.
     *
     * @return string
     */
    public function getProviderName(): string;
}
