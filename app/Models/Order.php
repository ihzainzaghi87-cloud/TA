<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'user_address_id',
        'order_number',
        'subtotal',
        'shipping_cost',
        'total',
        'total_points_used',
        'points_earned',
        'status',
        'snap_token',
        'payment_status',
        'notes',
        'shipping_recipient_name',
        'shipping_phone',
        'courier',
        'service',
        'weight',
        'origin_city_id',
        'destination_city_id',
        'tracking_number',
        'shipped_at',
        'delivered_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'total_points_used' => 'integer',
        'points_earned' => 'integer',
        'weight' => 'integer',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items for the order.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(UserAddress::class, 'user_address_id');
    }

    /**
     * Get all of the point transactions for the order.
     */
    public function pointTransactions()
    {
        return $this->morphMany(PointTransaction::class, 'transactionable');
    }

    /**
     * Scope a query to only include pending orders.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Generate a unique order number.
     */
    public static function generateOrderNumber()
    {
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
        return "ORD-{$date}-{$random}";
    }

    public function isShipped(): bool
    {
        return $this->status === 'Shipped' || $this->status === 'Delivered';
    }

    public function isDelivered(): bool
    {
        return $this->status === 'Delivered';
    }

    public function canBeShipped(): bool
    {
        return $this->payment_status === 'Paid' && 
               in_array($this->status, ['Processing', 'Pending']);
    }

    // ✅ NEW: Status constants
    public const STATUS_PENDING = 'Pending';
    public const STATUS_PROCESSING = 'Processing';
    public const STATUS_SHIPPED = 'Shipped';
    public const STATUS_DELIVERED = 'Delivered';
    public const STATUS_CANCELLED = 'Cancelled';

    public const PAYMENT_PENDING = 'Pending';
    public const PAYMENT_PAID = 'Paid';
    public const PAYMENT_FAILED = 'Failed';

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_SHIPPED => 'Shipped',
            self::STATUS_DELIVERED => 'Delivered',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public static function getStatusBadgeClass(string $status): string
    {
        return match($status) {
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_PROCESSING => 'bg-blue-100 text-blue-800',
            self::STATUS_SHIPPED => 'bg-purple-100 text-purple-800',
            self::STATUS_DELIVERED => 'bg-green-100 text-green-800',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Check if order has tracking
     */
    public function hasTracking(): bool
    {
        return !empty($this->tracking_number) && !empty($this->courier);
    }

    /**
     * Get courier for tracking (lowercase)
     */
    public function getCourierForTracking(): string
    {
        return strtolower($this->courier ?? '');
    }

    public function getCourierCodeAttribute(): string
    {
        // Convert courier code to lowercase untuk API
        $courierMap = [
            'JNT' => 'jnt',
            'J&T' => 'jnt',
            'JNE' => 'jne',
            'SICEPAT' => 'sicepat',
            'POS' => 'pos',
            'TIKI' => 'tiki',
            'ANTERAJA' => 'anteraja',
        ];
        
        $courier = strtoupper($this->courier);
        return $courierMap[$courier] ?? strtolower($this->courier);
    }

    /**
     * Get formatted tracking status badge
     */
    public function getTrackingStatusBadgeAttribute(): string
    {
        if (!$this->hasTracking()) {
            return '<span class="px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-600">Belum Dikirim</span>';
        }
        
        return '<span class="px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-600">Dalam Pengiriman</span>';
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }
        });
    }
}
