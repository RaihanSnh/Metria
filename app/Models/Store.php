<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
use HasFactory;

    protected $fillable = [
        'user_id',
        'store_name',
        'description',
        'city',
        'province',
    ];

    // store belongs to a user (owner)
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // store has many product stocks
    public function stock(): HasMany
    {
        return $this->hasMany(ProductStock::class);
    }

    // store has many allocated orders
    public function allocatedOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'allocated_store_id');
    }
}