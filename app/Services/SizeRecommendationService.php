<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductSizeChart;
use App\Models\SizeRecommendation;
use App\Models\User;

class SizeRecommendationService
{
    /**
     * Generate size recommendation for user and product
     */
    public function generateRecommendation(User $user, Product $product): ?SizeRecommendation
    {
        $sizeCharts = $product->sizeCharts;
        
        if ($sizeCharts->isEmpty()) {
            return null;
        }

        $recommendation = $this->calculateBestSize($user, $sizeCharts);
        
        if (!$recommendation) {
            return null;
        }

        // Save or update recommendation
        return SizeRecommendation::updateOrCreate(
            [
                'user_id' => $user->id,
                'product_id' => $product->id,
            ],
            [
                'recommended_size' => $recommendation['size'],
                'confidence_score' => $recommendation['confidence'],
                'calculation_data' => $recommendation['calculation_data'],
                'recommendation_notes' => $recommendation['notes'],
            ]
        );
    }

    /**
     * Calculate best size based on user measurements
     */
    private function calculateBestSize(User $user, $sizeCharts): ?array
    {
        $scores = [];
        
        foreach ($sizeCharts as $sizeChart) {
            $score = $this->calculateSizeScore($user, $sizeChart);
            $scores[] = [
                'size' => $sizeChart->size_label,
                'score' => $score['score'],
                'confidence' => $score['confidence'],
                'calculation_data' => $score['calculation_data'],
                'size_chart' => $sizeChart,
            ];
        }
        
        // Sort by score (highest first)
        usort($scores, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        $bestMatch = $scores[0] ?? null;
        
        if (!$bestMatch || $bestMatch['score'] < 0.3) {
            return null;
        }
        
        return [
            'size' => $bestMatch['size'],
            'confidence' => $bestMatch['confidence'],
            'calculation_data' => $bestMatch['calculation_data'],
            'notes' => $this->generateRecommendationNotes($bestMatch),
        ];
    }

    /**
     * Calculate score for a specific size chart
     */
    private function calculateSizeScore(User $user, ProductSizeChart $sizeChart): array
    {
        $scores = [];
        $weights = [];
        $calculationData = [];
        
        // Height and weight recommendation check
        if ($sizeChart->height_recommendation_min && $sizeChart->height_recommendation_max) {
            $heightScore = $this->calculateRangeScore(
                $user->height_cm,
                $sizeChart->height_recommendation_min,
                $sizeChart->height_recommendation_max
            );
            $scores[] = $heightScore;
            $weights[] = 0.3;
            $calculationData['height_score'] = $heightScore;
        }
        
        if ($sizeChart->weight_recommendation_min && $sizeChart->weight_recommendation_max) {
            $weightScore = $this->calculateRangeScore(
                $user->weight_kg,
                $sizeChart->weight_recommendation_min,
                $sizeChart->weight_recommendation_max
            );
            $scores[] = $weightScore;
            $weights[] = 0.3;
            $calculationData['weight_score'] = $weightScore;
        }
        
        // Body measurements check
        if ($user->bust_circumference_cm && $sizeChart->chest_cm) {
            $chestScore = $this->calculateMeasurementScore(
                $user->bust_circumference_cm,
                $sizeChart->chest_cm
            );
            $scores[] = $chestScore;
            $weights[] = 0.25;
            $calculationData['chest_score'] = $chestScore;
        }
        
        if ($user->waist_circumference_cm && $sizeChart->waist_cm) {
            $waistScore = $this->calculateMeasurementScore(
                $user->waist_circumference_cm,
                $sizeChart->waist_cm
            );
            $scores[] = $waistScore;
            $weights[] = 0.25;
            $calculationData['waist_score'] = $waistScore;
        }
        
        if ($user->hip_circumference_cm && $sizeChart->hip_cm) {
            $hipScore = $this->calculateMeasurementScore(
                $user->hip_circumference_cm,
                $sizeChart->hip_cm
            );
            $scores[] = $hipScore;
            $weights[] = 0.2;
            $calculationData['hip_score'] = $hipScore;
        }
        
        // Calculate weighted average
        $totalScore = 0;
        $totalWeight = 0;
        
        for ($i = 0; $i < count($scores); $i++) {
            $totalScore += $scores[$i] * $weights[$i];
            $totalWeight += $weights[$i];
        }
        
        $finalScore = $totalWeight > 0 ? $totalScore / $totalWeight : 0;
        $confidence = $this->calculateConfidence($scores, $totalWeight);
        
        return [
            'score' => $finalScore,
            'confidence' => $confidence,
            'calculation_data' => $calculationData,
        ];
    }

    /**
     * Calculate score for range-based recommendations (height, weight)
     */
    private function calculateRangeScore(float $userValue, float $minValue, float $maxValue): float
    {
        if ($userValue >= $minValue && $userValue <= $maxValue) {
            // Perfect match
            return 1.0;
        } elseif ($userValue < $minValue) {
            // Below minimum
            $diff = $minValue - $userValue;
            $range = $maxValue - $minValue;
            return max(0, 1 - ($diff / $range));
        } else {
            // Above maximum
            $diff = $userValue - $maxValue;
            $range = $maxValue - $minValue;
            return max(0, 1 - ($diff / $range));
        }
    }

    /**
     * Calculate score for body measurements
     */
    private function calculateMeasurementScore(float $userMeasurement, float $sizeMeasurement): float
    {
        $diff = abs($userMeasurement - $sizeMeasurement);
        $tolerance = $sizeMeasurement * 0.1; // 10% tolerance
        
        if ($diff <= $tolerance) {
            return 1.0 - ($diff / $tolerance) * 0.2; // Score between 0.8-1.0
        } else {
            return max(0, 0.8 - ($diff - $tolerance) / $sizeMeasurement);
        }
    }

    /**
     * Calculate confidence score
     */
    private function calculateConfidence(array $scores, float $totalWeight): float
    {
        if (empty($scores)) {
            return 0.1;
        }
        
        $avgScore = array_sum($scores) / count($scores);
        $dataCompleteness = min(1.0, $totalWeight / 1.0); // Full weight is 1.0
        
        return min(1.0, $avgScore * $dataCompleteness);
    }

    /**
     * Generate recommendation notes
     */
    private function generateRecommendationNotes(array $bestMatch): string
    {
        $notes = [];
        $calculationData = $bestMatch['calculation_data'];
        
        if (isset($calculationData['height_score']) && $calculationData['height_score'] < 0.8) {
            $notes[] = "Rekomendasi berdasarkan tinggi badan kurang optimal.";
        }
        
        if (isset($calculationData['weight_score']) && $calculationData['weight_score'] < 0.8) {
            $notes[] = "Rekomendasi berdasarkan berat badan kurang optimal.";
        }
        
        if (isset($calculationData['chest_score']) && $calculationData['chest_score'] < 0.8) {
            $notes[] = "Ukuran dada mungkin tidak pas.";
        }
        
        if (isset($calculationData['waist_score']) && $calculationData['waist_score'] < 0.8) {
            $notes[] = "Ukuran pinggang mungkin tidak pas.";
        }
        
        if (empty($notes)) {
            $notes[] = "Ukuran sangat cocok berdasarkan data yang tersedia.";
        }
        
        return implode(" ", $notes);
    }

    /**
     * Get recommendation for user and product
     */
    public function getRecommendation(User $user, Product $product): ?SizeRecommendation
    {
        $recommendation = SizeRecommendation::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();
            
        if (!$recommendation) {
            $recommendation = $this->generateRecommendation($user, $product);
        }
        
        return $recommendation;
    }

    /**
     * Batch generate recommendations for user
     */
    public function generateRecommendationsForUser(User $user, array $productIds = []): array
    {
        $query = Product::with('sizeCharts');
        
        if (!empty($productIds)) {
            $query->whereIn('id', $productIds);
        }
        
        $products = $query->get();
        $recommendations = [];
        
        foreach ($products as $product) {
            $recommendation = $this->generateRecommendation($user, $product);
            if ($recommendation) {
                $recommendations[] = $recommendation;
            }
        }
        
        return $recommendations;
    }
} 