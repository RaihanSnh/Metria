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
        $request->validate([
            'name' => 'required|string|max:255',
            'items' => 'required|string',
        ]);

        // Decode the JSON items from the form
        $items = json_decode($request->items, true);
        
        // Validate that we have items
        if (empty($items)) {
            return redirect()->back()->with('error', 'Please add at least one item to your outfit.');
        }

        // Create the outfit with the JSON items
        $outfit = Outfit::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'items' => $items, // This will be cast to JSON by the model
        ]);

        return redirect()->route('outfits.index')
            ->with('success', 'Outfit created successfully!');
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

        // The items are already cast to array via the model
        $outfitItems = collect($outfit->items ?? []);
        
        return view('outfits.show', [
            'outfit' => $outfit,
            'outfitItems' => $outfitItems
        ]);
    }
}
