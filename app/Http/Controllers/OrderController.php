<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\FairAllocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected $allocationService;

    public function __construct(FairAllocationService $allocationService)
    {
        $this->allocationService = $allocationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Auth::user()->orders()->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $user = Auth::user();

        // Start a database transaction to ensure data integrity
        DB::beginTransaction();

        try {
            // 1. Use the FairAllocationService to get the allocated store
            $allocatedStore = $this->allocationService->allocateStore($product);

            if (!$allocatedStore) {
                // If no store can be allocated, roll back and show an error
                DB::rollBack();
                return redirect()->back()->with('error', 'Sorry, this product is currently unavailable from any store.');
            }

            // 2. Create the Order
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending', // or use an Enum
                'total_amount' => $product->price * $request->quantity,
            ]);

            // 3. Create the Order Item
            $order->items()->create([
                'product_id' => $product->id,
                'store_id' => $allocatedStore->id,
                'quantity' => $request->quantity,
                'price' => $product->price,
            ]);

            // 4. Decrement the stock for the allocated store
            DB::table('product_stock')
                ->where('store_id', $allocatedStore->id)
                ->where('product_id', $product->id)
                ->decrement('stock', $request->quantity);

            // If everything is fine, commit the transaction
            DB::commit();

            // Redirect to a success page or order details page
            // We'll create this view next.
            return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully! Your order has been allocated to a store.');

        } catch (\Exception $e) {
            // If anything goes wrong, roll back the transaction
            DB::rollBack();

            // Log the error and redirect back with a generic error message
            // Log::error('Order creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while placing your order. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        // Add authorization to ensure only the user who owns the order can see it
        $this->authorize('view', $order);

        // Eager load relationships for efficiency
        $order->load('items.product', 'items.store');

        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
