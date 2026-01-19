<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
// Spatie:
use Spatie\Permission\Traits\HasRoles;
// Import WAJIB untuk relasi ✅
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable implements CanResetPasswordContract
{
    use HasApiTokens, Notifiable, HasRoles, CanResetPassword;

    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'phone_number',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the user's points.
     */
    public function userPoint(): HasOne
    {
        return $this->hasOne(UserPoint::class);
    }

    /**
     * Get the user's cart items.
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get the user's orders.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get all user addresses.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class);
    }
    
    /**
     * Get only active addresses (is_active = true).
     */
    public function activeAddresses(): HasMany
    {
        return $this->hasMany(UserAddress::class)->where('is_active', true);
    }
    
    /**
     * Get primary address (is_primary = true).
     */
    public function primaryAddress(): HasOne
    {
        return $this->hasOne(UserAddress::class)->where('is_primary', true);
    }
    
    /**
     * Get primary address or first available address.
     */
    public function getPrimaryAddressOrFirst()
    {
        $primary = $this->primaryAddress()->first();
        
        if ($primary) {
            return $primary;
        }
        
        return $this->activeAddresses()->first();
    }
    
    public function pushSubscriptions()
    {
        return $this->hasMany(PushSubscription::class);
    }

}
