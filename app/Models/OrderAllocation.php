<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'store_id',
        'distance_km',
        'allocation_priority',
        'allocation_method',
        'allocated_at',
        'allocation_notes',
    ];

    protected $casts = [
        'distance_km' => 'decimal:2',
        'allocated_at' => 'datetime',
    ];

    // Order allocation belongs to order
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Order allocation belongs to store
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    // Scope untuk filter berdasarkan metode alokasi
    public function scopeByDistance($query)
    {
        return $query->where('allocation_method', 'distance');
    }

    public function scopeByRoundRobin($query)
    {
        return $query->where('allocation_method', 'round_robin');
    }

    public function scopeManual($query)
    {
        return $query->where('allocation_method', 'manual');
    }

    // Scope untuk mendapatkan alokasi berdasarkan prioritas
    public function scopeByPriority($query)
    {
        return $query->orderBy('allocation_priority', 'asc');
    }

    // Helper method untuk mendapatkan alokasi terbaik
    public static function getBestAllocation($orderId)
    {
        return self::where('order_id', $orderId)
            ->byPriority()
            ->first();
    }
}
