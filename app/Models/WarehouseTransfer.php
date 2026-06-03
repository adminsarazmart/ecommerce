<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class WarehouseTransfer extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'from_warehouse_id', 'to_warehouse_id', 'reference_number', 'status',
        'notes', 'created_by', 'approved_by', 'completed_at',
    ];

    protected function casts(): array
    {
        return ['completed_at' => 'datetime'];
    }

    public function fromWarehouse() { return $this->belongsTo(Warehouse::class, 'from_warehouse_id'); }
    public function toWarehouse() { return $this->belongsTo(Warehouse::class, 'to_warehouse_id'); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
    public function approvedBy() { return $this->belongsTo(User::class, 'approved_by'); }
    public function items() { return $this->hasMany(TransferItem::class, 'transfer_id'); }
}
