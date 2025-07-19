<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;

class SizeRecommendationService
{
    /**
     * Get a size recommendation for a given product and user.
     *
     * @param Product $product The product to get a recommendation for.
     * @param User $user The user to get a recommendation for.
     * @return string|null The recommended size, or null if no recommendation can be made.
     */
    public function getRecommendation(Product $product, User $user): ?string
    {
        // For now, we'll implement a simple recommendation based on height and weight.
        // In the future, this can be expanded to use more complex logic and user measurements.

        $sizeChart = $product->sizeCharts()
            ->where('height_recommendation_min', '<=', $user->height_cm)
            ->where('height_recommendation_max', '>=', $user->height_cm)
            ->where('weight_recommendation_min', '<=', $user->weight_kg)
            ->where('weight_recommendation_max', '>=', $user->weight_kg)
            ->first();

        return $sizeChart->size_label ?? null;
    }
} 