<?php

namespace App\Modules\Payment\Gateways;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StripeGateway extends BaseGateway
{
    protected string $gatewayName = 'stripe';

    public function initialize(array $data): array
    {
        return $this->createPaymentIntent($data);
    }

    public function createPaymentIntent(array $data): array
    {
        try {
            $amountInCents = (int) (round((float) $data['amount'], 2) * 100);

            $payload = [
                'amount' => $amountInCents,
                'currency' => strtolower($data['currency'] ?? config('payment.default_currency', 'USD')),
                'payment_method_types' => ['card'],
                'description' => $data['description'] ?? 'Order payment',
                'metadata' => [
                    'order_id' => $data['order_id'] ?? null,
                    'customer_id' => $data['customer_id'] ?? null,
                ],
            ];

            if (!empty($data['customer_email'])) {
                $payload['receipt_email'] = $data['customer_email'];
            }

            $response = $this->http()
                ->withBasicAuth($this->config['secret_key'], '')
                ->post($this->getBaseUrl() . '/payment_intents', $payload);

            $result = $response->json();

            $this->logTransaction('create_payment_intent', '/payment_intents', $payload, $result);

            if ($response->successful()) {
                return $this->formatResponse(true, [
                    'transaction_id' => $result['id'],
                    'amount' => $data['amount'],
                    'currency' => strtoupper($payload['currency']),
                    'status' => 'pending',
                    'message' => 'Payment intent created successfully',
                    'data' => [
                        'client_secret' => $result['client_secret'],
                        'intent_id' => $result['id'],
                        'status' => $result['status'],
                    ],
                ]);
            }

            return $this->formatResponse(false, [
                'status' => 'failed',
                'message' => $result['error']['message'] ?? 'Failed to create payment intent',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            $this->logTransaction('create_payment_intent_error', '/payment_intents', $data, ['error' => $e->getMessage()]);
            return $this->formatResponse(false, [
                'message' => 'Stripe error: ' . $e->getMessage(),
            ]);
        }
    }

    public function confirmPayment(string $paymentIntentId, array $data = []): array
    {
        try {
            $response = $this->http()
                ->withBasicAuth($this->config['secret_key'], '')
                ->post($this->getBaseUrl() . "/payment_intents/{$paymentIntentId}/confirm", $data);

            $result = $response->json();

            $this->logTransaction('confirm_payment_intent', "/payment_intents/{$paymentIntentId}/confirm", $data, $result, $paymentIntentId);

            if ($response->successful() && $result['status'] === 'succeeded') {
                return $this->formatResponse(true, [
                    'transaction_id' => $paymentIntentId,
                    'amount' => ($result['amount_received'] ?? 0) / 100,
                    'currency' => strtoupper($result['currency'] ?? 'usd'),
                    'status' => 'completed',
                    'message' => 'Payment confirmed successfully',
                    'data' => $result,
                ]);
            }

            return $this->formatResponse(false, [
                'transaction_id' => $paymentIntentId,
                'status' => $result['status'] ?? 'failed',
                'message' => $result['error']['message'] ?? 'Payment confirmation failed',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            $this->logTransaction('confirm_payment_error', "/payment_intents/{$paymentIntentId}/confirm", $data, ['error' => $e->getMessage()], $paymentIntentId);
            return $this->formatResponse(false, [
                'transaction_id' => $paymentIntentId,
                'message' => 'Stripe confirmation error: ' . $e->getMessage(),
            ]);
        }
    }

    public function verify($paymentId): array
    {
        try {
            $response = $this->http()
                ->withBasicAuth($this->config['secret_key'], '')
                ->get($this->getBaseUrl() . "/payment_intents/{$paymentId}");

            $result = $response->json();

            $this->logTransaction('retrieve_payment_intent', "/payment_intents/{$paymentId}", [], $result, $paymentId);

            if ($response->successful()) {
                $statusMap = [
                    'succeeded' => 'completed',
                    'processing' => 'processing',
                    'requires_payment_method' => 'pending',
                    'requires_confirmation' => 'pending',
                    'canceled' => 'cancelled',
                ];

                return $this->formatResponse($result['status'] === 'succeeded', [
                    'transaction_id' => $paymentId,
                    'amount' => ($result['amount'] ?? 0) / 100,
                    'currency' => strtoupper($result['currency'] ?? 'usd'),
                    'status' => $statusMap[$result['status']] ?? $result['status'],
                    'message' => $result['status'] === 'succeeded' ? 'Payment completed' : "Payment status: {$result['status']}",
                    'data' => $result,
                ]);
            }

            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'status' => 'not_found',
                'message' => $result['error']['message'] ?? 'Payment intent not found',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            $this->logTransaction('retrieve_payment_error', "/payment_intents/{$paymentId}", [], ['error' => $e->getMessage()], $paymentId);
            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'message' => 'Stripe retrieve error: ' . $e->getMessage(),
            ]);
        }
    }

    public function refund($paymentId, $amount): array
    {
        try {
            $amountInCents = (int) (round((float) $amount, 2) * 100);

            $payload = [
                'payment_intent' => $paymentId,
                'amount' => $amountInCents,
            ];

            $response = $this->http()
                ->withBasicAuth($this->config['secret_key'], '')
                ->post($this->getBaseUrl() . '/refunds', $payload);

            $result = $response->json();

            $this->logTransaction('refund', '/refunds', $payload, $result, $paymentId);

            if ($response->successful() && $result['status'] === 'succeeded') {
                return $this->formatResponse(true, [
                    'transaction_id' => $paymentId,
                    'amount' => $amount,
                    'currency' => strtoupper($result['currency'] ?? 'usd'),
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
                'message' => $result['error']['message'] ?? 'Refund processing failed',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            $this->logTransaction('refund_error', '/refunds', ['payment_intent' => $paymentId, 'amount' => $amount], ['error' => $e->getMessage()], $paymentId);
            return $this->formatResponse(false, [
                'transaction_id' => $paymentId,
                'amount' => $amount,
                'message' => 'Stripe refund error: ' . $e->getMessage(),
            ]);
        }
    }

    public function processWebhook($request): array
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $this->config['webhook_secret']
            );

            $this->logTransaction('webhook', 'webhook', ['type' => $event->type], $event->toArray());

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    return $this->formatResponse(true, [
                        'transaction_id' => $event->data->object->id,
                        'status' => 'completed',
                        'message' => 'Payment succeeded via webhook',
                        'data' => $event->toArray(),
                    ]);

                case 'payment_intent.payment_failed':
                    return $this->formatResponse(false, [
                        'transaction_id' => $event->data->object->id,
                        'status' => 'failed',
                        'message' => 'Payment failed',
                        'data' => $event->toArray(),
                    ]);

                default:
                    return $this->formatResponse(true, [
                        'transaction_id' => $event->data->object->id ?? null,
                        'status' => 'unknown',
                        'message' => "Unhandled event type: {$event->type}",
                        'data' => $event->toArray(),
                    ]);
            }
        } catch (\Exception $e) {
            $this->logTransaction('webhook_error', 'webhook', ['error' => $e->getMessage()], []);
            return $this->formatResponse(false, [
                'message' => 'Webhook verification failed: ' . $e->getMessage(),
            ]);
        }
    }

    public function getStatus($paymentId): array
    {
        return $this->verify($paymentId);
    }
}
