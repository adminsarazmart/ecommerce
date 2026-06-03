<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService extends BaseService
{
    protected OrderRepository $repository;

    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
        parent::__construct($repository);
    }

    public function createOrder(array $data): Order
    {
        DB::beginTransaction();
        try {
            $data['order_number'] = $data['order_number'] ?? 'ORD-' . strtoupper(Str::random(10));
            $data['status'] = $data['status'] ?? 'pending';

            $order = $this->repository->create($data);

            if (!empty($data['items'])) {
                foreach ($data['items'] as $item) {
                    $order->items()->create([
                        'product_id' => $item['product_id'],
                        'vendor_id' => $item['vendor_id'] ?? null,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'total_price' => $item['quantity'] * $item['unit_price'],
                    ]);
                }
            }

            $order = $this->calculateOrderTotals($order);
            DB::commit();
            return $order->load('items');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function processOrder(int $orderId): Order
    {
        $order = $this->repository->findOrFail($orderId);
        $order->update(['status' => 'processing']);
        return $order->fresh();
    }

    public function updateOrderStatus(int $orderId, string $status): Order
    {
        $order = $this->repository->findOrFail($orderId);
        $oldStatus = $order->status;
        $order->update(['status' => $status]);
        $order->statusHistory()->create([
            'from_status' => $oldStatus,
            'to_status' => $status,
            'changed_by' => auth()->id(),
        ]);
        return $order->fresh();
    }

    public function processRefund(int $orderId, array $data): Order
    {
        DB::beginTransaction();
        try {
            $order = $this->repository->findOrFail($orderId);
            $order->update(['status' => 'refunded']);
            $order->refund()->create([
                'amount' => $data['amount'] ?? $order->total,
                'reason' => $data['reason'] ?? null,
                'processed_by' => auth()->id(),
            ]);
            DB::commit();
            return $order->fresh(['refund', 'items']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function processReturn(int $orderId, array $data): Order
    {
        DB::beginTransaction();
        try {
            $order = $this->repository->findOrFail($orderId);
            $order->update(['status' => 'returned']);
            $order->return()->create([
                'reason' => $data['reason'],
                'status' => 'pending',
                'requested_by' => auth()->id(),
            ]);
            DB::commit();
            return $order->fresh(['return', 'items']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function calculateOrderTotals(Order $order): Order
    {
        $subtotal = $order->items->sum('total_price');
        $tax = $order->tax_amount ?? 0;
        $shipping = $order->shipping_amount ?? 0;
        $discount = $order->discount_amount ?? 0;

        $total = $subtotal + $tax + $shipping - $discount;

        $order->update([
            'subtotal' => $subtotal,
            'total' => max(0, $total),
        ]);

        return $order->fresh();
    }
}
