<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile() { return $this->hasOne(UserProfile::class); }
    public function vendor() { return $this->hasOne(Vendor::class); }
    public function reseller() { return $this->hasOne(Reseller::class); }
    public function customer() { return $this->hasOne(Customer::class); }
    public function employee() { return $this->hasOne(Employee::class); }
    public function shareholder() { return $this->hasOne(Shareholder::class); }
    public function addresses() { return $this->hasMany(Address::class); }
    public function loginHistories() { return $this->hasMany(LoginHistory::class); }
    public function devices() { return $this->hasMany(DeviceToken::class); }
}
