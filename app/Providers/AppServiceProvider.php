<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;

use App\Services\PaymentService;
use App\Services\StripePayment;
use App\Services\RazorpayPayment;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentService::class, function ($app) {

            if (config('payment.gateway') === 'stripe') {

                return new PaymentService(
                    $app->make(StripePayment::class)
                );
            }

            if (config('payment.gateway') === 'razorpay') {

                return new PaymentService(
                    $app->make(RazorpayPayment::class)
                );
            }

            throw new \Exception(
                'Unsupported payment gateway: ' .
                config('payment.gateway')
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Scramble::configure()
        ->withDocumentTransformers(function (OpenApi $openApi) {
            $openApi->secure(
                SecurityScheme::http('bearer')
            );
        });
    }
}
