<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyFlutterwaveWebhook
{
    /**
     * Handle an incoming request.
     * Verifies Flutterwave webhook using the secret hash.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $signature = $request->header('verif-hash');
        $expectedSignature = config('services.flutterwave.webhook_secret');

        if (!$signature || !$expectedSignature) {
            return response()->json(['error' => 'Missing signature'], 400);
        }

        if (!hash_equals($expectedSignature, $signature)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        return $next($request);
    }
}
