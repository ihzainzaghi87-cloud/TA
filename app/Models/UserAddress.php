<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddress extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
    ];
    
    // Relasi ke User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    // Relasi ke Province
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id', 'province_id');
    }
    
    // Relasi ke City
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_address_id');
    }
    
    // Getter untuk alamat lengkap
    public function getFullAddressAttribute(): string
    {
        return "{$this->address}, {$this->city_name}, {$this->province_name} {$this->postal_code}";
    }
    
    // Set sebagai alamat utama
    public function setAsPrimary(): bool
    {
        // Set semua alamat user lain menjadi non-primary
        static::where('user_id', $this->user_id)
              ->where('id', '!=', $this->id)
              ->update(['is_primary' => false]);
        
        // Set alamat ini sebagai primary
        return $this->update(['is_primary' => true]);
    }
}
