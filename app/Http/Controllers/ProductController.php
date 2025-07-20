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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
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
        Log::info('Product store method initiated.', ['request_data' => $request->all()]);
        
        $user = Auth::user();
        $store = $user->store;

        if (!$store) {
            return redirect()->route('stores.create')->with('error', 'You must create a store before adding products.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'condition' => 'required|in:new,pre-loved',
            'clothing_type' => ['required', new Enum(ClothingType::class)],
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'materials' => 'required|array',
            'materials.*' => 'exists:materials,id',
            'genres' => 'required|array',
            'genres.*' => 'exists:outfit_genres,id',
            'sizes' => 'required|array|min:1',
            'sizes.*.name' => 'required|string|max:255',
            'sizes.*.quantity' => 'required|integer|min:0', // Validate quantity for each size
            'sizes.*.bust' => 'nullable|integer|min:0',
            'sizes.*.waist' => 'nullable|integer|min:0',
            'sizes.*.hip' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            Log::error('Product creation validation failed.', ['errors' => $validator->errors()->all()]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        
        DB::beginTransaction();
        try {
            $imagePath = $request->file('image')->store('products', 'public');

            $product = Product::firstOrCreate(
                ['name' => $validated['name'], 'condition' => $validated['condition']],
                [
                    'description' => $validated['description'],
                    'price' => $validated['price'],
                    'image_url' => $imagePath,
                    'clothing_type' => $validated['clothing_type'],
                ]
            );

            if ($product->wasRecentlyCreated) {
                $product->materials()->sync($validated['materials']);
                $product->genres()->sync($validated['genres']);
                // Create size chart entries with CORRECT column names
                foreach($validated['sizes'] as $sizeData) {
                    $product->sizeCharts()->create([
                        'size_label' => $sizeData['name'],
                        'chest_cm' => $sizeData['bust'],
                        'waist_cm' => $sizeData['waist'],
                        'hip_cm' => $sizeData['hip'],
                    ]);
                }
            }

            // Correctly add stock for each size in the pivot table
            $stockData = [];
            foreach ($validated['sizes'] as $sizeData) {
                $stockData[$sizeData['name']] = ['quantity' => $sizeData['quantity']];
            }
            // This is a simplified approach. A more robust way would be to updateOrCreate on the pivot.
            // For now, let's just add the stock for this product from this store.
            foreach ($validated['sizes'] as $sizeData) {
                 DB::table('product_stock')->updateOrInsert(
                    ['product_id' => $product->id, 'store_id' => $store->id, 'size' => $sizeData['name']],
                    ['quantity' => $sizeData['quantity']]
                );
            }
            
            DB::commit();

            return redirect()->route('stores.show', $store)->with('success', 'Product added successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product creation failed in try-catch block.', ['exception' => $e]);
            return redirect()->back()->with('error', 'There was an error creating the product. Please try again.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Eager load ALL relationships for the base product
        $product->load('materials', 'genres', 'sizeCharts');

        // Find all variations (new/pre-loved) of this product by name
        $productVariations = Product::where('name', $product->name)->get()->keyBy('condition');

        // Get the total stock for each size across all stores for each variation
        $stockBySize = [];
        foreach ($productVariations as $condition => $variant) {
            $stockBySize[$condition] = DB::table('product_stock')
                ->where('product_id', $variant->id)
                ->groupBy('size')
                ->select('size', DB::raw('SUM(quantity) as total_quantity'))
                ->pluck('total_quantity', 'size');
        }

        $recommendedSize = null;
        if (Auth::check()) {
            $recommendedSize = $this->sizeRecommendationService->getRecommendation($product, Auth::user());
        }

        return view('products.show', compact('product', 'productVariations', 'stockBySize', 'recommendedSize'));
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
