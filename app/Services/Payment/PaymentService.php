<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGatewayInterface;
use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Events\PaymentFailed;
use App\Events\PaymentSucceeded;
use App\Exceptions\PaymentException;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct(
        private readonly PaymentGatewayInterface $gateway
    ) {}

    /**
     * Initiate payment for a booking.
     * Creates a payment record and returns checkout URL.
     */
    public function initiatePayment(Booking $booking, string $successUrl, string $cancelUrl): array
    {
        // Prevent duplicate payment initiation
        if ($booking->payment && $booking->payment->status === PaymentStatus::INITIATED->value) {
            // Return existing session if still valid
            Log::info('Returning existing payment session', [
                'booking_reference' => $booking->reference,
            ]);
        }

        return DB::transaction(function () use ($booking, $successUrl, $cancelUrl) {
            // Lock the booking row to prevent race conditions
            $booking = Booking::where('id', $booking->id)->lockForUpdate()->first();

            if (!$booking) {
                throw PaymentException::bookingNotFound('Booking not found');
            }

            // Check if booking is in valid state for payment
            if ($booking->status !== BookingStatus::PENDING_PAYMENT->value) {
                throw PaymentException::invalidState(
                    "Booking is in '{$booking->status}' state, expected 'pending_payment'"
                );
            }

            // Create checkout session with payment provider
            $session = $this->gateway->createCheckoutSession(
                $booking,
                $successUrl,
                $cancelUrl
            );

            // Create or update payment record
            $payment = Payment::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'provider' => $this->gateway->getProviderName(),
                    'provider_reference' => $session['session_id'],
                    'amount' => $booking->total_amount,
                    'currency' => $booking->currency,
                    'status' => PaymentStatus::INITIATED->value,
                    'raw_payload' => ['checkout_session_id' => $session['session_id']],
                ]
            );

            Log::info('Payment initiated', [
                'booking_reference' => $booking->reference,
                'payment_id' => $payment->id,
                'session_id' => $session['session_id'],
            ]);

            return [
                'payment' => $payment,
                'checkout_url' => $session['checkout_url'],
            ];
        });
    }

    /**
     * Handle successful payment from webhook.
     */
    public function handlePaymentSuccess(array $sessionData): void
    {
        $bookingReference = $sessionData['client_reference_id'] ?? null;
        $sessionId = $sessionData['id'] ?? null;

        if (!$bookingReference || !$sessionId) {
            Log::warning('Payment success webhook missing required data', $sessionData);
            return;
        }

        DB::transaction(function () use ($bookingReference, $sessionId, $sessionData) {
            $booking = Booking::where('reference', $bookingReference)
                ->lockForUpdate()
                ->first();

            if (!$booking) {
                Log::error('Booking not found for payment success', [
                    'reference' => $bookingReference,
                ]);
                return;
            }

            $payment = $booking->payment;

            if (!$payment) {
                Log::error('Payment record not found', [
                    'booking_reference' => $bookingReference,
                ]);
                return;
            }

            // Idempotency check - don't process if already succeeded
            if ($payment->status === PaymentStatus::SUCCEEDED->value) {
                Log::info('Payment already processed, skipping', [
                    'booking_reference' => $bookingReference,
                ]);
                return;
            }

            // Update payment record
            $payment->update([
                'status' => PaymentStatus::SUCCEEDED->value,
                'provider_reference' => $sessionId,
                'raw_payload' => array_merge(
                    $payment->raw_payload ?? [],
                    ['webhook_data' => $sessionData]
                ),
            ]);

            // Update booking status
            $booking->update([
                'status' => BookingStatus::CONFIRMED->value,
            ]);

            Log::info('Payment succeeded', [
                'booking_reference' => $bookingReference,
                'payment_id' => $payment->id,
            ]);

            // Dispatch event for listeners (email, etc.)
            event(new PaymentSucceeded($booking, $payment));
        });
    }

    /**
     * Handle failed/expired payment from webhook.
     */
    public function handlePaymentFailure(array $sessionData, string $reason = 'failed'): void
    {
        $bookingReference = $sessionData['client_reference_id'] ?? null;
        $sessionId = $sessionData['id'] ?? null;

        if (!$bookingReference) {
            Log::warning('Payment failure webhook missing booking reference', $sessionData);
            return;
        }

        DB::transaction(function () use ($bookingReference, $sessionId, $sessionData, $reason) {
            $booking = Booking::where('reference', $bookingReference)
                ->lockForUpdate()
                ->first();

            if (!$booking) {
                Log::error('Booking not found for payment failure', [
                    'reference' => $bookingReference,
                ]);
                return;
            }

            $payment = $booking->payment;

            if ($payment) {
                // Don't overwrite a successful payment
                if ($payment->status === PaymentStatus::SUCCEEDED->value) {
                    Log::info('Ignoring failure for already successful payment', [
                        'booking_reference' => $bookingReference,
                    ]);
                    return;
                }

                $payment->update([
                    'status' => $reason === 'expired' 
                        ? PaymentStatus::CANCELLED->value 
                        : PaymentStatus::FAILED->value,
                    'raw_payload' => array_merge(
                        $payment->raw_payload ?? [],
                        ['failure_data' => $sessionData]
                    ),
                ]);
            }

            // Update booking status
            $newStatus = $reason === 'expired' 
                ? BookingStatus::EXPIRED->value 
                : BookingStatus::CANCELLED->value;
            
            $booking->update(['status' => $newStatus]);

            Log::info('Payment failed/expired', [
                'booking_reference' => $bookingReference,
                'reason' => $reason,
            ]);

            event(new PaymentFailed($booking, $payment, $reason));
        });
    }

    /**
     * Verify webhook signature and return event data.
     */
    public function verifyWebhook(string $payload, string $signature): array
    {
        return $this->gateway->verifyWebhookSignature($payload, $signature);
    }
}
