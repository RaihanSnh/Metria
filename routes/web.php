<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('feed.index');
    }
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
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
    
    Route::resource('products', ProductController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('stores', StoreController::class)->middleware('auth');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
    
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

    Route::get('/feed', [FeedController::class, 'index'])->name('feed.index');
    
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

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

// require __DIR__.'/auth.php';
