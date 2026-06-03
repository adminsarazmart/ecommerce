<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProcessPaymentRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function process(ProcessPaymentRequest $request)
    {
        try {
            $order = Order::where('customer_id', $request->user()->customer->id)
                ->where('id', $request->order_id)
                ->where('payment_status', 'pending')
                ->firstOrFail();

            $payment = $this->paymentService->processPayment($order, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Payment initiated',
                'data' => $payment,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function callback(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|string',
            'status' => 'required|in:completed,failed',
            'gateway_response' => 'nullable|json',
        ]);

        try {
            $payment = Payment::where('transaction_id', $validated['transaction_id'])->firstOrFail();

            if ($validated['status'] === 'completed') {
                $payment = $this->paymentService->verifyPayment($validated['transaction_id']);
                $payment->order->update(['payment_status' => 'paid']);
            } else {
                $payment->update([
                    'status' => 'failed',
                    'gateway_response' => $validated['gateway_response'],
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment callback processed',
                'data' => $payment->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function status($id)
    {
        try {
            $payment = Payment::with('order')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $payment,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found',
            ], 404);
        }
    }

    public function methods()
    {
        try {
            $methods = $this->paymentService->getPaymentGateways();

            return response()->json([
                'success' => true,
                'data' => $methods,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
