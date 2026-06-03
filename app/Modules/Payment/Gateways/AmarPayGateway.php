<?php

namespace App\Modules\Payment\Gateways;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AmarPayGateway extends BaseGateway
{
    protected string $gatewayName = 'amarpay';

    public function initialize(array $data): array
    {
        try {
            $payload = [
                'store_id' => $this->config['store_id'],
                'signature_key' => $this->config['signature_key'],
                'tran_id' => $data['transaction_id'] ?? $this->generateTransactionId(),
                'amount' => number_format((float) $data['amount'], 2, '.', ''),
                'currency' => $data['currency'] ?? 'BDT',
                'cus_name' => $data['customer_name'] ?? '',
                'cus_email' => $data['customer_email'] ?? '',
                'cus_phone' => $data['customer_phone'] ?? '',
                'cus_add1' => $data['customer_address'] ?? '',
                'cus_city' => $data['customer_city'] ?? '',
                'cus_country' => $data['customer_country'] ?? 'Bangladesh',
                'desc' => $data['description'] ?? 'Order Payment',
                'success_url' => $data['success_url'] ?? url(config('payment.callback_urls.success')),
                'cancel_url' => $data['cancel_url'] ?? url(config('payment.callback_urls.cancel')),
                'fail_url' => $data['fail_url'] ?? url(config('payment.callback_urls.fail')),
                'ipn_url' => $data['ipn_url'] ?? url(config('payment.callback_urls.ipn')),
            ];

            $response = $this->http()->asForm()->post($this->getBaseUrl() . '/payment/init', $payload);

            $result = $response->json();

            $this->logTransaction('init', '/payment/init', $payload, $result, $payload['tran_id']);

            if ($response->successful() && ($result['status'] ?? '') === 'success') {
                return $this->formatResponse(true, [
                    'transaction_id' => $payload['tran_id'],
                    'amount' => $data['amount'],
                    'currency' => 'BDT',
                    'status' => 'pending',
                    'message' => 'AmarPay payment initialized successfully',
                    'data' => [
                        'gateway_url' => $result['payment_url'] ?? $result['redirect_url'] ?? null,
                        'session_id' => $result['session_id'] ?? null,
                    ],
                ]);
            }

            return $this->formatResponse(false, [
                'transaction_id' => $payload['tran_id'],
                'status' => 'failed',
                'message' => $result['message'] ?? $result['reason'] ?? 'Failed to initialize AmarPay payment',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            $this->logTransaction('init_error', '/payment/init', $data, ['error' => $e->getMessage()]);
            return $this->formatResponse(false, [
                'message' => 'AmarPay initialization error: ' . $e->getMessage(),
            ]);
        }
    }

    public function verify($paymentId): array
    {
        try {
            $response = $this->http()->asForm()->post($this->getBaseUrl() . '/payment/status', [
                'store_id' => $this->config['store_id'],
                'signature_key' => $this->config['signature_key'],
                'tran_id' => $paymentId,
            ]);

            $result = $response->json();

            $this->logTransaction('verify', '/payment/status', ['tran_id' => $paymentId], $result, $paymentId);

            if ($response->successful()) {
                $statusMap = [
                    'Completed' => 'completed',
                    'Pending' => 'pending',
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
            $this->logTransaction('verify_error', '/payment/status', ['tran_id' => $paymentId], ['error' => $e->getMessage()], $paymentId);
            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'message' => 'AmarPay verify error: ' . $e->getMessage(),
            ]);
        }
    }

    public function refund($paymentId, $amount): array
    {
        try {
            $response = $this->http()->asForm()->post($this->getBaseUrl() . '/payment/refund', [
                'store_id' => $this->config['store_id'],
                'signature_key' => $this->config['signature_key'],
                'tran_id' => $paymentId,
                'amount' => number_format((float) $amount, 2, '.', ''),
                'refund_remark' => 'Customer requested refund',
            ]);

            $result = $response->json();

            $this->logTransaction('refund', '/payment/refund', [
                'tran_id' => $paymentId,
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
            $this->logTransaction('refund_error', '/payment/refund', ['tran_id' => $paymentId, 'amount' => $amount], ['error' => $e->getMessage()], $paymentId);
            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'amount' => $amount,
                'message' => 'AmarPay refund error: ' . $e->getMessage(),
            ]);
        }
    }

    public function processWebhook($request): array
    {
        $data = $request->all();
        $this->logTransaction('webhook', 'webhook', $data, $data, $data['tran_id'] ?? null);

        $paymentId = $data['tran_id'] ?? null;
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
