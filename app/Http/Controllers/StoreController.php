<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('stores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        $store = Store::create([
            'user_id' => $user->id,
            'store_name' => $request->store_name,
            'description' => $request->description,
            'city' => $request->city,
            'province' => $request->province,
        ]);

        // We'll also mark the user as a seller.
        $user->update(['is_seller' => true]);

        return redirect()->route('stores.show', $store)->with('success', 'Congratulations! Your store has been created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Store $store)
    {
        // Ensure the user is authorized to see this store
        $this->authorize('view', $store);

        $products = $store->products()->latest()->paginate(10);

        return view('stores.show', compact('store', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Store $store)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Store $store)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Store $store)
    {
        //
    }
}
