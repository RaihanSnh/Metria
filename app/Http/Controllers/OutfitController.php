<?php

namespace App\Http\Controllers;

use App\Models\DigitalWardrobeItem;
use App\Models\Outfit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OutfitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $outfits = Auth::user()->outfits()->with('items.itemable')->latest()->paginate(9);
        return view('outfits.index', compact('outfits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $wardrobeItems = DigitalWardrobeItem::where('user_id', Auth::id())
            ->get()
            ->groupBy('clothing_type');

        return view('outfits.create', compact('wardrobeItems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'items' => 'required|json',
        ]);

        $items = json_decode($validated['items'], true);

        if (empty($items)) {
            return back()->with('error', 'An outfit must have at least one item.')->withInput();
        }

        $outfit = Auth::user()->outfits()->create([
            'name' => $validated['name'],
        ]);

        foreach ($items as $itemData) {
            $modelType = null;
            if ($itemData['type'] === 'digital_wardrobe_item') {
                $modelType = \App\Models\DigitalWardrobeItem::class;
            } elseif ($itemData['type'] === 'product') {
                $modelType = \App\Models\Product::class;
            }

            if ($modelType) {
                $outfit->items()->create([
                    'itemable_id' => $itemData['id'],
                    'itemable_type' => $modelType,
                ]);
            }
        }
        
        return redirect()->route('outfits.index')->with('success', 'Outfit created successfully!');
    }

    /**
     * Display the specified outfit.
     *
     * @param  \App\Models\Outfit  $outfit
     * @return \Illuminate\Http\Response
     */
    public function show(Outfit $outfit)
    {
        // Check if user owns this outfit
        if ($outfit->user_id !== auth()->id()) {
            return redirect()->route('outfits.index')
                ->with('error', 'You do not have permission to view this outfit.');
        }

        // Parse the JSON items into a collection
        $outfitItems = collect(json_decode($outfit->items, true) ?? []);
        
        return view('outfits.show', [
            'outfit' => $outfit,
            'outfitItems' => $outfitItems
        ]);
    }
}
