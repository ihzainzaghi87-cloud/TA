<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'banners';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'image',
        'title',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to only include active banners.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive banners.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Get the full URL for the banner image.
     * 
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return Storage::url($this->image);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Delete image file when banner is deleted
        static::deleting(function ($banner) {
            if ($banner->image && Storage::exists($banner->image)) {
                Storage::delete($banner->image);
            }
        });
    }
}
