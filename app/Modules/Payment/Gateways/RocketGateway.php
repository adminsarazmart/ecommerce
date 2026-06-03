<?php

namespace App\Modules\Payment\Gateways;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RocketGateway extends BaseGateway
{
    protected string $gatewayName = 'rocket';

    public function initialize(array $data): array
    {
        try {
            $payload = [
                'api_key' => $this->config['api_key'],
                'api_secret' => $this->config['api_secret'],
                'merchant_number' => $this->config['merchant_number'],
                'trx_id' => $data['transaction_id'] ?? $this->generateTransactionId(),
                'amount' => number_format((float) $data['amount'], 2, '.', ''),
                'currency' => $data['currency'] ?? 'BDT',
                'customer_name' => $data['customer_name'] ?? '',
                'customer_mobile' => $data['customer_phone'] ?? '',
                'customer_email' => $data['customer_email'] ?? '',
                'customer_address' => $data['customer_address'] ?? '',
                'description' => $data['description'] ?? 'Order Payment',
                'callback_url' => $data['callback_url'] ?? url(config('payment.callback_urls.success')),
                'cancel_url' => $data['cancel_url'] ?? url(config('payment.callback_urls.cancel')),
                'ipn_url' => $data['ipn_url'] ?? url(config('payment.callback_urls.ipn')),
            ];

            $response = $this->http()->post($this->getBaseUrl() . '/payment/init', $payload);

            $result = $response->json();

            $this->logTransaction('init', '/payment/init', $payload, $result, $payload['trx_id']);

            if ($response->successful() && ($result['status'] ?? '') === 'success') {
                return $this->formatResponse(true, [
                    'transaction_id' => $payload['trx_id'],
                    'amount' => $data['amount'],
                    'currency' => 'BDT',
                    'status' => 'pending',
                    'message' => 'Rocket payment initialized successfully',
                    'data' => [
                        'gateway_url' => $result['payment_url'] ?? $result['redirect_url'] ?? null,
                        'reference' => $result['reference'] ?? null,
                    ],
                ]);
            }

            return $this->formatResponse(false, [
                'transaction_id' => $payload['trx_id'],
                'status' => 'failed',
                'message' => $result['message'] ?? 'Failed to initialize Rocket payment',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            $this->logTransaction('init_error', '/payment/init', $data, ['error' => $e->getMessage()]);
            return $this->formatResponse(false, [
                'message' => 'Rocket initialization error: ' . $e->getMessage(),
            ]);
        }
    }

    public function verify($paymentId): array
    {
        try {
            $response = $this->http()->post($this->getBaseUrl() . '/payment/status', [
                'api_key' => $this->config['api_key'],
                'api_secret' => $this->config['api_secret'],
                'trx_id' => $paymentId,
            ]);

            $result = $response->json();

            $this->logTransaction('verify', '/payment/status', ['trx_id' => $paymentId], $result, $paymentId);

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
            $this->logTransaction('verify_error', '/payment/status', ['trx_id' => $paymentId], ['error' => $e->getMessage()], $paymentId);
            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'message' => 'Rocket verify error: ' . $e->getMessage(),
            ]);
        }
    }

    public function refund($paymentId, $amount): array
    {
        try {
            $response = $this->http()->post($this->getBaseUrl() . '/payment/refund', [
                'api_key' => $this->config['api_key'],
                'api_secret' => $this->config['api_secret'],
                'trx_id' => $paymentId,
                'amount' => number_format((float) $amount, 2, '.', ''),
                'refund_trx_id' => $this->generateTransactionId(),
            ]);

            $result = $response->json();

            $this->logTransaction('refund', '/payment/refund', [
                'trx_id' => $paymentId,
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
            $this->logTransaction('refund_error', '/payment/refund', ['trx_id' => $paymentId, 'amount' => $amount], ['error' => $e->getMessage()], $paymentId);
            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'amount' => $amount,
                'message' => 'Rocket refund error: ' . $e->getMessage(),
            ]);
        }
    }

    public function processWebhook($request): array
    {
        $data = $request->all();
        $this->logTransaction('webhook', 'webhook', $data, $data, $data['trx_id'] ?? null);

        $paymentId = $data['trx_id'] ?? null;
        if (!$paymentId) {
            return $this->formatResponse(false, [
                'message' => 'Invalid webhook: missing transaction ID',
            ]);
        }

        return $this->verify($paymentId);
    }

    public function getStatus($paymentId): array
    {
        return $this->verify($paymentId);
    }
}
