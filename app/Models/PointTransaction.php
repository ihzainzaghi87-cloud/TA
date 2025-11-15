<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointTransaction extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'point_transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'points',
        'balance_after',
        'description',
        'transactionable_id',
        'transactionable_type',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'points' => 'integer',
        'balance_after' => 'integer',
    ];

    /**
     * Get the user that owns the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent transactionable model (Order, OrderItem, etc.).
     */
    public function transactionable()
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include earned transactions.
     */
    public function scopeEarned($query)
    {
        return $query->where('type', 'earned');
    }

    /**
     * Scope a query to only include redeemed transactions.
     */
    public function scopeRedeemed($query)
    {
        return $query->where('type', 'redeemed');
    }

    /**
     * Scope a query to only include completed transactions.
     */
    // public function scopeCompleted($query)
    // {
    //     return $query->where('status', 'completed');
    // }

    /**
     * Scope a query to only include pending transactions.
     */
    // public function scopePending($query)
    // {
    //     return $query->where('status', 'pending');
    // }

    /**
     * Scope a query to only include cancelled transactions.
     */
    // public function scopeCancelled($query)
    // {
    //     return $query->where('status', 'cancelled');
    // }
}
