<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PostItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'item_type',
        'item_id',
        'position_x',
        'position_y',
        'affiliate_code',
    ];

    protected $casts = [
        'position_x' => 'decimal:2',
        'position_y' => 'decimal:2',
    ];

    // Post item belongs to post
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    // Polymorphic relationship to item (Product, DigitalWardrobeItem, etc.)
    public function item(): MorphTo
    {
        return $this->morphTo('item', 'item_type', 'item_id');
    }

    // Check if item has affiliate code
    public function hasAffiliateCode(): bool
    {
        return !empty($this->affiliate_code);
    }
}
