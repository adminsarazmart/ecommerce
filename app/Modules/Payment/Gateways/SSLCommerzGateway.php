<?php

namespace App\Modules\Payment\Gateways;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SSLCommerzGateway extends BaseGateway
{
    protected string $gatewayName = 'sslcommerz';

    public function initialize(array $data): array
    {
        try {
            $postData = [
                'store_id' => $this->config['store_id'],
                'store_passwd' => $this->config['store_password'],
                'total_amount' => $data['amount'],
                'currency' => $data['currency'] ?? config('payment.default_currency', 'BDT'),
                'tran_id' => $data['transaction_id'] ?? $this->generateTransactionId(),
                'success_url' => $data['success_url'] ?? config('payment.callback_urls.success'),
                'fail_url' => $data['fail_url'] ?? config('payment.callback_urls.fail'),
                'cancel_url' => $data['cancel_url'] ?? config('payment.callback_urls.cancel'),
                'ipn_url' => $data['ipn_url'] ?? config('payment.callback_urls.ipn'),
                'cus_name' => $data['customer_name'] ?? '',
                'cus_email' => $data['customer_email'] ?? '',
                'cus_phone' => $data['customer_phone'] ?? '',
                'cus_add1' => $data['customer_address'] ?? '',
                'cus_city' => $data['customer_city'] ?? '',
                'cus_country' => $data['customer_country'] ?? 'Bangladesh',
                'shipping_method' => 'NO',
                'product_name' => $data['product_name'] ?? 'Order Payment',
                'product_category' => $data['product_category'] ?? 'General',
                'product_profile' => 'general',
            ];

            $response = $this->http()->asForm()->post($this->getBaseUrl() . '/gwprocess/v4/api.php', $postData);

            $result = $response->json();

            $this->logTransaction('init', '/gwprocess/v4/api.php', $postData, $result, $postData['tran_id']);

            if ($response->successful() && ($result['status'] ?? '') === 'SUCCESS') {
                return $this->formatResponse(true, [
                    'transaction_id' => $postData['tran_id'],
                    'amount' => $data['amount'],
                    'currency' => $postData['currency'],
                    'status' => 'pending',
                    'message' => 'Payment session created successfully',
                    'data' => [
                        'gateway_url' => $result['GatewayPageURL'] ?? null,
                        'session_key' => $result['sessionkey'] ?? null,
                    ],
                ]);
            }

            return $this->formatResponse(false, [
                'transaction_id' => $postData['tran_id'],
                'amount' => $data['amount'],
                'status' => 'failed',
                'message' => $result['failedreason'] ?? 'Failed to initialize payment',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            $this->logTransaction('init_error', '/gwprocess/v4/api.php', $data, ['error' => $e->getMessage()]);
            return $this->formatResponse(false, [
                'message' => 'Payment initialization error: ' . $e->getMessage(),
                'data' => ['error' => $e->getMessage()],
            ]);
        }
    }

    public function verify($paymentId): array
    {
        try {
            $response = Http::asForm()->post($this->getBaseUrl() . '/gwprocess/v4/validation/api.php', [
                'store_id' => $this->config['store_id'],
                'store_passwd' => $this->config['store_password'],
                'tran_id' => $paymentId,
            ]);

            $result = $response->json();

            $this->logTransaction('verify', '/gwprocess/v4/validation/api.php', ['tran_id' => $paymentId], $result, $paymentId);

            if ($response->successful() && ($result['status'] ?? '') === 'VALID') {
                return $this->formatResponse(true, [
                    'transaction_id' => $paymentId,
                    'amount' => $result['amount'] ?? null,
                    'currency' => $result['currency'] ?? 'BDT',
                    'status' => 'completed',
                    'message' => 'Payment verified successfully',
                    'data' => $result,
                ]);
            }

            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'status' => 'invalid',
                'message' => $result['status'] ?? 'Payment verification failed',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            $this->logTransaction('verify_error', '/gwprocess/v4/validation/api.php', ['tran_id' => $paymentId], ['error' => $e->getMessage()], $paymentId);
            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'message' => 'Verification error: ' . $e->getMessage(),
            ]);
        }
    }

    public function refund($paymentId, $amount): array
    {
        try {
            $response = Http::asForm()->post($this->getBaseUrl() . '/gwprocess/v4/refund/api.php', [
                'store_id' => $this->config['store_id'],
                'store_passwd' => $this->config['store_password'],
                'refund_amount' => $amount,
                'refund_ref_id' => $this->generateTransactionId(),
                'tran_id' => $paymentId,
            ]);

            $result = $response->json();

            $this->logTransaction('refund', '/gwprocess/v4/refund/api.php', [
                'tran_id' => $paymentId,
                'refund_amount' => $amount,
            ], $result, $paymentId);

            if ($response->successful() && ($result['status'] ?? '') === 'SUCCESS') {
                return $this->formatResponse(true, [
                    'transaction_id' => $paymentId,
                    'amount' => $amount,
                    'status' => 'refunded',
                    'message' => 'Refund initiated successfully',
                    'data' => $result,
                ]);
            }

            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'amount' => $amount,
                'status' => 'refund_failed',
                'message' => $result['failedreason'] ?? 'Refund initiation failed',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            $this->logTransaction('refund_error', '/gwprocess/v4/refund/api.php', ['tran_id' => $paymentId, 'amount' => $amount], ['error' => $e->getMessage()], $paymentId);
            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'amount' => $amount,
                'message' => 'Refund error: ' . $e->getMessage(),
            ]);
        }
    }

    public function processWebhook($request): array
    {
        return $this->validateIpn($request);
    }

    public function getStatus($paymentId): array
    {
        return $this->verify($paymentId);
    }

    public function processSuccess(Request $request): array
    {
        $data = $request->all();
        $this->logTransaction('success_callback', 'callback/success', $data, $data, $data['tran_id'] ?? null);

        if (($data['status'] ?? '') === 'VALID') {
            return $this->formatResponse(true, [
                'transaction_id' => $data['tran_id'] ?? null,
                'amount' => $data['amount'] ?? null,
                'currency' => $data['currency'] ?? 'BDT',
                'status' => 'completed',
                'message' => 'Payment completed successfully',
                'data' => $data,
            ]);
        }

        return $this->formatResponse(false, [
            'transaction_id' => $data['tran_id'] ?? null,
            'status' => 'failed',
            'message' => 'Payment validation failed',
            'data' => $data,
        ]);
    }

    public function processFailure(Request $request): array
    {
        $data = $request->all();
        $this->logTransaction('failure_callback', 'callback/fail', $data, $data, $data['tran_id'] ?? null);

        return $this->formatResponse(false, [
            'transaction_id' => $data['tran_id'] ?? null,
            'status' => 'failed',
            'message' => $data['failedreason'] ?? 'Payment was not successful',
            'data' => $data,
        ]);
    }

    public function processCancel(Request $request): array
    {
        $data = $request->all();
        $this->logTransaction('cancel_callback', 'callback/cancel', $data, $data, $data['tran_id'] ?? null);

        return $this->formatResponse(false, [
            'transaction_id' => $data['tran_id'] ?? null,
            'status' => 'cancelled',
            'message' => 'Payment was cancelled by user',
            'data' => $data,
        ]);
    }

    public function validateIpn(Request $request): array
    {
        $data = $request->all();
        $this->logTransaction('ipn', 'callback/ipn', $data, $data, $data['tran_id'] ?? null);

        if (empty($data['tran_id'])) {
            return $this->formatResponse(false, [
                'status' => 'invalid',
                'message' => 'Invalid IPN request: missing transaction ID',
            ]);
        }

        $verificationData = [
            'store_id' => $this->config['store_id'],
            'store_passwd' => $this->config['store_password'],
            'tran_id' => $data['tran_id'],
        ];

        $verificationData['verified_by'] = 'IPN';

        if (isset($data['val_id'])) {
            $verificationData['val_id'] = $data['val_id'];
        }

        return $this->verify($data['tran_id']);
    }
}
