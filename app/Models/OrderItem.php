<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'variation_id',
        'product_name',
        'variant_details',
        'quantity',
        'price',
        'point_price',
        'subtotal',
        'point_subtotal',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'point_price' => 'integer',
        'subtotal' => 'decimal:2',
        'point_subtotal' => 'integer',
    ];

    /**
     * Get the order that owns the order item.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the variation for the order item.
     */
    public function variation()
    {
        return $this->belongsTo(Variation::class);
    }

    /**
     * Get all of the point transactions for the order item.
     */
    public function pointTransactions()
    {
        return $this->morphMany(PointTransaction::class, 'transactionable');
    }

    /**
     * Calculate subtotal based on price and quantity.
     */
    public function calculateSubtotal()
    {
        $this->subtotal = $this->price * $this->quantity;
        $this->point_subtotal = $this->point_price * $this->quantity;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($orderItem) {
            if ($orderItem->subtotal == 0 && $orderItem->price > 0) {
                $orderItem->calculateSubtotal();
            }
        });
    }
}
