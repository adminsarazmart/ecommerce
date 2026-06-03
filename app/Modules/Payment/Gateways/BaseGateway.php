<?php

namespace App\Modules\Payment\Gateways;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

abstract class BaseGateway implements PaymentGatewayInterface
{
    protected array $config;

    protected string $gatewayName;

    protected ?string $sandboxUrl;

    protected ?string $liveUrl;

    public function __construct()
    {
        $this->loadConfig();
    }

    protected function loadConfig(): void
    {
        $this->config = config("payment.gateways.{$this->gatewayName}", []);
        $this->sandboxUrl = $this->config['sandbox_url'] ?? null;
        $this->liveUrl = $this->config['live_url'] ?? null;
    }

    protected function getBaseUrl(): string
    {
        return config('payment.sandbox_mode', true)
            ? ($this->sandboxUrl ?? $this->liveUrl)
            : ($this->liveUrl ?? $this->sandboxUrl);
    }

    protected function http(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::timeout(30)
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => config('app.name', 'EcommerceApp') . '/1.0',
            ]);
    }

    protected function logTransaction(string $type, string $endpoint, array $request, $response, ?string $paymentId = null): void
    {
        $logData = [
            'gateway' => $this->gatewayName,
            'type' => $type,
            'endpoint' => $endpoint,
            'request_data' => $request,
            'response' => is_array($response) ? $response : (is_string($response) ? json_decode($response, true) : $response),
            'payment_id' => $paymentId,
        ];

        Log::channel('payment')->info("{$this->gatewayName} {$type}", $logData);

        try {
            if (class_exists(\App\Modules\Payment\Models\PaymentLog::class)) {
                \App\Modules\Payment\Models\PaymentLog::create($logData);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to log payment transaction to database: ' . $e->getMessage());
        }
    }

    protected function formatResponse(bool $success, array $data = []): array
    {
        return array_merge([
            'success' => $success,
            'transaction_id' => $data['transaction_id'] ?? null,
            'amount' => $data['amount'] ?? null,
            'currency' => $data['currency'] ?? config('payment.default_currency', 'BDT'),
            'status' => $data['status'] ?? ($success ? 'completed' : 'failed'),
            'message' => $data['message'] ?? ($success ? 'Payment processed successfully' : 'Payment processing failed'),
            'data' => $data['data'] ?? $data,
        ], $data);
    }

    protected function generateTransactionId(): string
    {
        return strtoupper($this->gatewayName) . '-' . Str::uuid()->toString();
    }
}
