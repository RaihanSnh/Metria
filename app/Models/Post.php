<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_image_url',
        'caption',
        'is_sponsored',
    ];

    protected $casts = [
        'is_sponsored' => 'boolean',
    ];

    // Post belongs to a user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Post has many tagged products
    public function taggedProducts(): BelongsToMany
    {
        // Pivot table: post_tagged_products
        // Related model: Product
        // Foreign key on the pivot table: post_id
        // Related key on the pivot table: product_id
        return $this->belongsToMany(Product::class, 'post_tagged_products');
    }

    // Post has many post items (tagged items with positions)
    public function postItems(): HasMany
    {
        return $this->hasMany(PostItem::class);
    }

    // Helper method untuk mendapatkan items yang di-tag dengan affiliate code
    public function getAffiliateItems()
    {
        return $this->postItems()->whereNotNull('affiliate_code');
    }

    // Helper method untuk check apakah post adalah sponsored
    public function isSponsored(): bool
    {
        return $this->is_sponsored;
    }

    // Helper method untuk mendapatkan total affiliate items
    public function getAffiliateItemsCount(): int
    {
        return $this->getAffiliateItems()->count();
    }
}