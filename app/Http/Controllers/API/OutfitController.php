<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Outfit;
use App\Models\OutfitItem;
use App\Models\Product;
use App\Models\DigitalWardrobeItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OutfitController extends Controller
{
    /**
     * Display user's outfits
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $outfits = Outfit::where('user_id', $user->id)
            ->with(['items'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Transform outfits to include item details
        $outfits->getCollection()->transform(function ($outfit) {
            $outfit->outfit_items = $outfit->items->map(function ($item) {
                $itemData = null;
                
                switch ($item->itemable_type) {
                    case 'App\Models\Product':
                        $itemData = Product::find($item->itemable_id);
                        break;
                    case 'App\Models\DigitalWardrobeItem':
                        $itemData = DigitalWardrobeItem::find($item->itemable_id);
                        break;
                }
                
                return [
                    'id' => $item->id,
                    'type' => $item->itemable_type,
                    'item' => $itemData,
                    'clothing_type' => $itemData?->clothing_type ?? 'unknown',
                ];
            });
            
            return $outfit;
        });

        return response()->json([
            'success' => true,
            'data' => $outfits,
        ]);
    }

    /**
     * Store a newly created outfit
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'outfit_name' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|in:product,digital_wardrobe_item',
            'items.*.item_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $user = $request->user();

            // Create outfit
            $outfit = Outfit::create([
                'user_id' => $user->id,
                'outfit_name' => $request->outfit_name,
            ]);

            // Add items to outfit
            foreach ($request->items as $item) {
                // Validate that the item exists and belongs to user (for wardrobe items)
                $itemExists = false;
                $modelClass = null;
                
                switch ($item['type']) {
                    case 'product':
                        $itemExists = Product::where('id', $item['item_id'])->exists();
                        $modelClass = Product::class;
                        break;
                    case 'digital_wardrobe_item':
                        $itemExists = DigitalWardrobeItem::where('id', $item['item_id'])
                            ->where('user_id', $user->id)
                            ->exists();
                        $modelClass = DigitalWardrobeItem::class;
                        break;
                }

                if (!$itemExists) {
                    continue;
                }

                // Create outfit item using polymorphic relationship
                $outfit->items()->create([
                    'itemable_type' => $modelClass,
                    'itemable_id' => $item['item_id'],
                ]);
            }

            DB::commit();

            $outfit->load(['items']);

            return response()->json([
                'success' => true,
                'message' => 'Outfit created successfully',
                'data' => $outfit,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create outfit',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified outfit
     */
    public function show(Request $request, Outfit $outfit)
    {
        $user = $request->user();

        // Check if user owns this outfit
        if ($outfit->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $outfit->load(['items']);

        // Get detailed item information
        $outfit->outfit_items = $outfit->items->map(function ($item) {
            $itemData = null;
            
            switch ($item->itemable_type) {
                case 'App\Models\Product':
                    $itemData = Product::with(['sizeCharts', 'genres', 'materials'])->find($item->itemable_id);
                    break;
                case 'App\Models\DigitalWardrobeItem':
                    $itemData = DigitalWardrobeItem::find($item->itemable_id);
                    break;
            }
            
            return [
                'id' => $item->id,
                'type' => $item->itemable_type,
                'item' => $itemData,
                'clothing_type' => $itemData?->clothing_type ?? 'unknown',
                'can_purchase' => $item->itemable_type === 'App\Models\Product',
            ];
        });

        // Group items by clothing type for outfit visualization
        $groupedItems = $outfit->outfit_items->groupBy('clothing_type');

        return response()->json([
            'success' => true,
            'data' => [
                'outfit' => $outfit,
                'grouped_items' => $groupedItems,
                'total_items' => $outfit->items->count(),
            ],
        ]);
    }

    /**
     * Update the specified outfit
     */
    public function update(Request $request, Outfit $outfit)
    {
        $user = $request->user();

        // Check if user owns this outfit
        if ($outfit->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'outfit_name' => 'string|max:255',
            'items' => 'array',
            'items.*.type' => 'required_with:items|in:product,digital_wardrobe_item',
            'items.*.item_id' => 'required_with:items|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Update outfit name if provided
            if ($request->has('outfit_name')) {
                $outfit->update(['outfit_name' => $request->outfit_name]);
            }

            // Update items if provided
            if ($request->has('items')) {
                // Remove existing items
                $outfit->items()->delete();

                // Add new items
                foreach ($request->items as $item) {
                    $itemExists = false;
                    $modelClass = null;
                    
                    switch ($item['type']) {
                        case 'product':
                            $itemExists = Product::where('id', $item['item_id'])->exists();
                            $modelClass = Product::class;
                            break;
                        case 'digital_wardrobe_item':
                            $itemExists = DigitalWardrobeItem::where('id', $item['item_id'])
                                ->where('user_id', $user->id)
                                ->exists();
                            $modelClass = DigitalWardrobeItem::class;
                            break;
                    }

                    if (!$itemExists) {
                        continue;
                    }

                    $outfit->items()->create([
                        'itemable_type' => $modelClass,
                        'itemable_id' => $item['item_id'],
                    ]);
                }
            }

            DB::commit();

            $outfit->load(['items']);

            return response()->json([
                'success' => true,
                'message' => 'Outfit updated successfully',
                'data' => $outfit,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update outfit',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified outfit
     */
    public function destroy(Request $request, Outfit $outfit)
    {
        $user = $request->user();

        // Check if user owns this outfit
        if ($outfit->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $outfit->delete();

        return response()->json([
            'success' => true,
            'message' => 'Outfit deleted successfully',
        ]);
    }

    /**
     * Get outfit suggestions based on user's wardrobe
     */
    public function getSuggestions(Request $request)
    {
        $user = $request->user();

        // Get user's wardrobe items grouped by type
        $wardrobeItems = DigitalWardrobeItem::where('user_id', $user->id)
            ->get()
            ->groupBy('clothing_type');

        // Simple suggestion algorithm: create outfits with different combinations
        $suggestions = [];

        // Check if user has basic items for complete outfits
        if ($wardrobeItems->has('top') && $wardrobeItems->has('bottom')) {
            $tops = $wardrobeItems['top'];
            $bottoms = $wardrobeItems['bottom'];

            foreach ($tops->take(3) as $top) {
                foreach ($bottoms->take(2) as $bottom) {
                    $suggestion = [
                        'suggested_name' => "Outfit with {$top->item_name} & {$bottom->item_name}",
                        'items' => [
                            ['type' => 'digital_wardrobe_item', 'item_id' => $top->id, 'item' => $top],
                            ['type' => 'digital_wardrobe_item', 'item_id' => $bottom->id, 'item' => $bottom],
                        ],
                    ];

                    // Add accessories if available
                    if ($wardrobeItems->has('accessory')) {
                        $accessory = $wardrobeItems['accessory']->first();
                        $suggestion['items'][] = [
                            'type' => 'digital_wardrobe_item',
                            'item_id' => $accessory->id,
                            'item' => $accessory
                        ];
                    }

                    // Add shoes if available
                    if ($wardrobeItems->has('shoes')) {
                        $shoes = $wardrobeItems['shoes']->first();
                        $suggestion['items'][] = [
                            'type' => 'digital_wardrobe_item',
                            'item_id' => $shoes->id,
                            'item' => $shoes
                        ];
                    }

                    $suggestions[] = $suggestion;
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => array_slice($suggestions, 0, 6), // Limit to 6 suggestions
            'message' => 'Outfit suggestions based on your wardrobe',
        ]);
    }

    /**
     * Get shopping list for outfit (items that need to be purchased)
     */
    public function getShoppingList(Request $request, Outfit $outfit)
    {
        $user = $request->user();

        // Check if user owns this outfit
        if ($outfit->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $outfit->load(['items']);

        // Get products that need to be purchased
        $shoppingList = [];
        $totalCost = 0;

        foreach ($outfit->items as $item) {
            if ($item->itemable_type === 'App\Models\Product') {
                $product = Product::find($item->itemable_id);
                if ($product) {
                    $shoppingList[] = [
                        'product' => $product,
                        'price' => $product->price,
                    ];
                    $totalCost += $product->price;
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'outfit' => $outfit,
                'shopping_list' => $shoppingList,
                'total_cost' => $totalCost,
                'items_to_buy' => count($shoppingList),
            ],
        ]);
    }

    /**
     * Clone outfit (create a copy)
     */
    public function clone(Request $request, Outfit $outfit)
    {
        $user = $request->user();

        // Check if user owns this outfit
        if ($outfit->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        try {
            DB::beginTransaction();

            // Create new outfit
            $newOutfit = Outfit::create([
                'user_id' => $user->id,
                'outfit_name' => $outfit->outfit_name . ' (Copy)',
            ]);

            // Copy items
            foreach ($outfit->items as $item) {
                $newOutfit->items()->create([
                    'itemable_type' => $item->itemable_type,
                    'itemable_id' => $item->itemable_id,
                ]);
            }

            DB::commit();

            $newOutfit->load(['items']);

            return response()->json([
                'success' => true,
                'message' => 'Outfit cloned successfully',
                'data' => $newOutfit,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to clone outfit',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
