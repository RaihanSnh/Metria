<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function index(): View
    {
        $user = Auth::user();
        
        // Get user stats with null safety
        $stats = [
            'orders_placed' => method_exists($user, 'orders') ? $user->orders()->count() : 0,
            'wishlist_items' => method_exists($user, 'wishlists') ? $user->wishlists()->count() : 0,
            'affiliate_earnings' => $user->affiliate_earnings ?? 0,
            'posts_created' => method_exists($user, 'posts') ? $user->posts()->count() : 0,
            'wardrobe_items' => method_exists($user, 'digitalWardrobeItems') ? $user->digitalWardrobeItems()->count() : 0,
            'outfits_created' => method_exists($user, 'outfits') ? $user->outfits()->count() : 0,
            'followers' => $user->followers_count ?? 0,
            'following' => $user->following_count ?? 0,
        ];

        // Get recent data with null safety
        $recent_posts = method_exists($user, 'posts') ? $user->posts()->latest()->take(6)->get() : collect();
        $recent_wardrobe = method_exists($user, 'digitalWardrobeItems') ? $user->digitalWardrobeItems()->latest()->take(6)->get() : collect();
        $recent_outfits = method_exists($user, 'outfits') ? $user->outfits()->latest()->take(3)->get() : collect();

        return view('profile.index', compact('stats', 'recent_posts', 'recent_wardrobe', 'recent_outfits', 'user'));
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        $validated = $request->validated();
        
        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $validated['profile_image'] = $path;
        }
        
        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('cover_images', 'public');
            $validated['cover_image'] = $path;
        }
        
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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