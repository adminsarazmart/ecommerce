<?php

namespace App\Modules\Payment\Gateways;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UddoktaPayGateway extends BaseGateway
{
    protected string $gatewayName = 'uddoktapay';

    public function initialize(array $data): array
    {
        try {
            $payload = [
                'api_key' => $this->config['api_key'],
                'amount' => number_format((float) $data['amount'], 2, '.', ''),
                'currency' => $data['currency'] ?? 'BDT',
                'full_name' => $data['customer_name'] ?? '',
                'email' => $data['customer_email'] ?? '',
                'phone' => $data['customer_phone'] ?? '',
                'address' => $data['customer_address'] ?? '',
                'metadata' => $data['metadata'] ?? [],
                'redirect_url' => $data['success_url'] ?? url(config('payment.callback_urls.success')),
                'cancel_url' => $data['cancel_url'] ?? url(config('payment.callback_urls.cancel')),
                'webhook_url' => $data['ipn_url'] ?? url(config('payment.callback_urls.ipn')),
            ];

            $response = $this->http()->post($this->getBaseUrl() . '/create-payment', $payload);

            $result = $response->json();

            $this->logTransaction('init', '/create-payment', $payload, $result);

            if ($response->successful() && ($result['status'] ?? '') === 'success') {
                return $this->formatResponse(true, [
                    'transaction_id' => $result['transaction_id'] ?? $result['invoice_id'] ?? null,
                    'amount' => $data['amount'],
                    'currency' => 'BDT',
                    'status' => 'pending',
                    'message' => 'UddoktaPay payment initialized successfully',
                    'data' => [
                        'gateway_url' => $result['payment_url'] ?? $result['redirect_url'] ?? null,
                        'invoice_id' => $result['invoice_id'] ?? null,
                    ],
                ]);
            }

            return $this->formatResponse(false, [
                'status' => 'failed',
                'message' => $result['message'] ?? 'Failed to initialize UddoktaPay payment',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            $this->logTransaction('init_error', '/create-payment', $data, ['error' => $e->getMessage()]);
            return $this->formatResponse(false, [
                'message' => 'UddoktaPay initialization error: ' . $e->getMessage(),
            ]);
        }
    }

    public function verify($paymentId): array
    {
        try {
            $response = $this->http()->post($this->getBaseUrl() . '/verify-payment', [
                'api_key' => $this->config['api_key'],
                'invoice_id' => $paymentId,
            ]);

            $result = $response->json();

            $this->logTransaction('verify', '/verify-payment', ['invoice_id' => $paymentId], $result, $paymentId);

            if ($response->successful()) {
                $isCompleted = ($result['status'] ?? '') === 'completed' || ($result['payment_status'] ?? '') === 'Completed';

                return $this->formatResponse($isCompleted, [
                    'transaction_id' => $paymentId,
                    'amount' => $result['amount'] ?? null,
                    'currency' => 'BDT',
                    'status' => $result['payment_status'] ?? $result['status'] ?? 'unknown',
                    'message' => $isCompleted ? 'Payment verified successfully' : ($result['message'] ?? 'Payment not completed'),
                    'data' => $result,
                ]);
            }

            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'status' => 'not_found',
                'message' => $result['message'] ?? 'Payment verification failed',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            $this->logTransaction('verify_error', '/verify-payment', ['invoice_id' => $paymentId], ['error' => $e->getMessage()], $paymentId);
            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'message' => 'UddoktaPay verify error: ' . $e->getMessage(),
            ]);
        }
    }

    public function refund($paymentId, $amount): array
    {
        try {
            $response = $this->http()->post($this->getBaseUrl() . '/refund-payment', [
                'api_key' => $this->config['api_key'],
                'invoice_id' => $paymentId,
                'amount' => number_format((float) $amount, 2, '.', ''),
            ]);

            $result = $response->json();

            $this->logTransaction('refund', '/refund-payment', [
                'invoice_id' => $paymentId,
                'amount' => $amount,
            ], $result, $paymentId);

            if ($response->successful() && ($result['status'] ?? '') === 'success') {
                return $this->formatResponse(true, [
                    'transaction_id' => $paymentId,
                    'amount' => $amount,
                    'currency' => 'BDT',
                    'status' => 'refunded',
                    'message' => 'Refund processed successfully',
                    'data' => $result,
                ]);
            }

            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'amount' => $amount,
                'status' => 'refund_failed',
                'message' => $result['message'] ?? 'Refund failed',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            $this->logTransaction('refund_error', '/refund-payment', ['invoice_id' => $paymentId, 'amount' => $amount], ['error' => $e->getMessage()], $paymentId);
            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'amount' => $amount,
                'message' => 'UddoktaPay refund error: ' . $e->getMessage(),
            ]);
        }
    }

    public function processWebhook($request): array
    {
        $data = $request->all();
        $this->logTransaction('webhook', 'webhook', $data, $data, $data['invoice_id'] ?? null);

        $paymentId = $data['invoice_id'] ?? null;
        if (!$paymentId) {
            return $this->formatResponse(false, [
                'message' => 'Invalid webhook: missing invoice ID',
            ]);
        }

        return $this->verify($paymentId);
    }

    public function getStatus($paymentId): array
    {
        return $this->verify($paymentId);
    }
}
