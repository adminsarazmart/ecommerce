<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AffiliateLink extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['reseller_id', 'product_id', 'link', 'visits', 'orders', 'commissions'];

    public function reseller() { return $this->belongsTo(Reseller::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
