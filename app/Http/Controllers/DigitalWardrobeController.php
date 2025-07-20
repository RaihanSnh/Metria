<?php

namespace App\Http\Controllers;

use App\Models\DigitalWardrobeItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\ClothingType;

class DigitalWardrobeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wardrobeItems = DigitalWardrobeItem::where('user_id', Auth::id())->latest()->paginate(10);
        return view('wardrobe.index', compact('wardrobeItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clothingTypes = ClothingType::cases();
        return view('wardrobe.create', compact('clothingTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'clothing_type' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('image')->store('wardrobe_items', 'public');

        DigitalWardrobeItem::create([
            'user_id' => Auth::id(),
            'item_name' => $request->item_name,
            'clothing_type' => $request->clothing_type,
            'image_url' => $imagePath,
        ]);

        return redirect()->route('wardrobe.index')->with('success', 'Item added to your wardrobe successfully.');
    }
}
