<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\AffiliateController;
use App\Http\Controllers\API\DigitalWardrobeController;
use App\Http\Controllers\API\OutfitController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\ProductController as ApiProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication routes
Route::post('/login', [AuthController::class, 'apiLogin']);
Route::post('/register', [AuthController::class, 'apiRegister']);

// Public routes
Route::get('/affiliate/program-info', [AffiliateController::class, 'getProgramInfo']);
Route::get('/affiliate/leaderboard', [AffiliateController::class, 'getLeaderboard']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    
    // Authentication
    Route::post('/logout', [AuthController::class, 'apiLogout']);
    
    // User info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Posts (Social Feed & Community)
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'index']);
        Route::post('/', [PostController::class, 'store']);
        Route::get('/search', [PostController::class, 'search']);
        Route::get('/user/{user}', [PostController::class, 'getUserPosts']);
        Route::get('/product/{product}', [PostController::class, 'getProductPosts']);
        Route::get('/affiliate', [PostController::class, 'getAffiliatePosts']);
        Route::get('/{post}', [PostController::class, 'show']);
        Route::put('/{post}', [PostController::class, 'update']);
        Route::delete('/{post}', [PostController::class, 'destroy']);
    });

    // Orders (Fair Allocation System)
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/allocation-stats', [OrderController::class, 'getAllocationStats']);
        Route::get('/fairness-report', [OrderController::class, 'getFairnessReport']);
        Route::get('/{order}', [OrderController::class, 'show']);
        Route::put('/{order}', [OrderController::class, 'update']);
    });

    // Size Recommendations
    Route::get('/products/{product}/size-recommendation', [OrderController::class, 'getSizeRecommendation']);

    // Digital Wardrobe
    Route::prefix('wardrobe')->group(function () {
        Route::get('/', [DigitalWardrobeController::class, 'index']);
        Route::post('/', [DigitalWardrobeController::class, 'store']);
        Route::get('/stats', [DigitalWardrobeController::class, 'getStats']);
        Route::get('/search', [DigitalWardrobeController::class, 'search']);
        Route::get('/for-outfit', [DigitalWardrobeController::class, 'getForOutfit']);
        Route::get('/type/{clothingType}', [DigitalWardrobeController::class, 'getByType']);
        Route::get('/{digitalWardrobeItem}', [DigitalWardrobeController::class, 'show']);
        Route::put('/{digitalWardrobeItem}', [DigitalWardrobeController::class, 'update']);
        Route::delete('/{digitalWardrobeItem}', [DigitalWardrobeController::class, 'destroy']);
    });

    // Outfit Constructor
    Route::prefix('outfits')->group(function () {
        Route::get('/', [OutfitController::class, 'index']);
        Route::post('/', [OutfitController::class, 'store']);
        Route::get('/suggestions', [OutfitController::class, 'getSuggestions']);
        Route::get('/{outfit}', [OutfitController::class, 'show']);
        Route::put('/{outfit}', [OutfitController::class, 'update']);
        Route::delete('/{outfit}', [OutfitController::class, 'destroy']);
        Route::post('/{outfit}/clone', [OutfitController::class, 'clone']);
        Route::get('/{outfit}/shopping-list', [OutfitController::class, 'getShoppingList']);
    });

    // Affiliate System
    Route::prefix('affiliate')->group(function () {
        Route::post('/register', [AffiliateController::class, 'register']);
        Route::get('/dashboard', [AffiliateController::class, 'getDashboard']);
        Route::get('/commissions', [AffiliateController::class, 'getCommissions']);
        Route::get('/stats', [AffiliateController::class, 'getStats']);
        Route::post('/generate-link', [AffiliateController::class, 'generateLink']);
        Route::post('/request-payout', [AffiliateController::class, 'requestPayout']);
    });

    // Wishlist/Manifestation
    Route::prefix('wishlist')->group(function () {
        Route::get('/', function (Request $request) {
            $user = $request->user();
            $wishlistItems = $user->wishlistProducts()->with(['sizeCharts', 'genres', 'materials'])->get();
            return response()->json([
                'success' => true,
                'data' => $wishlistItems,
            ]);
        });
        
        Route::post('/{product}', function (Request $request, $product) {
            $user = $request->user();
            $user->wishlistProducts()->attach($product);
            return response()->json([
                'success' => true,
                'message' => 'Product added to wishlist',
            ]);
        });
        
        Route::delete('/{product}', function (Request $request, $product) {
            $user = $request->user();
            $user->wishlistProducts()->detach($product);
            return response()->json([
                'success' => true,
                'message' => 'Product removed from wishlist',
            ]);
        });
    });

    // Products (Basic CRUD and search)
    Route::prefix('products')->group(function () {
        Route::get('/', function (Request $request) {
            $products = \App\Models\Product::with(['sizeCharts', 'genres', 'materials', 'stock'])
                ->when($request->search, function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                          ->orWhere('description', 'like', '%' . $request->search . '%');
                })
                ->when($request->clothing_type, function ($query) use ($request) {
                    $query->where('clothing_type', $request->clothing_type);
                })
                ->when($request->condition, function ($query) use ($request) {
                    $query->where('condition', $request->condition);
                })
                ->paginate(12);
            
            return response()->json([
                'success' => true,
                'data' => $products,
            ]);
        });

        Route::get('/{product}', function ($product) {
            $product = \App\Models\Product::with(['sizeCharts', 'genres', 'materials', 'stock.store'])
                ->findOrFail($product);
            
            return response()->json([
                'success' => true,
                'data' => $product,
            ]);
        });
    });

    // Stores
    Route::prefix('stores')->group(function () {
        Route::get('/', function (Request $request) {
            $stores = \App\Models\Store::with(['owner', 'stock.product'])
                ->when($request->city, function ($query) use ($request) {
                    $query->where('city', $request->city);
                })
                ->when($request->province, function ($query) use ($request) {
                    $query->where('province', $request->province);
                })
                ->paginate(10);
            
            return response()->json([
                'success' => true,
                'data' => $stores,
            ]);
        });

        Route::get('/{store}', function ($store) {
            $store = \App\Models\Store::with(['owner', 'stock.product'])
                ->findOrFail($store);
            
            return response()->json([
                'success' => true,
                'data' => $store,
            ]);
        });
    });

    // User Profile Management
    Route::prefix('profile')->group(function () {
        Route::get('/', function (Request $request) {
            $user = $request->user();
            $user->load(['store', 'digitalWardrobeItems', 'outfits', 'posts']);
            
            return response()->json([
                'success' => true,
                'data' => $user,
            ]);
        });

        Route::put('/', function (Request $request) {
            $user = $request->user();
            
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'full_name' => 'string|max:255',
                'height_cm' => 'integer|min:100|max:250',
                'weight_kg' => 'numeric|min:30|max:200',
                'bust_circumference_cm' => 'integer|min:50|max:150',
                'waist_circumference_cm' => 'integer|min:50|max:150',
                'hip_circumference_cm' => 'integer|min:50|max:150',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user->update($request->only([
                'full_name', 'height_cm', 'weight_kg', 
                'bust_circumference_cm', 'waist_circumference_cm', 'hip_circumference_cm'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $user,
            ]);
        });
    });
}); 

// Route::get('/products/search', [ApiProductController::class, 'search'])->name('api.products.search'); 