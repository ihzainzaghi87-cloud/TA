<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan model ini.
     * (Sebenarnya opsional karena Laravel otomatis menebak dari nama model.)
     */
    protected $table = 'products';

    /**
     * Kolom yang boleh diisi (mass assignable).
     */
    protected $fillable = [
        'category_id',
        'slug',
        'name',
        'description',
        'weight',
        'price',
        'point_price',
        'is_active',
        'is_reward',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_reward' => 'boolean',
    ];

    /**
     * Relasi ke kategori (many-to-one).
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke gambar produk (one-to-many).
     * Akan berfungsi jika kamu punya tabel `product_images`.
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variations()
    {
        return $this->hasMany(Variation::class);
    }

    /**
     * Relasi ke order items untuk menghitung penjualan
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
