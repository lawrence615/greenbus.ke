<?php

namespace App\Http\Middleware;

use App\Exceptions\PaymentWebhookException;
use App\Services\Payment\PaymentService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyStripeWebhook
{
    public function __construct(
        private readonly PaymentService $paymentService
    ) {}

    /**
     * Handle an incoming request.
     * Verifies Stripe webhook signature before allowing the request to proceed.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        if (!$signature) {
            return response()->json(['error' => 'Missing signature'], 400);
        }

        try {
            $event = $this->paymentService->verifyWebhook($payload, $signature);
            
            // Attach verified event data to request for controller use
            $request->merge(['stripe_event' => $event]);
            
            return $next($request);
        } catch (PaymentWebhookException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}
