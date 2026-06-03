<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Order extends BaseModel implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'order_number', 'customer_id', 'vendor_id', 'coupon_id', 'coupon_discount',
        'subtotal', 'shipping_cost', 'tax_amount', 'discount', 'grand_total',
        'paid_amount', 'due_amount', 'currency', 'exchange_rate', 'status',
        'payment_status', 'shipping_status', 'payment_method', 'shipping_method',
        'shipping_address', 'billing_address', 'notes', 'is_partial',
        'partial_payments', 'is_seen', 'placed_at', 'confirmed_at', 'processing_at',
        'shipped_at', 'delivered_at', 'cancelled_at', 'refunded_at',
    ];

    protected function casts(): array
    {
        return [
            'shipping_address' => 'json', 'billing_address' => 'json',
            'partial_payments' => 'json', 'is_partial' => 'boolean', 'is_seen' => 'boolean',
            'placed_at' => 'datetime', 'confirmed_at' => 'datetime',
            'processing_at' => 'datetime', 'shipped_at' => 'datetime',
            'delivered_at' => 'datetime', 'cancelled_at' => 'datetime', 'refunded_at' => 'datetime',
        ];
    }

    public function customer() { return $this->belongsTo(Customer::class); }
    public function vendor() { return $this->belongsTo(Vendor::class); }
    public function coupon() { return $this->belongsTo(Coupon::class); }
    public function items() { return $this->hasMany(OrderItem::class); }
    public function statusHistories() { return $this->hasMany(OrderStatusHistory::class); }
    public function shipments() { return $this->hasMany(Shipment::class); }
    public function refunds() { return $this->hasMany(Refund::class); }
    public function returns() { return $this->hasMany(ReturnItem::class); }
}
