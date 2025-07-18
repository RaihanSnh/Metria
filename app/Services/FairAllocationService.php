<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderAllocation;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Collection;

class FairAllocationService
{
    /**
     * Allocate order to the best store based on Fair Allocation System
     */
    public function allocateOrder(Order $order): ?OrderAllocation
    {
        $buyer = $order->buyer;
        $stores = $this->getEligibleStores($order);
        
        if ($stores->isEmpty()) {
            return null;
        }

        // Calculate allocations for all eligible stores
        $allocations = $this->calculateAllocations($order, $stores, $buyer);
        
        // Save all allocations for transparency
        $this->saveAllocations($allocations);
        
        // Get the best allocation
        $bestAllocation = $allocations->first();
        
        // Update order with allocated store
        $order->update([
            'allocated_store_id' => $bestAllocation->store_id
        ]);
        
        return $bestAllocation;
    }

    /**
     * Get stores that have the required products in stock
     */
    private function getEligibleStores(Order $order): Collection
    {
        $productIds = $order->items->pluck('product_id')->unique();
        
        return Store::whereHas('stock', function ($query) use ($productIds) {
            $query->whereIn('product_id', $productIds)
                  ->where('quantity', '>', 0);
        })->get();
    }

    /**
     * Calculate allocations for all stores
     */
    private function calculateAllocations(Order $order, Collection $stores, User $buyer): Collection
    {
        $allocations = collect();
        
        foreach ($stores as $store) {
            $distance = $this->calculateDistance($buyer, $store);
            $roundRobinScore = $this->calculateRoundRobinScore($store);
            
            $allocation = new OrderAllocation([
                'order_id' => $order->id,
                'store_id' => $store->id,
                'distance_km' => $distance,
                'allocation_method' => 'distance',
                'allocated_at' => now(),
                'allocation_notes' => "Distance: {$distance}km, Round-robin score: {$roundRobinScore}",
            ]);
            
            $allocations->push($allocation);
        }
        
        // Sort by distance (closest first), then by round-robin score
        return $allocations->sortBy(function ($allocation) {
            return [$allocation->distance_km, $this->calculateRoundRobinScore(Store::find($allocation->store_id))];
        })->values()->map(function ($allocation, $index) {
            $allocation->allocation_priority = $index + 1;
            return $allocation;
        });
    }

    /**
     * Calculate distance between buyer and store (simplified)
     * In production, use proper geolocation services
     */
    private function calculateDistance(User $buyer, Store $store): float
    {
        // Simplified distance calculation based on city/province
        // In production, use actual coordinates and distance calculation
        if ($buyer->city === $store->city) {
            return rand(1, 5); // Same city: 1-5 km
        } elseif ($buyer->province === $store->province) {
            return rand(10, 50); // Same province: 10-50 km
        } else {
            return rand(100, 500); // Different province: 100-500 km
        }
    }

    /**
     * Calculate round-robin score for fair distribution
     */
    private function calculateRoundRobinScore(Store $store): int
    {
        // Count recent orders allocated to this store (last 30 days)
        $recentOrders = OrderAllocation::where('store_id', $store->id)
            ->where('allocated_at', '>=', now()->subDays(30))
            ->count();
            
        return $recentOrders;
    }

    /**
     * Save all allocations to database
     */
    private function saveAllocations(Collection $allocations): void
    {
        foreach ($allocations as $allocation) {
            $allocation->save();
        }
    }

    /**
     * Get allocation statistics for a store
     */
    public function getStoreAllocationStats(Store $store, int $days = 30): array
    {
        $allocations = OrderAllocation::where('store_id', $store->id)
            ->where('allocated_at', '>=', now()->subDays($days))
            ->get();
            
        return [
            'total_allocations' => $allocations->count(),
            'average_distance' => $allocations->avg('distance_km'),
            'allocation_methods' => $allocations->groupBy('allocation_method')
                ->map(function ($group) {
                    return $group->count();
                })->toArray(),
        ];
    }

    /**
     * Get overall allocation fairness report
     */
    public function getFairnessReport(int $days = 30): array
    {
        $allocations = OrderAllocation::where('allocated_at', '>=', now()->subDays($days))
            ->with('store')
            ->get();
            
        $storeAllocations = $allocations->groupBy('store_id');
        
        return [
            'total_orders' => $allocations->count(),
            'total_stores' => $storeAllocations->count(),
            'average_per_store' => $allocations->count() / max($storeAllocations->count(), 1),
            'store_distribution' => $storeAllocations->map(function ($group, $storeId) {
                $store = Store::find($storeId);
                return [
                    'store_name' => $store->store_name,
                    'allocation_count' => $group->count(),
                    'average_distance' => $group->avg('distance_km'),
                ];
            })->values()->toArray(),
        ];
    }
} 