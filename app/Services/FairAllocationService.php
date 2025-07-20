<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Support\Facades\DB;

class FairAllocationService
{
    /**
     * Allocate an order to a store based on a specific product and size.
     *
     * @param Product $product The specific product variant being ordered.
     * @param string $size The desired size.
     * @return Store|null The allocated store, or null if none are available.
     */
    public function allocateStore(Product $product, string $size): ?Store
    {
        // Find store IDs that have the specific product_id and size with quantity > 0
        $storeIds = DB::table('product_stock')
            ->where('product_id', $product->id)
            ->where('size', $size)
            ->where('quantity', '>', 0)
            ->pluck('store_id');

        if ($storeIds->isEmpty()) {
            return null;
        }

        // --- Fair Allocation Logic (Random for now) ---
        // Get all store models for the available IDs and pick one at random.
        $availableStores = Store::whereIn('id', $storeIds)->get();
        
        return $availableStores->random();
    }
} 