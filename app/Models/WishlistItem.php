<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class WishlistItem extends Pivot
{
    protected $table = 'wishlist_items';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
} 