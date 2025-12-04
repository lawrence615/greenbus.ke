<?php

namespace App\Http\Controllers\Webhook;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use App\Services\Payment\PaymentService;

class StripeWebhookController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService
    ) {}

    /**
     * Handle incoming Stripe webhook events.
     * Signature verification is handled by middleware.
     */
    public function handle(Request $request): JsonResponse
    {
        $event = $request->input('stripe_event');
        
        if (!$event) {
            return response()->json(['error' => 'No event data'], 400);
        }

        $eventType = $event['type'];
        $sessionData = $event['data'];

        Log::info('Stripe webhook received', [
            'type' => $eventType,
            'event_id' => $event['id'],
        ]);

        return match ($eventType) {
            'checkout.session.completed' => $this->handleCheckoutCompleted($sessionData),
            'checkout.session.expired' => $this->handleCheckoutExpired($sessionData),
            'checkout.session.async_payment_failed' => $this->handlePaymentFailed($sessionData),
            default => $this->handleUnknownEvent($eventType),
        };
    }

    private function handleCheckoutCompleted(array $sessionData): JsonResponse
    {
        // Only process if payment was successful
        $paymentStatus = $sessionData['payment_status'] ?? null;
        
        if ($paymentStatus === 'paid') {
            $this->paymentService->handlePaymentSuccess($sessionData);
            
            return response()->json(['status' => 'success']);
        }

        // For async payment methods, payment might still be processing
        if ($paymentStatus === 'unpaid') {
            Log::info('Checkout completed but payment unpaid (async)', [
                'session_id' => $sessionData['id'] ?? null,
            ]);
        }

        return response()->json(['status' => 'acknowledged']);
    }

    private function handleCheckoutExpired(array $sessionData): JsonResponse
    {
        $this->paymentService->handlePaymentFailure($sessionData, 'expired');
        
        return response()->json(['status' => 'success']);
    }

    private function handlePaymentFailed(array $sessionData): JsonResponse
    {
        $this->paymentService->handlePaymentFailure($sessionData, 'failed');
        
        return response()->json(['status' => 'success']);
    }

    private function handleUnknownEvent(string $eventType): JsonResponse
    {
        Log::info('Unhandled Stripe webhook event', ['type' => $eventType]);
        
        return response()->json(['status' => 'ignored']);
    }
}
