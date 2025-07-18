<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SizeRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'recommended_size',
        'confidence_score',
        'calculation_data',
        'recommendation_notes',
    ];

    protected $casts = [
        'confidence_score' => 'decimal:2',
        'calculation_data' => 'array',
    ];

    // Size recommendation belongs to user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Size recommendation belongs to product
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Scope untuk filter berdasarkan confidence score
    public function scopeHighConfidence($query)
    {
        return $query->where('confidence_score', '>=', 0.8);
    }

    public function scopeMediumConfidence($query)
    {
        return $query->whereBetween('confidence_score', [0.5, 0.79]);
    }

    public function scopeLowConfidence($query)
    {
        return $query->where('confidence_score', '<', 0.5);
    }

    // Helper method untuk mendapatkan rekomendasi berdasarkan user dan product
    public static function getRecommendation($userId, $productId)
    {
        return self::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();
    }

    // Helper method untuk check apakah rekomendasi reliable
    public function isReliable(): bool
    {
        return $this->confidence_score >= 0.7;
    }
}
