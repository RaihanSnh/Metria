<?php

namespace App\Models;

use App\Enums\ClothingType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Product extends Model
{
use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'condition',
        'clothing_type',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'clothing_type' => ClothingType::class,
    ];

    // product belongs to a store
    public function stock(): HasMany
    {
        return $this->hasMany(ProductStock::class);
    }

    // genres that this product belongs to
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(OutfitGenre::class, 'product_genres');
    }

    // materials used in this product
    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(Material::class, 'product_materials');
    }

    // This product can be part of many outfits (polymorphic)
    public function outfits(): MorphToMany
    {
        return $this->morphToMany(Outfit::class, 'itemable', 'outfit_items');
    }

    // product has many size charts
    public function sizeCharts(): HasMany
    {
        return $this->hasMany(ProductSizeChart::class);
    }

    // product has many size recommendations
    public function sizeRecommendations(): HasMany
    {
        return $this->hasMany(SizeRecommendation::class);
    }

    // product can be tagged in many posts
    public function taggedInPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_items', 'item_id', 'post_id')
            ->wherePivot('item_type', 'product');
    }

    // Helper method untuk mendapatkan rekomendasi ukuran untuk user
    public function getSizeRecommendationForUser(User $user): ?SizeRecommendation
    {
        return $this->sizeRecommendations()
            ->where('user_id', $user->id)
            ->first();
    }

    // Helper method untuk mendapatkan ukuran yang tersedia
    public function getAvailableSizes(): array
    {
        return $this->sizeCharts()
            ->pluck('size_label')
            ->unique()
            ->toArray();
    }
}