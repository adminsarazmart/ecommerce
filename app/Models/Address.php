<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'label', 'first_name', 'last_name', 'phone',
        'address_line1', 'address_line2', 'city', 'state', 'zip', 'country',
        'is_default', 'is_billing', 'is_shipping',
    ];

    protected function casts(): array
    {
        return ['is_default' => 'boolean', 'is_billing' => 'boolean', 'is_shipping' => 'boolean'];
    }

    public function customer() { return $this->belongsTo(Customer::class); }
}
