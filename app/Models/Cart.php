<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'carts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'product_variant_id',
        'quantity',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * Get the user that owns the cart item.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product variant for the cart item.
     */
    public function variations()
    {
        return $this->belongsTo(Variation::class);
    }

    /**
     * Get the total price for this cart item.
     * 
     * @return float
     */
    public function getTotalPriceAttribute()
    {
        if ($this->payment_type === 'cash') {
            return $this->quantity * $this->productVariant->price;
        } else {
            return $this->quantity * $this->productVariant->points_price;
        }
    }
}
