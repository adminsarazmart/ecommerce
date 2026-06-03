<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name', 'company_name', 'email', 'phone', 'address', 'city', 'state',
        'zip', 'country', 'tax_id', 'registration_number', 'notes', 'status',
    ];

    public function purchaseOrders() { return $this->hasMany(PurchaseOrder::class); }
}
