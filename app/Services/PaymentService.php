<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentService
{
    public function processPayment(Order $order, array $paymentData): Payment
    {
        DB::beginTransaction();
        try {
            $payment = Payment::create([
                'order_id' => $order->id,
                'transaction_id' => 'TXN-' . strtoupper(Str::random(16)),
                'amount' => $order->total,
                'payment_method' => $paymentData['method'] ?? 'stripe',
                'status' => 'pending',
                'gateway_response' => null,
            ]);

            DB::commit();
            return $payment;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function verifyPayment(string $transactionId): Payment
    {
        $payment = Payment::where('transaction_id', $transactionId)->firstOrFail();
        $payment->update(['status' => 'completed']);
        return $payment;
    }

    public function refundPayment(int $paymentId, ?float $amount = null): Payment
    {
        $payment = Payment::findOrFail($paymentId);
        $refundAmount = $amount ?? $payment->amount;

        DB::beginTransaction();
        try {
            $payment->update([
                'status' => 'refunded',
                'refunded_amount' => $refundAmount,
                'refunded_at' => now(),
            ]);
            DB::commit();
            return $payment->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getPaymentGateways(): array
    {
        return [
            'stripe' => 'Stripe',
            'paypal' => 'PayPal',
            'razorpay' => 'Razorpay',
            'paystack' => 'Paystack',
            'flutterwave' => 'Flutterwave',
            'sslcommerz' => 'SSLCommerz',
            'bkash' => 'bKash',
            'nagad' => 'Nagad',
            'cod' => 'Cash on Delivery',
            'bank_transfer' => 'Bank Transfer',
        ];
    }
}
