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

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'full_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'height_cm' => 'nullable|integer|min:100|max:250',
            'weight_kg' => 'nullable|numeric|min:30|max:200',
            'bust_circumference_cm' => 'nullable|integer|min:50|max:150',
            'waist_circumference_cm' => 'nullable|integer|min:50|max:150',
            'hip_circumference_cm' => 'nullable|integer|min:50|max:150',
        ]);

        $user->update($request->only([
            'full_name', 'username', 'email', 'height_cm', 'weight_kg',
            'bust_circumference_cm', 'waist_circumference_cm', 'hip_circumference_cm'
        ]));

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        
        Auth::logout();
        
        $user->delete();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Your account has been deleted successfully.');
    }
}
