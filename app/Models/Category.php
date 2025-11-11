<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     * (Sebenarnya opsional karena Laravel otomatis menebak dari nama model.)
     */
    protected $table = 'categories';

    /**
     * Kolom yang boleh diisi (mass assignable).
     */
    protected $fillable = [
        'slug',
        'name',
    ];

    /**
     * Jika ingin relasi, misalnya Category punya banyak produk:
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
