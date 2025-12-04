<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGatewayInterface;
use App\Exceptions\PaymentException;
use App\Exceptions\PaymentWebhookException;
use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlutterwavePaymentGateway implements PaymentGatewayInterface
{
    private ?string $secretKey;
    private ?string $publicKey;
    private ?string $webhookSecret;
    private string $baseUrl = 'https://api.flutterwave.com/v3';

    public function __construct()
    {
        $this->secretKey = config('services.flutterwave.secret_key');
        $this->publicKey = config('services.flutterwave.public_key');
        $this->webhookSecret = config('services.flutterwave.webhook_secret');
    }

    /**
     * Check if the gateway is properly configured.
     */
    private function ensureConfigured(): void
    {
        if (empty($this->secretKey) || empty($this->publicKey)) {
            Log::error('Flutterwave payment gateway not configured', [
                'has_secret_key' => !empty($this->secretKey),
                'has_public_key' => !empty($this->publicKey),
            ]);
            
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
            $response = Http::withToken($this->secretKey)
                ->post("{$this->baseUrl}/payments", [
                    'tx_ref' => $booking->reference,
                    'amount' => $this->convertToUsd($booking->total_amount),
                    'currency' => 'USD',
                    'redirect_url' => $successUrl,
                    'customer' => [
                        'email' => $booking->customer_email,
                        'name' => $booking->customer_name,
                        'phonenumber' => $booking->customer_phone,
                    ],
                    'customizations' => [
                        'title' => 'GreenBus Tours',
                        'description' => $this->buildDescription($booking),
                        'logo' => config('app.url') . '/images/logo.png',
                    ],
                    'meta' => [
                        'booking_id' => $booking->id,
                        'booking_reference' => $booking->reference,
                        'tour_id' => $booking->tour_id,
                        'cancel_url' => $cancelUrl,
                    ],
                ]);

            if (!$response->successful()) {
                Log::error('Flutterwave payment initiation failed', [
                    'booking_reference' => $booking->reference,
                    'response' => $response->json(),
                ]);
                throw PaymentException::checkoutFailed($response->json('message') ?? 'Unknown error');
            }

            $data = $response->json('data');

            Log::info('Flutterwave payment initiated', [
                'booking_reference' => $booking->reference,
                'link' => $data['link'],
            ]);

            return [
                'session_id' => $booking->reference, // Flutterwave uses tx_ref
                'checkout_url' => $data['link'],
            ];
        } catch (\Exception $e) {
            if ($e instanceof PaymentException) {
                throw $e;
            }

            Log::error('Flutterwave checkout creation failed', [
                'booking_reference' => $booking->reference,
                'error' => $e->getMessage(),
            ]);

            throw PaymentException::checkoutFailed($e->getMessage());
        }
    }

    public function verifyWebhookSignature(string $payload, string $signature): array
    {
        // Flutterwave uses a secret hash header for verification
        $expectedSignature = $this->webhookSecret;

        if (!hash_equals($expectedSignature, $signature)) {
            Log::warning('Flutterwave webhook signature verification failed');
            throw PaymentWebhookException::invalidSignature();
        }

        try {
            $data = json_decode($payload, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw PaymentWebhookException::invalidPayload('Invalid JSON payload');
            }

            return [
                'type' => $data['event'] ?? 'unknown',
                'data' => $data['data'] ?? [],
                'id' => $data['data']['id'] ?? null,
            ];
        } catch (\Exception $e) {
            if ($e instanceof PaymentWebhookException) {
                throw $e;
            }
            throw PaymentWebhookException::invalidPayload($e->getMessage());
        }
    }

    public function retrieveSession(string $sessionId): array
    {
        // For Flutterwave, we verify the transaction by tx_ref
        try {
            $response = Http::withToken($this->secretKey)
                ->get("{$this->baseUrl}/transactions/verify_by_reference", [
                    'tx_ref' => $sessionId,
                ]);

            if (!$response->successful()) {
                throw PaymentException::checkoutFailed('Failed to verify transaction');
            }

            return $response->json('data');
        } catch (\Exception $e) {
            if ($e instanceof PaymentException) {
                throw $e;
            }

            Log::error('Failed to retrieve Flutterwave transaction', [
                'tx_ref' => $sessionId,
                'error' => $e->getMessage(),
            ]);

            throw PaymentException::checkoutFailed($e->getMessage());
        }
    }

    /**
     * Verify a transaction by its ID (used after webhook).
     */
    public function verifyTransaction(int $transactionId): array
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->get("{$this->baseUrl}/transactions/{$transactionId}/verify");

            if (!$response->successful()) {
                throw PaymentException::checkoutFailed('Failed to verify transaction');
            }

            return $response->json('data');
        } catch (\Exception $e) {
            if ($e instanceof PaymentException) {
                throw $e;
            }

            Log::error('Failed to verify Flutterwave transaction', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
            ]);

            throw PaymentException::checkoutFailed($e->getMessage());
        }
    }

    public function getProviderName(): string
    {
        return 'flutterwave';
    }

    /**
     * Convert KES amount to USD.
     */
    private function convertToUsd(int $kesAmount): float
    {
        $exchangeRate = config('services.flutterwave.kes_to_usd_rate', 153);
        return round($kesAmount / $exchangeRate, 2);
    }

    private function buildDescription(Booking $booking): string
    {
        $tour = $booking->tour;
        return "{$tour->title} - {$booking->date->format('M d, Y')} - {$booking->adults} adult(s)";
    }
}
