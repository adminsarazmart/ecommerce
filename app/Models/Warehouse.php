<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warehouse extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'code', 'address', 'city', 'state', 'zip', 'country',
        'contact_name', 'contact_email', 'contact_phone', 'is_active', 'is_default',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'is_default' => 'boolean'];
    }

    public function stocks() { return $this->hasMany(Stock::class); }
    public function stockMovements() { return $this->hasMany(StockMovement::class); }
    public function inboundTransfers() { return $this->hasMany(WarehouseTransfer::class, 'to_warehouse_id'); }
    public function outboundTransfers() { return $this->hasMany(WarehouseTransfer::class, 'from_warehouse_id'); }
    public function purchaseOrders() { return $this->hasMany(PurchaseOrder::class); }
}
