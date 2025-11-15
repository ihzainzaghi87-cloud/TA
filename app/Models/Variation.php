<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'color',
        'size',
        'stock',
    ];

    protected $casts = [
        'stock' => 'integer',
    ];

    /**
     * Get the product that owns the variation.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get all cart items for this product variant.
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
