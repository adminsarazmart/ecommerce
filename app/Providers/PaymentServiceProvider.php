<?php

namespace App\Providers;

use App\Modules\Payment\Gateways\AmarPayGateway;
use App\Modules\Payment\Gateways\BkashGateway;
use App\Modules\Payment\Gateways\NagadGateway;
use App\Modules\Payment\Gateways\PayPalGateway;
use App\Modules\Payment\Gateways\RocketGateway;
use App\Modules\Payment\Gateways\ShurjoPayGateway;
use App\Modules\Payment\Gateways\SSLCommerzGateway;
use App\Modules\Payment\Gateways\StripeGateway;
use App\Modules\Payment\Gateways\UddoktaPayGateway;
use App\Modules\Payment\Gateways\UpayGateway;
use App\Modules\Payment\Services\PaymentGatewayManager;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PaymentGatewayManager::class, function ($app) {
            $manager = new PaymentGatewayManager();

            $manager->registerGateway('sslcommerz', SSLCommerzGateway::class);
            $manager->registerGateway('stripe', StripeGateway::class);
            $manager->registerGateway('paypal', PayPalGateway::class);
            $manager->registerGateway('bkash', BkashGateway::class);
            $manager->registerGateway('nagad', NagadGateway::class);
            $manager->registerGateway('rocket', RocketGateway::class);
            $manager->registerGateway('upay', UpayGateway::class);
            $manager->registerGateway('amarpay', AmarPayGateway::class);
            $manager->registerGateway('uddoktapay', UddoktaPayGateway::class);
            $manager->registerGateway('shurjopay', ShurjoPayGateway::class);

            return $manager;
        });
    }

    public function boot(): void
    {
        //
    }
}
