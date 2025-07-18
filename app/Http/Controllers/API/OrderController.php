<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\FairAllocationService;
use App\Services\SizeRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    protected $fairAllocationService;
    protected $sizeRecommendationService;

    public function __construct(
        FairAllocationService $fairAllocationService,
        SizeRecommendationService $sizeRecommendationService
    ) {
        $this->fairAllocationService = $fairAllocationService;
        $this->sizeRecommendationService = $sizeRecommendationService;
    }

    /**
     * Display a listing of user's orders
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $orders = Order::where('buyer_user_id', $user->id)
            ->with(['items.product', 'allocatedStore', 'referrer'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    /**
     * Create a new order with Fair Allocation
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.size' => 'required|string',
            'shipping_address' => 'required|string',
            'affiliate_code' => 'nullable|string|exists:users,affiliate_code',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $user = $request->user();
            $items = $request->items;
            $affiliateCode = $request->affiliate_code;

            // Find affiliate user if code is provided
            $affiliateUser = null;
            if ($affiliateCode) {
                $affiliateUser = User::where('affiliate_code', $affiliateCode)
                    ->where('is_affiliate', true)
                    ->first();
            }

            // Calculate total amount
            $totalAmount = 0;
            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                $totalAmount += $product->price * $item['quantity'];
            }

            // Create order
            $order = Order::create([
                'buyer_user_id' => $user->id,
                'referred_by_affiliate_id' => $affiliateUser?->id,
                'total_amount' => $totalAmount,
                'shipping_address' => $request->shipping_address,
                'status' => 'pending',
            ]);

            // Create order items
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'size' => $item['size'],
                    'price' => Product::find($item['product_id'])->price,
                ]);
            }

            // Apply Fair Allocation System
            $allocation = $this->fairAllocationService->allocateOrder($order);

            if (!$allocation) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No available stores found for this order',
                ], 400);
            }

            // Generate size recommendations for future reference
            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                $this->sizeRecommendationService->generateRecommendation($user, $product);
            }

            DB::commit();

            $order->load(['items.product', 'allocatedStore', 'referrer', 'allocations']);

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => $order,
                'allocation' => $allocation,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified order
     */
    public function show(Request $request, Order $order)
    {
        $user = $request->user();

        // Check if user owns this order
        if ($order->buyer_user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $order->load([
            'items.product.sizeCharts',
            'allocatedStore',
            'referrer',
            'allocations.store',
            'affiliateCommission'
        ]);

        // Get size recommendations for each item
        $sizeRecommendations = [];
        foreach ($order->items as $item) {
            $recommendation = $this->sizeRecommendationService->getRecommendation($user, $item->product);
            if ($recommendation) {
                $sizeRecommendations[$item->product_id] = $recommendation;
            }
        }

        return response()->json([
            'success' => true,
            'data' => $order,
            'size_recommendations' => $sizeRecommendations,
        ]);
    }

    /**
     * Update order status
     */
    public function update(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();

        // Check if user owns this order or is the store owner
        if ($order->buyer_user_id !== $user->id && 
            $order->allocatedStore?->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $order->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully',
            'data' => $order,
        ]);
    }

    /**
     * Get size recommendation for a product
     */
    public function getSizeRecommendation(Request $request, Product $product)
    {
        $user = $request->user();
        
        $recommendation = $this->sizeRecommendationService->getRecommendation($user, $product);
        
        if (!$recommendation) {
            return response()->json([
                'success' => false,
                'message' => 'No size recommendation available for this product',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $recommendation,
        ]);
    }

    /**
     * Get Fair Allocation statistics
     */
    public function getAllocationStats(Request $request)
    {
        $user = $request->user();
        
        // Only store owners can see allocation stats
        if (!$user->store) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized - Store owner only',
            ], 403);
        }

        $stats = $this->fairAllocationService->getStoreAllocationStats($user->store);
        
        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /**
     * Get fairness report (admin only)
     */
    public function getFairnessReport(Request $request)
    {
        $user = $request->user();
        
        // Check if user is admin (you can implement your own admin check)
        if (!$user->is_admin ?? false) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized - Admin only',
            ], 403);
        }

        $report = $this->fairAllocationService->getFairnessReport();
        
        return response()->json([
            'success' => true,
            'data' => $report,
        ]);
    }
}
