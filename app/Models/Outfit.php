<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphedByMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Outfit extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'items'];

    protected $casts = [
        'items' => 'array',
    ];

    /**
     * Get the user that owns the outfit.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the products for the outfit.
     */
    public function products(): MorphedByMany
    {
        return $this->morphedByMany(Product::class, 'itemable', 'outfit_items');
    }

    /**
     * Get all of the digital wardrobe items for the outfit.
     */
    public function digitalWardrobeItems(): MorphedByMany
    {
        return $this->morphedByMany(DigitalWardrobeItem::class, 'itemable', 'outfit_items');
    }

    /**
     * Get all of the items for the outfit.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OutfitItem::class);
    }
}