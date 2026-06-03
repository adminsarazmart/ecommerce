<?php

return [

    'sandbox_mode' => env('PAYMENT_SANDBOX_MODE', true),

    'default_currency' => env('PAYMENT_DEFAULT_CURRENCY', 'BDT'),

    'callback_urls' => [
        'success' => env('PAYMENT_SUCCESS_URL', '/payment/success'),
        'cancel' => env('PAYMENT_CANCEL_URL', '/payment/cancel'),
        'fail' => env('PAYMENT_FAIL_URL', '/payment/fail'),
        'ipn' => env('PAYMENT_IPN_URL', '/payment/ipn'),
    ],

    'gateways' => [

        'sslcommerz' => [
            'store_id' => env('SSLC_STORE_ID'),
            'store_password' => env('SSLC_STORE_PASSWORD'),
            'sandbox_url' => 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php',
            'live_url' => 'https://secure.sslcommerz.com/gwprocess/v4/api.php',
        ],

        'stripe' => [
            'publishable_key' => env('STRIPE_PUBLISHABLE_KEY'),
            'secret_key' => env('STRIPE_SECRET_KEY'),
            'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
            'api_url' => 'https://api.stripe.com/v1',
        ],

        'paypal' => [
            'client_id' => env('PAYPAL_CLIENT_ID'),
            'client_secret' => env('PAYPAL_CLIENT_SECRET'),
            'sandbox_url' => 'https://api-m.sandbox.paypal.com',
            'live_url' => 'https://api-m.paypal.com',
            'webhook_id' => env('PAYPAL_WEBHOOK_ID'),
        ],

        'bkash' => [
            'app_key' => env('BKASH_APP_KEY'),
            'app_secret' => env('BKASH_APP_SECRET'),
            'username' => env('BKASH_USERNAME'),
            'password' => env('BKASH_PASSWORD'),
            'sandbox_url' => 'https://tokenized.sandbox.bka.sh/v1.2.0-beta',
            'live_url' => 'https://tokenized.pay.bka.sh/v1.2.0-beta',
        ],

        'nagad' => [
            'merchant_id' => env('NAGAD_MERCHANT_ID'),
            'merchant_number' => env('NAGAD_MERCHANT_NUMBER'),
            'public_key' => env('NAGAD_PUBLIC_KEY'),
            'private_key' => env('NAGAD_PRIVATE_KEY'),
            'sandbox_url' => 'https://sandbox.mynagad.com/api',
            'live_url' => 'https://api.mynagad.com/api',
        ],

        'rocket' => [
            'api_key' => env('ROCKET_API_KEY'),
            'api_secret' => env('ROCKET_API_SECRET'),
            'merchant_number' => env('ROCKET_MERCHANT_NUMBER'),
            'sandbox_url' => 'https://sandbox.rocketbd.com/api',
            'live_url' => 'https://api.rocketbd.com/api',
        ],

        'upay' => [
            'merchant_id' => env('UPAY_MERCHANT_ID'),
            'api_key' => env('UPAY_API_KEY'),
            'api_secret' => env('UPAY_API_SECRET'),
            'sandbox_url' => 'https://sandbox.upaybd.com/api',
            'live_url' => 'https://api.upaybd.com/api',
        ],

        'amarpay' => [
            'store_id' => env('AMARPAY_STORE_ID'),
            'signature_key' => env('AMARPAY_SIGNATURE_KEY'),
            'sandbox_url' => 'https://sandbox.amarpay.com/api',
            'live_url' => 'https://secure.amarpay.com/api',
        ],

        'uddoktapay' => [
            'api_key' => env('UDDOKTAPAY_API_KEY'),
            'sandbox_url' => 'https://sandbox.uddoktapay.com/api',
            'live_url' => 'https://uddoktapay.com/api',
        ],

        'shurjopay' => [
            'merchant_name' => env('SHURJOPAY_MERCHANT_NAME'),
            'merchant_key' => env('SHURJOPAY_MERCHANT_KEY'),
            'merchant_password' => env('SHURJOPAY_MERCHANT_PASSWORD'),
            'prefix' => env('SHURJOPAY_PREFIX', 'NOK'),
            'sandbox_url' => 'https://sandbox.shurjopayment.com/api',
            'live_url' => 'https://shurjopayment.com/api',
        ],

    ],

];
