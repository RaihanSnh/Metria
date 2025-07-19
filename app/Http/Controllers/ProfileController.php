<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $stats = [
            'orders_placed' => $user->orders()->count(),
            'wishlist_items' => $user->wishlistItems()->count(),
            'affiliate_earnings' => $user->affiliateCommissions()->sum('commission_amount'),
            'posts_created' => $user->posts()->count(),
        ];

        $recent_posts = $user->posts()
            ->with('items.item')
            ->latest()
            ->limit(3)
            ->get();
            
        $recent_wardrobe_items = $user->digitalWardrobeItems()
            ->latest()
            ->limit(5)
            ->get();

        return view('profile.index', compact('user', 'stats', 'recent_posts', 'recent_wardrobe_items'));
    }
}
