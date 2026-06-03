<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'supplier_id', 'warehouse_id', 'order_number', 'status', 'subtotal',
        'tax', 'shipping', 'grand_total', 'notes', 'ordered_at', 'expected_at',
        'received_at', 'created_by', 'approved_by',
    ];

    protected function casts(): array
    {
        return ['ordered_at' => 'datetime', 'expected_at' => 'datetime', 'received_at' => 'datetime'];
    }

    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function warehouse() { return $this->belongsTo(Warehouse::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
    public function approvedBy() { return $this->belongsTo(User::class, 'approved_by'); }
    public function items() { return $this->hasMany(PurchaseOrderItem::class); }
}
