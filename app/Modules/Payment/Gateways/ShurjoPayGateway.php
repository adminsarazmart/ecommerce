<?php

namespace App\Modules\Payment\Gateways;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShurjoPayGateway extends BaseGateway
{
    protected string $gatewayName = 'shurjopay';

    protected ?string $token = null;

    protected function authenticate(): ?string
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($this->getBaseUrl() . '/auth/token', [
                'username' => $this->config['merchant_name'],
                'password' => $this->config['merchant_password'],
            ]);

            $result = $response->json();

            if ($response->successful() && !empty($result['token'])) {
                $this->token = $result['token'];
                return $this->token;
            }

            \Illuminate\Support\Facades\Log::error('ShurjoPay auth failed', ['response' => $result]);
            return null;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('ShurjoPay auth exception: ' . $e->getMessage());
            return null;
        }
    }

    public function initialize(array $data): array
    {
        return $this->createPayment($data);
    }

    public function createPayment(array $data): array
    {
        try {
            $this->authenticate();

            $prefix = $this->config['prefix'] ?? 'NOK';
            $invoiceNo = $prefix . uniqid();

            $payload = [
                'token' => $this->token,
                'orderId' => $data['order_id'] ?? uniqid(),
                'currency' => $data['currency'] ?? 'BDT',
                'amount' => number_format((float) $data['amount'], 2, '.', ''),
                'discsountAmount' => 0,
                'discPercent' => 0,
                'customerName' => $data['customer_name'] ?? '',
                'customerPhone' => $data['customer_phone'] ?? '',
                'customerEmail' => $data['customer_email'] ?? '',
                'customerAddress' => $data['customer_address'] ?? '',
                'customerCity' => $data['customer_city'] ?? '',
                'customerState' => $data['customer_state'] ?? '',
                'customerPostcode' => $data['customer_postcode'] ?? '',
                'customerCountry' => $data['customer_country'] ?? 'Bangladesh',
                'returnUrl' => $data['success_url'] ?? url(config('payment.callback_urls.success')),
                'cancelUrl' => $data['cancel_url'] ?? url(config('payment.callback_urls.cancel')),
                'clientIP' => $data['client_ip'] ?? request()->ip(),
                'value1' => $data['value1'] ?? '',
                'value2' => $data['value2'] ?? '',
                'value3' => $data['value3'] ?? '',
                'value4' => $data['value4'] ?? '',
            ];

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token,
            ])->post($this->getBaseUrl() . '/payment/create', $payload);

            $result = $response->json();

            $this->logTransaction('create_payment', '/payment/create', $payload, $result, $invoiceNo);

            if ($response->successful() && ($result['status'] ?? '') === 'success') {
                return $this->formatResponse(true, [
                    'transaction_id' => $result['order_id'] ?? $invoiceNo,
                    'amount' => $data['amount'],
                    'currency' => 'BDT',
                    'status' => 'pending',
                    'message' => 'ShurjoPay payment created successfully',
                    'data' => [
                        'gateway_url' => $result['payment_url'] ?? $result['checkout_url'] ?? null,
                        'order_id' => $result['order_id'] ?? null,
                    ],
                ]);
            }

            return $this->formatResponse(false, [
                'status' => 'failed',
                'message' => $result['message'] ?? $result['error'] ?? 'Failed to create ShurjoPay payment',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            $this->logTransaction('create_payment_error', '/payment/create', $data, ['error' => $e->getMessage()]);
            return $this->formatResponse(false, [
                'message' => 'ShurjoPay create payment error: ' . $e->getMessage(),
            ]);
        }
    }

    public function verify($paymentId): array
    {
        try {
            $this->authenticate();

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token,
            ])->post($this->getBaseUrl() . '/payment/status', [
                'token' => $this->token,
                'order_id' => $paymentId,
            ]);

            $result = $response->json();

            $this->logTransaction('verify', '/payment/status', ['order_id' => $paymentId], $result, $paymentId);

            if ($response->successful()) {
                $statusMap = [
                    'Completed' => 'completed',
                    'Pending' => 'pending',
                    'Processing' => 'processing',
                    'Failed' => 'failed',
                    'Cancelled' => 'cancelled',
                    'Refunded' => 'refunded',
                ];

                $paymentStatus = $result['payment_status'] ?? $result['status'] ?? '';
                $isCompleted = $paymentStatus === 'Completed';

                return $this->formatResponse($isCompleted, [
                    'transaction_id' => $paymentId,
                    'amount' => $result['amount'] ?? null,
                    'currency' => 'BDT',
                    'status' => $statusMap[$paymentStatus] ?? strtolower($paymentStatus),
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
            $this->logTransaction('verify_error', '/payment/status', ['order_id' => $paymentId], ['error' => $e->getMessage()], $paymentId);
            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'message' => 'ShurjoPay verify error: ' . $e->getMessage(),
            ]);
        }
    }

    public function refund($paymentId, $amount): array
    {
        try {
            $this->authenticate();

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token,
            ])->post($this->getBaseUrl() . '/payment/refund', [
                'token' => $this->token,
                'order_id' => $paymentId,
                'amount' => number_format((float) $amount, 2, '.', ''),
            ]);

            $result = $response->json();

            $this->logTransaction('refund', '/payment/refund', [
                'order_id' => $paymentId,
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
            $this->logTransaction('refund_error', '/payment/refund', ['order_id' => $paymentId, 'amount' => $amount], ['error' => $e->getMessage()], $paymentId);
            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'amount' => $amount,
                'message' => 'ShurjoPay refund error: ' . $e->getMessage(),
            ]);
        }
    }

    public function processWebhook($request): array
    {
        $data = $request->all();
        $this->logTransaction('webhook', 'webhook', $data, $data, $data['order_id'] ?? null);

        $paymentId = $data['order_id'] ?? null;
        if (!$paymentId) {
            return $this->formatResponse(false, [
                'message' => 'Invalid webhook: missing order ID',
            ]);
        }

        return $this->verify($paymentId);
    }

    public function getStatus($paymentId): array
    {
        return $this->verify($paymentId);
    }
}
