<?php

namespace App\Modules\Payment\Gateways;

interface PaymentGatewayInterface
{
    public function initialize(array $data);

    public function verify($paymentId);

    public function refund($paymentId, $amount);

    public function processWebhook($request);

    public function getStatus($paymentId);
}
