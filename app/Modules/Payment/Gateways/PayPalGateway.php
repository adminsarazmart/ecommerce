<?php

namespace App\Modules\Payment\Gateways;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PayPalGateway extends BaseGateway
{
    protected string $gatewayName = 'paypal';

    protected function getAccessToken(): ?string
    {
        try {
            $response = Http::withBasicAuth($this->config['client_id'], $this->config['client_secret'])
                ->asForm()
                ->post($this->getBaseUrl() . '/v1/oauth2/token', [
                    'grant_type' => 'client_credentials',
                ]);

            if ($response->successful()) {
                return $response->json()['access_token'] ?? null;
            }

            Log::error('PayPal access token error', ['response' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('PayPal access token exception: ' . $e->getMessage());
            return null;
        }
    }

    protected function httpWithAuth(): \Illuminate\Http\Client\PendingRequest
    {
        $token = $this->getAccessToken();
        return $this->http()->withToken($token);
    }

    public function initialize(array $data): array
    {
        return $this->createOrder($data);
    }

    public function createOrder(array $data): array
    {
        try {
            $payload = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => $data['order_id'] ?? uniqid(),
                        'description' => $data['description'] ?? 'Order payment',
                        'amount' => [
                            'currency_code' => $data['currency'] ?? config('payment.default_currency', 'USD'),
                            'value' => number_format((float) $data['amount'], 2, '.', ''),
                        ],
                    ],
                ],
                'payment_source' => [
                    'paypal' => [
                        'experience_context' => [
                            'payment_method_preference' => 'IMMEDIATE_PAYMENT_REQUIRED',
                            'landing_page' => 'LOGIN',
                            'user_action' => 'PAY_NOW',
                            'return_url' => $data['success_url'] ?? url(config('payment.callback_urls.success')),
                            'cancel_url' => $data['cancel_url'] ?? url(config('payment.callback_urls.cancel')),
                        ],
                    ],
                ],
            ];

            $response = $this->httpWithAuth()->post($this->getBaseUrl() . '/v2/checkout/orders', $payload);

            $result = $response->json();

            $this->logTransaction('create_order', '/v2/checkout/orders', $payload, $result);

            if ($response->successful()) {
                return $this->formatResponse(true, [
                    'transaction_id' => $result['id'],
                    'amount' => $data['amount'],
                    'currency' => $payload['purchase_units'][0]['amount']['currency_code'],
                    'status' => 'pending',
                    'message' => 'PayPal order created successfully',
                    'data' => [
                        'order_id' => $result['id'],
                        'status' => $result['status'],
                        'approval_url' => $result['links'][1]['href'] ?? null,
                    ],
                ]);
            }

            return $this->formatResponse(false, [
                'status' => 'failed',
                'message' => $result['message'] ?? 'Failed to create PayPal order',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            $this->logTransaction('create_order_error', '/v2/checkout/orders', $data, ['error' => $e->getMessage()]);
            return $this->formatResponse(false, [
                'message' => 'PayPal create order error: ' . $e->getMessage(),
            ]);
        }
    }

    public function captureOrder(string $orderId): array
    {
        try {
            $response = $this->httpWithAuth()->post($this->getBaseUrl() . "/v2/checkout/orders/{$orderId}/capture");

            $result = $response->json();

            $this->logTransaction('capture_order', "/v2/checkout/orders/{$orderId}/capture", [], $result, $orderId);

            if ($response->successful() && $result['status'] === 'COMPLETED') {
                $capture = $result['purchase_units'][0]['payments']['captures'][0] ?? [];

                return $this->formatResponse(true, [
                    'transaction_id' => $capture['id'] ?? $orderId,
                    'amount' => $capture['amount']['value'] ?? null,
                    'currency' => $capture['amount']['currency_code'] ?? 'USD',
                    'status' => 'completed',
                    'message' => 'Payment captured successfully',
                    'data' => [
                        'order_id' => $orderId,
                        'capture_id' => $capture['id'] ?? null,
                        'status' => $result['status'],
                        'seller_receivable' => $capture['seller_receivable_breakdown'] ?? [],
                    ],
                ]);
            }

            return $this->formatResponse(false, [
                'transaction_id' => $orderId,
                'status' => strtolower($result['status'] ?? 'failed'),
                'message' => $result['message'] ?? 'Failed to capture payment',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            $this->logTransaction('capture_order_error', "/v2/checkout/orders/{$orderId}/capture", [], ['error' => $e->getMessage()], $orderId);
            return $this->formatResponse(false, [
                'transaction_id' => $orderId,
                'message' => 'PayPal capture error: ' . $e->getMessage(),
            ]);
        }
    }

    public function verify($paymentId): array
    {
        try {
            $response = $this->httpWithAuth()->get($this->getBaseUrl() . "/v2/checkout/orders/{$paymentId}");

            $result = $response->json();

            $this->logTransaction('verify_order', "/v2/checkout/orders/{$paymentId}", [], $result, $paymentId);

            if ($response->successful()) {
                $isCompleted = $result['status'] === 'COMPLETED';

                return $this->formatResponse($isCompleted, [
                    'transaction_id' => $paymentId,
                    'amount' => $result['purchase_units'][0]['amount']['value'] ?? null,
                    'currency' => $result['purchase_units'][0]['amount']['currency_code'] ?? 'USD',
                    'status' => strtolower($result['status']),
                    'message' => $isCompleted ? 'Payment verified successfully' : "Order status: {$result['status']}",
                    'data' => $result,
                ]);
            }

            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'status' => 'not_found',
                'message' => $result['message'] ?? 'Order not found',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            $this->logTransaction('verify_error', "/v2/checkout/orders/{$paymentId}", [], ['error' => $e->getMessage()], $paymentId);
            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'message' => 'PayPal verify error: ' . $e->getMessage(),
            ]);
        }
    }

    public function refund($paymentId, $amount): array
    {
        try {
            $payload = [
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => number_format((float) $amount, 2, '.', ''),
                ],
            ];

            $response = $this->httpWithAuth()->post($this->getBaseUrl() . "/v2/payments/captures/{$paymentId}/refund", $payload);

            $result = $response->json();

            $this->logTransaction('refund', "/v2/payments/captures/{$paymentId}/refund", $payload, $result, $paymentId);

            if ($response->successful() && $result['status'] === 'COMPLETED') {
                return $this->formatResponse(true, [
                    'transaction_id' => $paymentId,
                    'amount' => $amount,
                    'currency' => $result['seller_payable_breakdown']['gross_amount']['currency_code'] ?? 'USD',
                    'status' => 'refunded',
                    'message' => 'Refund processed successfully',
                    'data' => [
                        'refund_id' => $result['id'],
                        'refund_status' => $result['status'],
                    ],
                ]);
            }

            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'amount' => $amount,
                'status' => 'refund_failed',
                'message' => $result['message'] ?? 'Refund processing failed',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            $this->logTransaction('refund_error', "/v2/payments/captures/{$paymentId}/refund", ['amount' => $amount], ['error' => $e->getMessage()], $paymentId);
            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'amount' => $amount,
                'message' => 'PayPal refund error: ' . $e->getMessage(),
            ]);
        }
    }

    public function processWebhook($request): array
    {
        $payload = $request->getContent();
        $headers = $request->headers->all();

        try {
            $verificationPayload = [
                'auth_algo' => $request->header('PAYPAL-AUTH-ALGO'),
                'cert_url' => $request->header('PAYPAL-CERT-URL'),
                'transmission_id' => $request->header('PAYPAL-TRANSMISSION-ID'),
                'transmission_sig' => $request->header('PAYPAL-TRANSMISSION-SIG'),
                'transmission_time' => $request->header('PAYPAL-TRANSMISSION-TIME'),
                'webhook_id' => $this->config['webhook_id'],
                'webhook_event' => json_decode($payload, true),
            ];

            $response = $this->httpWithAuth()
                ->post($this->getBaseUrl() . '/v1/notifications/verify-webhook-signature', $verificationPayload);

            $verificationResult = $response->json();

            if (($verificationResult['verification_status'] ?? '') !== 'SUCCESS') {
                return $this->formatResponse(false, [
                    'status' => 'invalid',
                    'message' => 'Webhook signature verification failed',
                ]);
            }

            $event = json_decode($payload, true);
            $this->logTransaction('webhook', 'webhook', $event, $event);

            switch ($event['event_type'] ?? '') {
                case 'CHECKOUT.ORDER.APPROVED':
                    return $this->formatResponse(true, [
                        'transaction_id' => $event['resource']['id'] ?? null,
                        'status' => 'approved',
                        'message' => 'Order approved, ready for capture',
                        'data' => $event,
                    ]);

                case 'PAYMENT.CAPTURE.COMPLETED':
                    return $this->formatResponse(true, [
                        'transaction_id' => $event['resource']['id'] ?? null,
                        'status' => 'completed',
                        'message' => 'Payment completed',
                        'data' => $event,
                    ]);

                case 'PAYMENT.CAPTURE.DENIED':
                    return $this->formatResponse(false, [
                        'transaction_id' => $event['resource']['id'] ?? null,
                        'status' => 'denied',
                        'message' => 'Payment denied',
                        'data' => $event,
                    ]);

                case 'PAYMENT.CAPTURE.REFUNDED':
                    return $this->formatResponse(true, [
                        'transaction_id' => $event['resource']['id'] ?? null,
                        'status' => 'refunded',
                        'message' => 'Payment refunded',
                        'data' => $event,
                    ]);

                default:
                    return $this->formatResponse(true, [
                        'transaction_id' => $event['resource']['id'] ?? null,
                        'status' => 'unknown',
                        'message' => "Unhandled event type: {$event['event_type']}",
                        'data' => $event,
                    ]);
            }
        } catch (\Exception $e) {
            $this->logTransaction('webhook_error', 'webhook', ['error' => $e->getMessage()], []);
            return $this->formatResponse(false, [
                'message' => 'Webhook processing error: ' . $e->getMessage(),
            ]);
        }
    }

    public function getStatus($paymentId): array
    {
        return $this->verify($paymentId);
    }
}
