<?php

namespace App\Modules\Payment\Gateways;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BkashGateway extends BaseGateway
{
    protected string $gatewayName = 'bkash';

    private ?string $idToken = null;

    private ?string $refreshToken = null;

    protected function grantToken(): ?string
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'x-app-key' => $this->config['app_key'],
            ])->post($this->getBaseUrl() . '/tokenized/checkout/token/grant', [
                'app_key' => $this->config['app_key'],
                'app_secret' => $this->config['app_secret'],
            ]);

            $result = $response->json();

            if ($response->successful() && !empty($result['id_token'])) {
                $this->idToken = $result['id_token'];
                $this->refreshToken = $result['refresh_token'] ?? null;
                return $this->idToken;
            }

            Log::error('bKash token grant failed', ['response' => $result]);
            return null;
        } catch (\Exception $e) {
            Log::error('bKash token grant exception: ' . $e->getMessage());
            return null;
        }
    }

    protected function refreshTokenGrant(): ?string
    {
        if (empty($this->refreshToken)) {
            return $this->grantToken();
        }

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'x-app-key' => $this->config['app_key'],
            ])->post($this->getBaseUrl() . '/tokenized/checkout/token/refresh', [
                'app_key' => $this->config['app_key'],
                'app_secret' => $this->config['app_secret'],
                'refresh_token' => $this->refreshToken,
            ]);

            $result = $response->json();

            if ($response->successful() && !empty($result['id_token'])) {
                $this->idToken = $result['id_token'];
                $this->refreshToken = $result['refresh_token'] ?? null;
                return $this->idToken;
            }

            return $this->grantToken();
        } catch (\Exception $e) {
            return $this->grantToken();
        }
    }

    protected function httpWithToken(): \Illuminate\Http\Client\PendingRequest
    {
        if (!$this->idToken) {
            $this->grantToken();
        }

        return $this->http()->withHeaders([
            'Authorization' => $this->idToken,
            'x-app-key' => $this->config['app_key'],
        ]);
    }

    public function initialize(array $data): array
    {
        return $this->createPayment($data);
    }

    public function createPayment(array $data): array
    {
        try {
            $this->grantToken();

            $payload = [
                'mode' => '0011',
                'payerReference' => $data['customer_id'] ?? uniqid(),
                'callbackURL' => $data['callback_url'] ?? url(config('payment.callback_urls.success')),
                'amount' => number_format((float) $data['amount'], 2, '.', ''),
                'currency' => 'BDT',
                'intent' => 'sale',
                'merchantInvoiceNumber' => $data['invoice_number'] ?? $this->generateTransactionId(),
            ];

            $response = $this->httpWithToken()
                ->post($this->getBaseUrl() . '/tokenized/checkout/create', $payload);

            $result = $response->json();

            $this->logTransaction('create_payment', '/tokenized/checkout/create', $payload, $result, $payload['merchantInvoiceNumber']);

            if ($response->successful() && !empty($result['bkashURL'])) {
                return $this->formatResponse(true, [
                    'transaction_id' => $result['paymentID'] ?? null,
                    'amount' => $data['amount'],
                    'currency' => 'BDT',
                    'status' => 'pending',
                    'message' => 'bKash payment created successfully',
                    'data' => [
                        'payment_id' => $result['paymentID'] ?? null,
                        'gateway_url' => $result['bkashURL'] ?? null,
                    ],
                ]);
            }

            return $this->formatResponse(false, [
                'status' => 'failed',
                'message' => $result['errorMessage'] ?? $result['message'] ?? 'Failed to create bKash payment',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            $this->logTransaction('create_payment_error', '/tokenized/checkout/create', $data, ['error' => $e->getMessage()]);
            return $this->formatResponse(false, [
                'message' => 'bKash create payment error: ' . $e->getMessage(),
            ]);
        }
    }

    public function executePayment(string $paymentId): array
    {
        try {
            $this->grantToken();

            $response = $this->httpWithToken()
                ->post($this->getBaseUrl() . '/tokenized/checkout/execute', [
                    'paymentID' => $paymentId,
                ]);

            $result = $response->json();

            $this->logTransaction('execute_payment', '/tokenized/checkout/execute', ['paymentID' => $paymentId], $result, $paymentId);

            if ($response->successful() && ($result['transactionStatus'] ?? '') === 'Completed') {
                return $this->formatResponse(true, [
                    'transaction_id' => $result['trxID'] ?? $paymentId,
                    'amount' => $result['amount'] ?? null,
                    'currency' => 'BDT',
                    'status' => 'completed',
                    'message' => 'Payment executed successfully',
                    'data' => $result,
                ]);
            }

            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'status' => strtolower($result['transactionStatus'] ?? 'failed'),
                'message' => $result['errorMessage'] ?? $result['statusMessage'] ?? 'Payment execution failed',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            $this->logTransaction('execute_payment_error', '/tokenized/checkout/execute', ['paymentID' => $paymentId], ['error' => $e->getMessage()], $paymentId);
            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'message' => 'bKash execute payment error: ' . $e->getMessage(),
            ]);
        }
    }

    public function verify($paymentId): array
    {
        try {
            $this->grantToken();

            $response = $this->httpWithToken()
                ->post($this->getBaseUrl() . '/tokenized/checkout/status', [
                    'paymentID' => $paymentId,
                ]);

            $result = $response->json();

            $this->logTransaction('verify_payment', '/tokenized/checkout/status', ['paymentID' => $paymentId], $result, $paymentId);

            if ($response->successful()) {
                $statusMap = [
                    'Completed' => 'completed',
                    'Initiated' => 'pending',
                    'Pending' => 'pending',
                    'Failed' => 'failed',
                    'Cancelled' => 'cancelled',
                    'Refunded' => 'refunded',
                ];

                $isCompleted = ($result['transactionStatus'] ?? '') === 'Completed';

                return $this->formatResponse($isCompleted, [
                    'transaction_id' => $result['trxID'] ?? $paymentId,
                    'amount' => $result['amount'] ?? null,
                    'currency' => 'BDT',
                    'status' => $statusMap[$result['transactionStatus'] ?? ''] ?? $result['transactionStatus'],
                    'message' => $isCompleted ? 'Payment verified successfully' : "Payment status: {$result['transactionStatus']}",
                    'data' => $result,
                ]);
            }

            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'status' => 'not_found',
                'message' => $result['errorMessage'] ?? 'Payment not found',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            $this->logTransaction('verify_error', '/tokenized/checkout/status', ['paymentID' => $paymentId], ['error' => $e->getMessage()], $paymentId);
            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'message' => 'bKash verify error: ' . $e->getMessage(),
            ]);
        }
    }

    public function refund($paymentId, $amount): array
    {
        return $this->refundTransaction($paymentId, $amount);
    }

    public function refundTransaction(string $paymentId, float $amount, ?string $trxId = null): array
    {
        try {
            $this->grantToken();

            $payload = [
                'paymentID' => $paymentId,
                'amount' => number_format((float) $amount, 2, '.', ''),
                'trxID' => $trxId ?? $paymentId,
                'sku' => 'refund',
                'reason' => 'Customer requested refund',
            ];

            $response = $this->httpWithToken()
                ->post($this->getBaseUrl() . '/tokenized/checkout/refund', $payload);

            $result = $response->json();

            $this->logTransaction('refund', '/tokenized/checkout/refund', $payload, $result, $paymentId);

            if ($response->successful() && ($result['transactionStatus'] ?? '') === 'Completed') {
                return $this->formatResponse(true, [
                    'transaction_id' => $paymentId,
                    'amount' => $amount,
                    'currency' => 'BDT',
                    'status' => 'refunded',
                    'message' => 'Refund processed successfully',
                    'data' => [
                        'refund_trx_id' => $result['refundTrxID'] ?? null,
                    ],
                ]);
            }

            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'amount' => $amount,
                'status' => 'refund_failed',
                'message' => $result['errorMessage'] ?? $result['statusMessage'] ?? 'Refund failed',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            $this->logTransaction('refund_error', '/tokenized/checkout/refund', ['paymentID' => $paymentId, 'amount' => $amount], ['error' => $e->getMessage()], $paymentId);
            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'amount' => $amount,
                'message' => 'bKash refund error: ' . $e->getMessage(),
            ]);
        }
    }

    public function processWebhook($request): array
    {
        $data = $request->all();
        $this->logTransaction('webhook', 'webhook/callback', $data, $data, $data['paymentID'] ?? null);

        $paymentId = $data['paymentID'] ?? null;
        if (!$paymentId) {
            return $this->formatResponse(false, [
                'message' => 'Invalid webhook: missing payment ID',
            ]);
        }

        return $this->verify($paymentId);
    }

    public function getStatus($paymentId): array
    {
        return $this->verify($paymentId);
    }
}
