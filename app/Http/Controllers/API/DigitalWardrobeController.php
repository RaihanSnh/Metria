<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DigitalWardrobeItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DigitalWardrobeController extends Controller
{
    /**
     * Display user's digital wardrobe
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $items = DigitalWardrobeItem::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Group items by clothing type for easier frontend handling
        $groupedItems = $items->groupBy('clothing_type');

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $items,
                'grouped_items' => $groupedItems,
                'total_items' => $items->count(),
            ],
        ]);
    }

    /**
     * Store a newly created wardrobe item
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_name' => 'required|string|max:255',
            'clothing_type' => 'required|in:top,outerwear,bottom,full_body,shoes,accessory,hat',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = $request->user();

            // Upload image
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('wardrobe', 'public');
            }

            // Create wardrobe item
            $item = DigitalWardrobeItem::create([
                'user_id' => $user->id,
                'item_name' => $request->item_name,
                'clothing_type' => $request->clothing_type,
                'item_image_url' => $imagePath ? Storage::url($imagePath) : null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Wardrobe item added successfully',
                'data' => $item,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add wardrobe item',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified wardrobe item
     */
    public function show(Request $request, DigitalWardrobeItem $digitalWardrobeItem)
    {
        $user = $request->user();

        // Check if user owns this item
        if ($digitalWardrobeItem->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $digitalWardrobeItem,
        ]);
    }

    /**
     * Update the specified wardrobe item
     */
    public function update(Request $request, DigitalWardrobeItem $digitalWardrobeItem)
    {
        $user = $request->user();

        // Check if user owns this item
        if ($digitalWardrobeItem->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'item_name' => 'string|max:255',
            'clothing_type' => 'in:top,outerwear,bottom,full_body,shoes,accessory,hat',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $updateData = $request->only(['item_name', 'clothing_type']);

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($digitalWardrobeItem->item_image_url) {
                    $oldImagePath = str_replace('/storage/', '', $digitalWardrobeItem->item_image_url);
                    Storage::disk('public')->delete($oldImagePath);
                }

                // Upload new image
                $imagePath = $request->file('image')->store('wardrobe', 'public');
                $updateData['item_image_url'] = Storage::url($imagePath);
            }

            $digitalWardrobeItem->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Wardrobe item updated successfully',
                'data' => $digitalWardrobeItem,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update wardrobe item',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified wardrobe item
     */
    public function destroy(Request $request, DigitalWardrobeItem $digitalWardrobeItem)
    {
        $user = $request->user();

        // Check if user owns this item
        if ($digitalWardrobeItem->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        try {
            // Delete image file if exists
            if ($digitalWardrobeItem->item_image_url) {
                $imagePath = str_replace('/storage/', '', $digitalWardrobeItem->item_image_url);
                Storage::disk('public')->delete($imagePath);
            }

            $digitalWardrobeItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Wardrobe item deleted successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete wardrobe item',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get wardrobe items by clothing type
     */
    public function getByType(Request $request, $clothingType)
    {
        $user = $request->user();

        $validTypes = ['top', 'outerwear', 'bottom', 'full_body', 'shoes', 'accessory', 'hat'];
        
        if (!in_array($clothingType, $validTypes)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid clothing type',
            ], 400);
        }

        $items = DigitalWardrobeItem::where('user_id', $user->id)
            ->where('clothing_type', $clothingType)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items,
            'clothing_type' => $clothingType,
        ]);
    }

    /**
     * Get wardrobe statistics
     */
    public function getStats(Request $request)
    {
        $user = $request->user();

        $stats = DigitalWardrobeItem::where('user_id', $user->id)
            ->selectRaw('clothing_type, COUNT(*) as count')
            ->groupBy('clothing_type')
            ->get()
            ->pluck('count', 'clothing_type')
            ->toArray();

        $totalItems = array_sum($stats);

        return response()->json([
            'success' => true,
            'data' => [
                'total_items' => $totalItems,
                'by_type' => $stats,
                'most_common_type' => $totalItems > 0 ? array_search(max($stats), $stats) : null,
            ],
        ]);
    }

    /**
     * Search wardrobe items
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:2',
            'clothing_type' => 'nullable|in:top,outerwear,bottom,full_body,shoes,accessory,hat',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();
        $query = $request->query;

        $items = DigitalWardrobeItem::where('user_id', $user->id)
            ->where('item_name', 'like', "%{$query}%")
            ->when($request->clothing_type, function ($q) use ($request) {
                $q->where('clothing_type', $request->clothing_type);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items,
            'query' => $query,
        ]);
    }

    /**
     * Get items suitable for outfit creation
     */
    public function getForOutfit(Request $request)
    {
        $user = $request->user();

        $items = DigitalWardrobeItem::where('user_id', $user->id)
            ->orderBy('clothing_type')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('clothing_type');

        return response()->json([
            'success' => true,
            'data' => $items,
            'message' => 'Items grouped by type for outfit creation',
        ]);
    }
}
