<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGatewayInterface;
use App\Exceptions\PaymentException;
use App\Exceptions\PaymentWebhookException;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use Stripe\Webhook;

class StripePaymentGateway implements PaymentGatewayInterface
{
    private ?StripeClient $stripe = null;
    private ?string $secretKey;
    private ?string $webhookSecret;

    public function __construct()
    {
        $this->secretKey = config('services.stripe.secret');
        $this->webhookSecret = config('services.stripe.webhook_secret');
        
        if (!empty($this->secretKey)) {
            $this->stripe = new StripeClient($this->secretKey);
        }
    }

    /**
     * Check if the gateway is properly configured.
     */
    private function ensureConfigured(): void
    {
        if (empty($this->secretKey) || !$this->stripe) {
            Log::error('Stripe payment gateway not configured');
            
            throw PaymentException::checkoutFailed(
                'Payment gateway is not configured. Please contact support.'
            );
        }
    }

    public function createCheckoutSession(
        Booking $booking,
        string $successUrl,
        string $cancelUrl
    ): array {
        $this->ensureConfigured();

        try {
            $session = $this->stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'mode' => 'payment',
                'currency' => 'usd',
                'customer_email' => $booking->customer_email,
                'client_reference_id' => $booking->reference,
                'metadata' => [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->reference,
                    'tour_id' => $booking->tour_id,
                    'customer_name' => $booking->customer_name,
                ],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            // 'unit_amount' => $this->convertToUsdCents($booking->total_amount),
                            'unit_amount' => $booking->total_amount * 100,
                            'product_data' => [
                                'name' => $booking->tour->title,
                                'description' => $this->buildDescription($booking),
                            ],
                        ],
                        'quantity' => 1,
                    ],
                ],
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'expires_at' => now()->addMinutes(30)->timestamp,
            ]);

            Log::info('Stripe checkout session created', [
                'session_id' => $session->id,
                'booking_reference' => $booking->reference,
            ]);

            return [
                'session_id' => $session->id,
                'checkout_url' => $session->url,
            ];
        } catch (ApiErrorException $e) {
            Log::error('Stripe checkout session creation failed', [
                'booking_reference' => $booking->reference,
                'error' => $e->getMessage(),
            ]);

            throw PaymentException::checkoutFailed($e->getMessage());
        }
    }

    public function verifyWebhookSignature(string $payload, string $signature): array
    {
        try {
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                $this->webhookSecret
            );

            return [
                'type' => $event->type,
                'data' => $event->data->object->toArray(),
                'id' => $event->id,
            ];
        } catch (SignatureVerificationException $e) {
            Log::warning('Stripe webhook signature verification failed', [
                'error' => $e->getMessage(),
            ]);

            throw PaymentWebhookException::invalidSignature();
        } catch (\UnexpectedValueException $e) {
            Log::warning('Stripe webhook payload invalid', [
                'error' => $e->getMessage(),
            ]);

            throw PaymentWebhookException::invalidPayload($e->getMessage());
        }
    }

    public function retrieveSession(string $sessionId): array
    {
        try {
            $session = $this->stripe->checkout->sessions->retrieve($sessionId, [
                'expand' => ['payment_intent'],
            ]);

            return $session->toArray();
        } catch (ApiErrorException $e) {
            Log::error('Failed to retrieve Stripe session', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);

            throw PaymentException::checkoutFailed($e->getMessage());
        }
    }

    public function getProviderName(): string
    {
        return 'stripe';
    }

    /**
     * Convert KES amount to USD cents.
     * Using a fixed rate for now - in production, use a currency API.
     */
    private function convertToUsdCents(int $kesAmount): int
    {
        // TODO: Integrate with a currency conversion API for real-time rates
        // For now, using approximate rate: 1 USD = 153 KES
        $exchangeRate = config('services.stripe.kes_to_usd_rate', 153);
        $usdAmount = $kesAmount / $exchangeRate;
        
        // Convert to cents and round up to avoid undercharging
        return (int) ceil($usdAmount * 100);
    }

    private function buildDescription(Booking $booking): string
    {
        $parts = [
            "Date: {$booking->date->format('M d, Y')}",
            "Adults: {$booking->adults}",
        ];

        if ($booking->children > 0) {
            $parts[] = "Children: {$booking->children}";
        }

        if ($booking->infants > 0) {
            $parts[] = "Infants: {$booking->infants}";
        }

        return implode(' | ', $parts);
    }
}
