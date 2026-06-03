<?php

namespace App\Modules\Payment\Services;

use App\Modules\Payment\Gateways\PaymentGatewayInterface;
use Illuminate\Support\Facades\Log;

class PaymentGatewayManager
{
    protected array $gateways = [];

    protected array $instances = [];

    public function registerGateway(string $name, string $class): void
    {
        $this->gateways[$name] = $class;
    }

    public function getGateway(string $name): PaymentGatewayInterface
    {
        if (!isset($this->instances[$name])) {
            if (!isset($this->gateways[$name])) {
                throw new \InvalidArgumentException("Payment gateway '{$name}' is not registered.");
            }

            $class = $this->gateways[$name];
            $this->instances[$name] = app($class);
        }

        return $this->instances[$name];
    }

    public function getAllGateways(): array
    {
        return array_keys($this->gateways);
    }

    public function isRegistered(string $name): bool
    {
        return isset($this->gateways[$name]);
    }

    public function process(string $gateway, array $data): array
    {
        try {
            $instance = $this->getGateway($gateway);
            $result = $instance->initialize($data);

            Log::channel('payment')->info("Payment processed via {$gateway}", [
                'gateway' => $gateway,
                'result' => $result,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::channel('payment')->error("Payment processing error via {$gateway}: " . $e->getMessage(), [
                'gateway' => $gateway,
                'data' => $data,
            ]);

            return [
                'success' => false,
                'transaction_id' => null,
                'amount' => $data['amount'] ?? null,
                'currency' => $data['currency'] ?? config('payment.default_currency', 'BDT'),
                'status' => 'error',
                'message' => 'Payment processing error: ' . $e->getMessage(),
                'data' => [],
            ];
        }
    }

    public function verify(string $gateway, $paymentId): array
    {
        try {
            $instance = $this->getGateway($gateway);
            return $instance->verify($paymentId);
        } catch (\Exception $e) {
            Log::channel('payment')->error("Payment verification error via {$gateway}: " . $e->getMessage());
            return [
                'success' => false,
                'transaction_id' => $paymentId,
                'status' => 'error',
                'message' => 'Verification error: ' . $e->getMessage(),
                'data' => [],
            ];
        }
    }

    public function refund(string $gateway, $paymentId, $amount): array
    {
        try {
            $instance = $this->getGateway($gateway);
            return $instance->refund($paymentId, $amount);
        } catch (\Exception $e) {
            Log::channel('payment')->error("Payment refund error via {$gateway}: " . $e->getMessage());
            return [
                'success' => false,
                'transaction_id' => $paymentId,
                'amount' => $amount,
                'status' => 'error',
                'message' => 'Refund error: ' . $e->getMessage(),
                'data' => [],
            ];
        }
    }

    public function processWebhook(string $gateway, $request): array
    {
        try {
            $instance = $this->getGateway($gateway);
            return $instance->processWebhook($request);
        } catch (\Exception $e) {
            Log::channel('payment')->error("Webhook processing error via {$gateway}: " . $e->getMessage());
            return [
                'success' => false,
                'status' => 'error',
                'message' => 'Webhook processing error: ' . $e->getMessage(),
                'data' => [],
            ];
        }
    }

    public function getStatus(string $gateway, $paymentId): array
    {
        try {
            $instance = $this->getGateway($gateway);
            return $instance->getStatus($paymentId);
        } catch (\Exception $e) {
            Log::channel('payment')->error("Status check error via {$gateway}: " . $e->getMessage());
            return [
                'success' => false,
                'transaction_id' => $paymentId,
                'status' => 'error',
                'message' => 'Status check error: ' . $e->getMessage(),
                'data' => [],
            ];
        }
    }
}
