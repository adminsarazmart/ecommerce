<?php

namespace App\Modules\Payment\Gateways;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NagadGateway extends BaseGateway
{
    protected string $gatewayName = 'nagad';

    protected function generateSensitiveData(array $data): string
    {
        $sensitive = json_encode([
            'merchantId' => $this->config['merchant_id'],
            'invoiceNo' => $data['invoice_no'] ?? $this->generateTransactionId(),
            'amount' => number_format((float) $data['amount'], 2, '.', ''),
            'currencyCode' => '050',
            'merchantCallbackURL' => $data['callback_url'] ?? url(config('payment.callback_urls.success')),
        ]);

        $publicKey = $this->config['public_key'];
        $encrypted = '';

        if (function_exists('openssl_encrypt')) {
            $key = substr(hash('sha256', $this->config['private_key'], true), 0, 16);
            $iv = substr($key, 0, 16);
            $encrypted = base64_encode(openssl_encrypt($sensitive, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv));
        }

        return $encrypted;
    }

    protected function generateSignature(string $data): string
    {
        return hash('sha256', $data . $this->config['private_key']);
    }

    public function initialize(array $data): array
    {
        return $this->createPayment($data);
    }

    public function createPayment(array $data): array
    {
        try {
            $merchantId = $this->config['merchant_id'];
            $invoiceNo = $data['invoice_no'] ?? $this->generateTransactionId();
            $sensitiveData = $this->generateSensitiveData($data);
            $signature = $this->generateSignature($sensitiveData);

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-Merchant-Id' => $merchantId,
            ])->post($this->getBaseUrl() . '/call/call', [
                'merchantId' => $merchantId,
                'invoiceNo' => $invoiceNo,
                'amount' => number_format((float) $data['amount'], 2, '.', ''),
                'currencyCode' => '050',
                'merchantCallbackURL' => $data['callback_url'] ?? url(config('payment.callback_urls.success')),
                'sensitiveData' => $sensitiveData,
                'signature' => $signature,
            ]);

            $result = $response->json();

            $this->logTransaction('create_payment', '/call/call', [
                'merchantId' => $merchantId,
                'invoiceNo' => $invoiceNo,
            ], $result, $invoiceNo);

            if ($response->successful() && !empty($result['sensitiveData'])) {
                return $this->formatResponse(true, [
                    'transaction_id' => $result['paymentReferenceId'] ?? $invoiceNo,
                    'amount' => $data['amount'],
                    'currency' => 'BDT',
                    'status' => 'pending',
                    'message' => 'Nagad payment created successfully',
                    'data' => [
                        'payment_reference_id' => $result['paymentReferenceId'] ?? null,
                        'issuer_payment_url' => $result['issuerPaymentURL'] ?? null,
                    ],
                ]);
            }

            return $this->formatResponse(false, [
                'status' => 'failed',
                'message' => $result['message'] ?? $result['reason'] ?? 'Failed to create Nagad payment',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            $this->logTransaction('create_payment_error', '/call/call', $data, ['error' => $e->getMessage()]);
            return $this->formatResponse(false, [
                'message' => 'Nagad create payment error: ' . $e->getMessage(),
            ]);
        }
    }

    public function verify($paymentId): array
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-Merchant-Id' => $this->config['merchant_id'],
            ])->get($this->getBaseUrl() . "/call/status/{$paymentId}");

            $result = $response->json();

            $this->logTransaction('verify_payment', "/call/status/{$paymentId}", [], $result, $paymentId);

            if ($response->successful()) {
                $statusMap = [
                    'Completed' => 'completed',
                    'Pending' => 'pending',
                    'Processing' => 'processing',
                    'Failed' => 'failed',
                    'Cancelled' => 'cancelled',
                    'Refunded' => 'refunded',
                    'Partial_Refunded' => 'partially_refunded',
                ];

                $paymentStatus = $result['paymentStatus'] ?? $result['status'] ?? '';
                $isCompleted = $paymentStatus === 'Completed';

                return $this->formatResponse($isCompleted, [
                    'transaction_id' => $paymentId,
                    'amount' => $result['amount'] ?? null,
                    'currency' => 'BDT',
                    'status' => $statusMap[$paymentStatus] ?? strtolower($paymentStatus),
                    'message' => $isCompleted ? 'Payment verified successfully' : "Payment status: {$paymentStatus}",
                    'data' => $result,
                ]);
            }

            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'status' => 'not_found',
                'message' => $result['message'] ?? 'Payment not found',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            $this->logTransaction('verify_error', "/call/status/{$paymentId}", [], ['error' => $e->getMessage()], $paymentId);
            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'message' => 'Nagad verify error: ' . $e->getMessage(),
            ]);
        }
    }

    public function refund($paymentId, $amount): array
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-Merchant-Id' => $this->config['merchant_id'],
            ])->post($this->getBaseUrl() . '/call/refund', [
                'merchantId' => $this->config['merchant_id'],
                'paymentReferenceId' => $paymentId,
                'amount' => number_format((float) $amount, 2, '.', ''),
                'refundReferenceNo' => $this->generateTransactionId(),
                'charge' => 0,
            ]);

            $result = $response->json();

            $this->logTransaction('refund', '/call/refund', [
                'paymentReferenceId' => $paymentId,
                'amount' => $amount,
            ], $result, $paymentId);

            if ($response->successful() && ($result['status'] ?? '') === 'Completed') {
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
                'message' => $result['message'] ?? $result['reason'] ?? 'Refund failed',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            $this->logTransaction('refund_error', '/call/refund', ['paymentReferenceId' => $paymentId, 'amount' => $amount], ['error' => $e->getMessage()], $paymentId);
            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'amount' => $amount,
                'message' => 'Nagad refund error: ' . $e->getMessage(),
            ]);
        }
    }

    public function processWebhook($request): array
    {
        $data = $request->all();
        $this->logTransaction('webhook', 'webhook', $data, $data, $data['paymentReferenceId'] ?? null);

        $paymentId = $data['paymentReferenceId'] ?? null;
        if (!$paymentId) {
            return $this->formatResponse(false, [
                'message' => 'Invalid webhook: missing payment reference ID',
            ]);
        }

        return $this->verify($paymentId);
    }

    public function getStatus($paymentId): array
    {
        return $this->verify($paymentId);
    }
}
