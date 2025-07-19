<?php

namespace App\Http\Controllers;

use App\Enums\ClothingType;
use App\Models\Material;
use App\Models\OutfitGenre;
use App\Models\Product;
use App\Services\SizeRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;

class ProductController extends Controller
{
    protected $sizeRecommendationService;

    public function __construct(SizeRecommendationService $sizeRecommendationService)
    {
        $this->sizeRecommendationService = $sizeRecommendationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // This will be refactored later to show a "single showcase" view
        $products = Product::latest()->paginate(20);
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $materials = Material::all();
        $genres = OutfitGenre::all();
        $clothingTypes = ClothingType::cases();
        return view('products.create', compact('materials', 'genres', 'clothingTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $store = $user->store;

        if (!$store) {
            return redirect()->route('stores.create')->with('error', 'You must create a store before adding products.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:1',
            'condition' => 'required|in:boutique,archive',
            'clothing_type' => ['required', new Enum(ClothingType::class)],
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'materials' => 'required|array',
            'materials.*' => 'exists:materials,id',
            'genres' => 'required|array',
            'genres.*' => 'exists:outfit_genres,id',
            'sizes' => 'required|array|min:1',
            'sizes.*.name' => 'required|string|max:255',
            'sizes.*.bust' => 'nullable|integer',
            'sizes.*.waist' => 'nullable|integer',
            'sizes.*.hip' => 'nullable|integer',
        ]);
        
        DB::beginTransaction();
        try {
            $imagePath = $request->file('image')->store('products', 'public');

            // A product is now unique by its name AND condition.
            $product = Product::firstOrCreate(
                [
                    'name' => $validated['name'],
                    'condition' => $validated['condition'],
                ],
                [
                    'description' => $validated['description'],
                    'price' => $validated['price'], // This becomes the price for this specific condition
                    'image_url' => $imagePath,
                    'clothing_type' => $validated['clothing_type'],
                ]
            );

            // Sync materials and genres. This should only happen if the product is newly created.
            if ($product->wasRecentlyCreated) {
                $product->materials()->sync($validated['materials']);
                $product->genres()->sync($validated['genres']);
            }

            // Attach the product to the seller's store with stock information.
            // Use syncWithoutDetaching to avoid overwriting other stores' stock.
            $store->products()->syncWithoutDetaching([
                $product->id => ['stock' => $validated['stock']]
            ]);
            
            // Create the size chart entries only if the product is new.
            if ($product->wasRecentlyCreated) {
                foreach($validated['sizes'] as $sizeData) {
                    $product->sizeCharts()->create([
                        'size_name' => $sizeData['name'],
                        'bust_circumference_cm' => $sizeData['bust'],
                        'waist_circumference_cm' => $sizeData['waist'],
                        'hip_circumference_cm' => $sizeData['hip'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('stores.show', $store)->with('success', 'Product added successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            // It's good practice to log the error.
            // Log::error('Product creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was an error creating the product. Please try again.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Find all variations (Boutique/Archive) of this product by name
        $productVariations = Product::where('name', $product->name)->get()->keyBy('condition');

        $recommendedSize = null;
        if (Auth::check()) {
            // Recommendation might be based on the "base" product
            $recommendedSize = $this->sizeRecommendationService->getRecommendation($product, Auth::user());
        }

        return view('products.show', compact('product', 'productVariations', 'recommendedSize'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
