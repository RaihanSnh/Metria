<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSizeChart extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size_label',
        'chest_cm',
        'waist_cm',
        'hip_cm',
        'length_cm',
        'shoulder_cm',
        'sleeve_cm',
        'weight_recommendation_min',
        'weight_recommendation_max',
        'height_recommendation_min',
        'height_recommendation_max',
    ];

    protected $casts = [
        'weight_recommendation_min' => 'decimal:2',
        'weight_recommendation_max' => 'decimal:2',
    ];

    // Product size chart belongs to product
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Helper method untuk check apakah ukuran cocok untuk user
    public function isRecommendedForUser(User $user): bool
    {
        $heightMatch = true;
        $weightMatch = true;

        // Check height recommendation
        if ($this->height_recommendation_min && $this->height_recommendation_max) {
            $heightMatch = $user->height_cm >= $this->height_recommendation_min 
                && $user->height_cm <= $this->height_recommendation_max;
        }

        // Check weight recommendation
        if ($this->weight_recommendation_min && $this->weight_recommendation_max) {
            $weightMatch = $user->weight_kg >= $this->weight_recommendation_min 
                && $user->weight_kg <= $this->weight_recommendation_max;
        }

        return $heightMatch && $weightMatch;
    }

    // Scope untuk filter berdasarkan size label
    public function scopeBySize($query, $sizeLabel)
    {
        return $query->where('size_label', $sizeLabel);
    }

    // Helper method untuk mendapatkan size chart berdasarkan product
    public static function getByProduct($productId)
    {
        return self::where('product_id', $productId)
            ->orderBy('size_label')
            ->get();
    }
}
