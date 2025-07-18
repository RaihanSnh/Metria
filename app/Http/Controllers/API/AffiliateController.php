<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\AffiliateCommission;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AffiliateController extends Controller
{
    /**
     * Register user as affiliate
     */
    public function register(Request $request)
    {
        $user = $request->user();

        if ($user->isAffiliate()) {
            return response()->json([
                'success' => false,
                'message' => 'User is already an affiliate',
            ], 400);
        }

        try {
            $user->activateAffiliate();

            return response()->json([
                'success' => true,
                'message' => 'Successfully registered as affiliate',
                'data' => [
                    'affiliate_code' => $user->affiliate_code,
                    'is_affiliate' => $user->is_affiliate,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to register as affiliate',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get affiliate dashboard data
     */
    public function getDashboard(Request $request)
    {
        $user = $request->user();

        if (!$user->isAffiliate()) {
            return response()->json([
                'success' => false,
                'message' => 'User is not an affiliate',
            ], 403);
        }

        // Get commission statistics
        $commissions = AffiliateCommission::where('affiliate_user_id', $user->id)->get();
        
        $totalEarnings = $commissions->sum('commission_amount');
        $pendingEarnings = $commissions->where('status', 'pending')->sum('commission_amount');
        $paidEarnings = $commissions->where('status', 'paid')->sum('commission_amount');
        
        // Get recent orders referred by this affiliate
        $recentOrders = Order::where('referred_by_affiliate_id', $user->id)
            ->with(['buyer', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get posts with affiliate items
        $affiliatePosts = Post::whereHas('postItems', function ($query) use ($user) {
            $query->where('affiliate_code', $user->affiliate_code);
        })->count();

        // Monthly earnings
        $monthlyEarnings = AffiliateCommission::where('affiliate_user_id', $user->id)
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('commission_amount');

        return response()->json([
            'success' => true,
            'data' => [
                'affiliate_code' => $user->affiliate_code,
                'total_earnings' => $totalEarnings,
                'pending_earnings' => $pendingEarnings,
                'paid_earnings' => $paidEarnings,
                'monthly_earnings' => $monthlyEarnings,
                'total_referrals' => $recentOrders->count(),
                'affiliate_posts' => $affiliatePosts,
                'recent_orders' => $recentOrders,
                'commission_rate' => 5, // 5% commission rate
            ],
        ]);
    }

    /**
     * Get affiliate commissions
     */
    public function getCommissions(Request $request)
    {
        $user = $request->user();

        if (!$user->isAffiliate()) {
            return response()->json([
                'success' => false,
                'message' => 'User is not an affiliate',
            ], 403);
        }

        $commissions = AffiliateCommission::where('affiliate_user_id', $user->id)
            ->with(['order.buyer', 'order.items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $commissions,
        ]);
    }

    /**
     * Get affiliate statistics
     */
    public function getStats(Request $request)
    {
        $user = $request->user();

        if (!$user->isAffiliate()) {
            return response()->json([
                'success' => false,
                'message' => 'User is not an affiliate',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'period' => 'in:week,month,quarter,year',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $period = $request->period ?? 'month';
        $startDate = match($period) {
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'quarter' => now()->startOfQuarter(),
            'year' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };

        // Get commissions for the period
        $commissions = AffiliateCommission::where('affiliate_user_id', $user->id)
            ->where('created_at', '>=', $startDate)
            ->get();

        // Get orders for the period
        $orders = Order::where('referred_by_affiliate_id', $user->id)
            ->where('created_at', '>=', $startDate)
            ->with(['items.product'])
            ->get();

        // Calculate statistics
        $stats = [
            'period' => $period,
            'total_commissions' => $commissions->sum('commission_amount'),
            'total_orders' => $orders->count(),
            'average_order_value' => $orders->avg('total_amount'),
            'conversion_rate' => 0, // This would need click tracking to calculate properly
            'top_products' => $orders->flatMap(function ($order) {
                return $order->items;
            })->groupBy('product_id')->map(function ($items) {
                $product = $items->first()->product;
                return [
                    'product' => $product,
                    'quantity_sold' => $items->sum('quantity'),
                    'total_revenue' => $items->sum(function ($item) {
                        return $item->quantity * $item->price;
                    }),
                ];
            })->sortByDesc('quantity_sold')->take(5)->values(),
            'daily_earnings' => $commissions->groupBy(function ($commission) {
                return $commission->created_at->format('Y-m-d');
            })->map(function ($dayCommissions) {
                return $dayCommissions->sum('commission_amount');
            }),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /**
     * Generate affiliate link for product
     */
    public function generateLink(Request $request)
    {
        $user = $request->user();

        if (!$user->isAffiliate()) {
            return response()->json([
                'success' => false,
                'message' => 'User is not an affiliate',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $productId = $request->product_id;
        $affiliateCode = $user->affiliate_code;

        // Generate affiliate link (you can customize this URL structure)
        $baseUrl = config('app.url');
        $affiliateLink = "{$baseUrl}/product/{$productId}?ref={$affiliateCode}";

        return response()->json([
            'success' => true,
            'data' => [
                'affiliate_link' => $affiliateLink,
                'affiliate_code' => $affiliateCode,
                'product_id' => $productId,
            ],
        ]);
    }

    /**
     * Get affiliate leaderboard
     */
    public function getLeaderboard(Request $request)
    {
        $period = $request->period ?? 'month';
        $startDate = match($period) {
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'quarter' => now()->startOfQuarter(),
            'year' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };

        $leaderboard = User::where('is_affiliate', true)
            ->withSum(['affiliateCommissions as total_earnings' => function ($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate)
                      ->where('status', 'paid');
            }], 'commission_amount')
            ->withCount(['referredOrders as total_referrals' => function ($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate);
            }])
            ->orderByDesc('total_earnings')
            ->limit(20)
            ->get(['id', 'username', 'full_name', 'profile_picture_url']);

        return response()->json([
            'success' => true,
            'data' => [
                'period' => $period,
                'leaderboard' => $leaderboard,
            ],
        ]);
    }

    /**
     * Request commission payout
     */
    public function requestPayout(Request $request)
    {
        $user = $request->user();

        if (!$user->isAffiliate()) {
            return response()->json([
                'success' => false,
                'message' => 'User is not an affiliate',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:10',
            'payment_method' => 'required|in:bank_transfer,paypal,crypto',
            'payment_details' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $pendingAmount = AffiliateCommission::where('affiliate_user_id', $user->id)
            ->where('status', 'pending')
            ->sum('commission_amount');

        if ($request->amount > $pendingAmount) {
            return response()->json([
                'success' => false,
                'message' => 'Requested amount exceeds pending earnings',
            ], 400);
        }

        // Here you would typically create a payout request record
        // For now, we'll just return a success response
        return response()->json([
            'success' => true,
            'message' => 'Payout request submitted successfully',
            'data' => [
                'requested_amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'status' => 'pending_review',
            ],
        ]);
    }

    /**
     * Get affiliate program information
     */
    public function getProgramInfo(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'program_name' => 'Metria Affiliate Program',
                'commission_rate' => 5,
                'minimum_payout' => 10,
                'payment_schedule' => 'Monthly',
                'benefits' => [
                    'Earn 5% commission on every sale',
                    'Real-time tracking and analytics',
                    'Monthly payouts',
                    'Dedicated affiliate support',
                    'Marketing materials provided',
                ],
                'requirements' => [
                    'Must be an active Metria user',
                    'Minimum 10 followers on social media',
                    'Agree to program terms and conditions',
                ],
                'how_it_works' => [
                    'Register as an affiliate',
                    'Get your unique affiliate code',
                    'Share products with your code',
                    'Earn commission on every sale',
                    'Get paid monthly',
                ],
            ],
        ]);
    }
}
