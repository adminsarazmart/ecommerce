<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'total_orders', 'total_spent', 'loyalty_points',
        'wallet_balance', 'membership_level', 'referred_by',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function referredBy() { return $this->belongsTo(Customer::class, 'referred_by'); }
    public function addresses() { return $this->hasMany(Address::class); }
    public function wishlistItems() { return $this->hasMany(Wishlist::class); }
    public function compareItems() { return $this->hasMany(ProductCompare::class); }
    public function reviews() { return $this->hasMany(Review::class); }
    public function cartItems() { return $this->hasMany(Cart::class); }
    public function orders() { return $this->hasMany(Order::class); }
    public function loyaltyPoints() { return $this->hasMany(LoyaltyPoint::class); }
    public function walletTransactions() { return $this->hasMany(CustomerWalletTransaction::class); }
    public function cashbackTransactions() { return $this->hasMany(CashbackTransaction::class); }
}
