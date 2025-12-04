<?php

use App\Http\Controllers\Webhook\FlutterwaveWebhookController;
use App\Http\Controllers\Webhook\StripeWebhookController;
use App\Http\Middleware\VerifyFlutterwaveWebhook;
use App\Http\Middleware\VerifyStripeWebhook;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Webhook Routes
|--------------------------------------------------------------------------
|
| These routes handle incoming webhooks from payment providers.
| They are excluded from CSRF protection and use signature verification.
|
*/

Route::post('/webhooks/stripe', [StripeWebhookController::class, 'handle'])
    ->middleware(VerifyStripeWebhook::class)
    ->name('webhooks.stripe');

Route::post('/webhooks/flutterwave', [FlutterwaveWebhookController::class, 'handle'])
    ->middleware(VerifyFlutterwaveWebhook::class)
    ->name('webhooks.flutterwave');
