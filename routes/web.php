<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

// Automated redirection based on auth status
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Authentication routes protected by 'guest' middleware
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected routes that require authentication
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    
    // Placeholder routes for navigation links
    Route::get('/feed', function () {
        return view('feed');
    })->name('feed');
    
    Route::get('/wardrobe', function () {
        return view('wardrobe.index');
    })->name('wardrobe.index');
    
    Route::get('/wardrobe/create', function () {
        return view('wardrobe.create');
    })->name('wardrobe.create');
    
    Route::get('/outfits', function () {
        return view('outfits.index');
    })->name('outfits.index');
    
    Route::get('/outfits/create', function () {
        return view('outfits.create');
    })->name('outfits.create');
    
    Route::get('/products', function () {
        return view('products.index');
    })->name('products.index');
    
    Route::get('/posts/create', function () {
        return view('posts.create');
    })->name('posts.create');
    
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    Route::get('/profile/edit', function () {
        return view('profile.edit');
    })->name('profile.edit');
    
    Route::get('/orders', function () {
        return view('orders.index');
    })->name('orders.index');
    
    Route::get('/affiliate/register', function () {
        return view('affiliate.register');
    })->name('affiliate.register');
    
    Route::get('/affiliate/dashboard', function () {
        return view('affiliate.dashboard');
    })->name('affiliate.dashboard');
});
