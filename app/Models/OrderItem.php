<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * table that this model refers to.
     *
     * @var string
     */
    protected $table = 'order_items';

    /**
     * attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price_at_purchase',
    ];

    /**
     * attributes that must be cast.
     *
     * @var array
     */
    protected $casts = [
        'price_at_purchase' => 'decimal:2',
    ];

    /**
     * non-nullable timestamps.
     * This is optional, but if you want to use created_at and updated_at,
     * you can set this to true.
     * If you don't want to use timestamps, set this to false.
     * If you set this to true, make sure your migration file
     * has created_at and updated_at columns.
     * If you set this to false, you can remove the timestamps
     * from your migration file.
     * @var bool
     */
    public $timestamps = true; // assume you will add timestamps to migration

    /**
     * Item which this OrderItem belongs to.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Item which this OrderItem refers to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}