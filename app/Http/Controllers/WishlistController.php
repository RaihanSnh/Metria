<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist.
     */
    public function index()
    {
        $wishlistItems = Auth::user()->wishlist()->latest()->paginate(10);
        return view('wishlist.index', compact('wishlistItems'));
    }

    /**
     * Add a product to the user's wishlist.
     */
    public function store(Product $product)
    {
        Auth::user()->wishlist()->syncWithoutDetaching([$product->id]);
        return back()->with('success', 'Product added to your wishlist!');
    }

    /**
     * Remove a product from the user's wishlist.
     */
    public function destroy(Product $product)
    {
        Auth::user()->wishlist()->detach($product->id);
        return back()->with('success', 'Product removed from your wishlist.');
    }
}
