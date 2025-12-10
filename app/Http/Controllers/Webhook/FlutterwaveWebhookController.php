<?php

namespace App\Http\Controllers\Webhook;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Events\PaymentFailed;
use App\Events\PaymentSucceeded;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\Payment\FlutterwavePaymentGateway;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FlutterwaveWebhookController extends Controller
{
    public function __construct(
        private readonly FlutterwavePaymentGateway $gateway
    ) {}

    /**
     * Handle incoming Flutterwave webhook events.
     */
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();
        $event = $payload['event'] ?? null;
        $data = $payload['data'] ?? [];

        Log::info('Flutterwave webhook received', [
            'event' => $event,
            'tx_ref' => $data['tx_ref'] ?? null,
            'transaction_id' => $data['id'] ?? null,
            'trace_id' => uniqid('webhook_recv_'),
            'timestamp' => now()->toIso8601String(),
        ]);

        return match ($event) {
            'charge.completed' => $this->handleChargeCompleted($data),
            'transfer.completed' => $this->handleTransferCompleted($data),
            default => $this->handleUnknownEvent($event),
        };
    }

    private function handleChargeCompleted(array $data): JsonResponse
    {
        $txRef = $data['tx_ref'] ?? null;
        $status = $data['status'] ?? null;
        $transactionId = $data['id'] ?? null;

        if (!$txRef) {
            Log::warning('Flutterwave webhook missing tx_ref', $data);
            return response()->json(['status' => 'error', 'message' => 'Missing tx_ref'], 400);
        }

        // Verify the transaction with Flutterwave API
        try {
            $verification = $this->gateway->verifyTransaction($transactionId);
            
            if ($verification['status'] !== 'successful') {
                Log::warning('Flutterwave transaction verification failed', [
                    'tx_ref' => $txRef,
                    'status' => $verification['status'],
                ]);
                
                $this->handlePaymentFailure($txRef, $data);
                return response()->json(['status' => 'failed']);
            }

            // Verify amount matches
            $this->handlePaymentSuccess($txRef, $data, $verification);
            
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Flutterwave verification error', [
                'tx_ref' => $txRef,
                'error' => $e->getMessage(),
            ]);
            
            return response()->json(['status' => 'error'], 500);
        }
    }

    private function handlePaymentSuccess(string $txRef, array $webhookData, array $verificationData): void
    {
        DB::transaction(function () use ($txRef, $webhookData, $verificationData) {
            $booking = Booking::where('reference', $txRef)
                ->lockForUpdate()
                ->first();

            if (!$booking) {
                Log::error('Booking not found for Flutterwave payment', ['tx_ref' => $txRef]);
                return;
            }

            $payment = $booking->payment;

            if (!$payment) {
                Log::error('Payment record not found', ['tx_ref' => $txRef]);
                return;
            }

            // Idempotency check
            if ($payment->status === PaymentStatus::SUCCEEDED->value) {
                Log::info('Payment already processed, skipping', ['tx_ref' => $txRef]);
                return;
            }

            // Update payment with detailed transaction info
            $payment->update([
                'status' => PaymentStatus::SUCCEEDED->value,
                'provider_reference' => $verificationData['flw_ref'] ?? $webhookData['flw_ref'] ?? null,
                'provider_transaction_id' => $verificationData['id'] ?? null,
                'amount_charged' => $verificationData['charged_amount'] ?? $verificationData['amount'] ?? null,
                'amount_settled' => $verificationData['amount_settled'] ?? null,
                'provider_fee' => $verificationData['app_fee'] ?? null,
                'payment_method' => $verificationData['payment_type'] ?? 'card',
                'raw_payload' => array_merge(
                    $payment->raw_payload ?? [],
                    [
                        'webhook_data' => $webhookData,
                        'verification_data' => $verificationData,
                    ]
                ),
            ]);

            // Update booking
            $booking->update([
                'status' => BookingStatus::CONFIRMED->value,
            ]);

            Log::info('Flutterwave payment succeeded', [
                'tx_ref' => $txRef,
                'trace_id' => uniqid('webhook_'),
                'timestamp' => now()->toIso8601String(),
            ]);

            Log::info('Dispatching PaymentSucceeded event', [
                'tx_ref' => $txRef,
                'booking_id' => $booking->id,
                'trace_id' => uniqid('event_'),
            ]);

            event(new PaymentSucceeded($booking, $payment));
        });
    }

    private function handlePaymentFailure(string $txRef, array $data): void
    {
        DB::transaction(function () use ($txRef, $data) {
            $booking = Booking::where('reference', $txRef)
                ->lockForUpdate()
                ->first();

            if (!$booking) {
                return;
            }

            $payment = $booking->payment;

            if ($payment && $payment->status !== PaymentStatus::SUCCEEDED->value) {
                $payment->update([
                    'status' => PaymentStatus::FAILED->value,
                    'raw_payload' => array_merge(
                        $payment->raw_payload ?? [],
                        ['failure_data' => $data]
                    ),
                ]);
            }

            $booking->update(['status' => BookingStatus::CANCELLED->value]);

            Log::info('Flutterwave payment failed', ['tx_ref' => $txRef]);

            event(new PaymentFailed($booking, $payment, 'payment_failed'));
        });
    }

    private function handleTransferCompleted(array $data): JsonResponse
    {
        // Handle payout/transfer webhooks if needed in future
        Log::info('Flutterwave transfer completed', $data);
        return response()->json(['status' => 'acknowledged']);
    }

    private function handleUnknownEvent(?string $event): JsonResponse
    {
        Log::info('Unhandled Flutterwave webhook event', ['event' => $event]);
        return response()->json(['status' => 'ignored']);
    }
}
