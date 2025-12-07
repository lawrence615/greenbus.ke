<?php

namespace App\Providers;

use App\Contracts\PaymentGatewayInterface;
use App\Services\Payment\FlutterwavePaymentGateway;
use App\Services\Payment\PaymentService;
use App\Services\Payment\StripePaymentGateway;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Supported payment gateways.
     */
    private array $gateways = [
        'stripe' => StripePaymentGateway::class,
        'flutterwave' => FlutterwavePaymentGateway::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind the payment gateway interface based on config
        $this->app->bind(PaymentGatewayInterface::class, function ($app) {
            $provider = config('services.payment.default', 'flutterwave');
            
            if (!isset($this->gateways[$provider])) {
                throw new \InvalidArgumentException("Unsupported payment provider: {$provider}");
            }

            return $app->make($this->gateways[$provider]);
        });

        // Register PaymentService as a singleton
        $this->app->singleton(PaymentService::class, function ($app) {
            return new PaymentService(
                $app->make(PaymentGatewayInterface::class)
            );
        });
    }

    /**
     * Bootstrap services.
     * 
     * Note: Event listeners (SendBookingConfirmationOnPayment, LogPaymentFailure)
     * are auto-discovered by Laravel via their type-hinted handle() methods.
     */
    public function boot(): void
    {
        //
    }
}
