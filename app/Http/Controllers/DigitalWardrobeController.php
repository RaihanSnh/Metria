<?php

namespace App\Http\Controllers;

use App\Models\DigitalWardrobeItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Enums\ClothingType;

class DigitalWardrobeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Log::info('Digital Wardrobe accessed', ['user_id' => Auth::id()]);
        
        $wardrobeItems = DigitalWardrobeItem::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);
            
        Log::info('Wardrobe items retrieved', [
            'user_id' => Auth::id(),
            'total_items' => $wardrobeItems->total(),
            'current_page_items' => $wardrobeItems->count()
        ]);
        
        return view('wardrobe.index', compact('wardrobeItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Log::info('Wardrobe create form accessed', ['user_id' => Auth::id()]);
        
        $clothingTypes = ClothingType::cases();
        return view('wardrobe.create', compact('clothingTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('Wardrobe item creation attempt', [
            'user_id' => Auth::id(),
            'request_data' => $request->except(['image'])
        ]);

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'clothing_type' => 'required|string|in:top,outerwear,bottom,full_body,shoes,accessory,hat',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'color' => 'nullable|string|max:50',
            'brand' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:20',
            'material' => 'nullable|string|max:100',
            'purchase_date' => 'nullable|date',
            'price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        Log::info('Wardrobe item validation passed', [
            'user_id' => Auth::id(),
            'validated_data' => collect($validated)->except(['image'])->toArray()
        ]);

        try {
            Log::info('Starting image upload for wardrobe item');
            $imagePath = $request->file('image')->store('wardrobe_items', 'public');
            Log::info('Wardrobe item image uploaded', ['image_path' => $imagePath]);

            $wardrobeItem = DigitalWardrobeItem::create([
                'user_id' => Auth::id(),
                'item_name' => $validated['item_name'],
                'clothing_type' => $validated['clothing_type'],
                'item_image_url' => $imagePath, // Fixed field name to match migration
                'color' => $validated['color'] ?? null,
                'brand' => $validated['brand'] ?? null,
                'size' => $validated['size'] ?? null,
                'material' => $validated['material'] ?? null,
                'purchase_date' => $validated['purchase_date'] ?? null,
                'price' => $validated['price'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            Log::info('Wardrobe item created successfully', [
                'item_id' => $wardrobeItem->id,
                'user_id' => Auth::id(),
                'item_name' => $wardrobeItem->item_name
            ]);

            return redirect()->route('wardrobe.index')
                ->with('success', 'Item added to your digital wardrobe successfully!');

        } catch (\Exception $e) {
            Log::error('Wardrobe item creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to add item to wardrobe: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DigitalWardrobeItem $wardrobe)
    {
        $this->authorize('view', $wardrobe);
        
        Log::info('Wardrobe item viewed', [
            'item_id' => $wardrobe->id,
            'user_id' => Auth::id()
        ]);
        
        return view('wardrobe.show', compact('wardrobe'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DigitalWardrobeItem $wardrobe)
    {
        $this->authorize('update', $wardrobe);
        
        $clothingTypes = ClothingType::cases();
        return view('wardrobe.edit', compact('wardrobe', 'clothingTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DigitalWardrobeItem $wardrobe)
    {
        $this->authorize('update', $wardrobe);

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'clothing_type' => 'required|string|in:top,outerwear,bottom,full_body,shoes,accessory,hat',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'color' => 'nullable|string|max:50',
            'brand' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:20',
            'material' => 'nullable|string|max:100',
            'purchase_date' => 'nullable|date',
            'price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('wardrobe_items', 'public');
                $validated['item_image_url'] = $imagePath;
            }

            $wardrobe->update($validated);

            Log::info('Wardrobe item updated', [
                'item_id' => $wardrobe->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('wardrobe.show', $wardrobe)
                ->with('success', 'Wardrobe item updated successfully!');

        } catch (\Exception $e) {
            Log::error('Wardrobe item update failed', [
                'error' => $e->getMessage(),
                'item_id' => $wardrobe->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to update wardrobe item: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DigitalWardrobeItem $wardrobe)
    {
        $this->authorize('delete', $wardrobe);

        try {
            $wardrobe->delete();

            Log::info('Wardrobe item deleted', [
                'item_id' => $wardrobe->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('wardrobe.index')
                ->with('success', 'Item removed from wardrobe successfully!');

        } catch (\Exception $e) {
            Log::error('Wardrobe item deletion failed', [
                'error' => $e->getMessage(),
                'item_id' => $wardrobe->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to remove item from wardrobe.');
        }
    }
}
