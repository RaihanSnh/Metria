<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_user_id',
        'allocated_store_id',
        'referred_by_affiliate_id',
        'total_amount',
        'shipping_address',
        'status',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'status' => OrderStatus::class, // use OrderStatus enum for status
    ];

    // this order belongs to a buyer (user)
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_user_id');
    }

    // this order is allocated to one store (for Fair Allocation System)
    public function allocatedStore(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'allocated_store_id');
    }

    // this order may be referred by a user (affiliate)
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by_affiliate_id');
    }

    // this order has many items
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // this order has many allocations (for Fair Allocation System)
    public function allocations(): HasMany
    {
        return $this->hasMany(OrderAllocation::class);
    }

    // this order may have affiliate commission
    public function affiliateCommission(): HasOne
    {
        return $this->hasOne(AffiliateCommission::class);
    }

    // Helper method untuk mendapatkan alokasi terbaik
    public function getBestAllocation(): ?OrderAllocation
    {
        return $this->allocations()
            ->orderBy('allocation_priority', 'asc')
            ->first();
    }

    // Helper method untuk check apakah order memiliki referrer
    public function hasReferrer(): bool
    {
        return !is_null($this->referred_by_affiliate_id);
    }

    // Helper method untuk calculate commission
    public function calculateCommission($commissionPercentage = 5): float
    {
        return $this->total_amount * ($commissionPercentage / 100);
    }
}