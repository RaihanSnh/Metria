<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Store;

class FairAllocationService
{
    /**
     * Allocate an order to a store based on specific product ID availability.
     *
     * @param Product $product The specific product variant being ordered (e.g., the 'archive' version).
     * @return Store|null The allocated store, or null if none are available.
     */
    public function allocateStore(Product $product): ?Store
    {
        // Find all stores that have this specific product_id in stock.
        // The condition ('boutique' or 'archive') is inherent to the $product model passed in.
        $availableStores = Store::whereHas('products', function ($query) use ($product) {
            $query->where('product_id', $product->id)
                  ->where('stock', '>', 0);
        })->get();

        if ($availableStores->isEmpty()) {
            return null;
        }
        
        // --- Fair Allocation Logic (Random for now) ---
        return $availableStores->random();
    }
} 