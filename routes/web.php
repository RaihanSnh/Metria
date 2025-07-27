<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('feed');
    }
    return redirect()->route('login');
});

Route::get('/feed', [FeedController::class, 'index'])->middleware(['auth', 'verified'])->name('feed');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('products', ProductController::class);
    Route::resource('stores', StoreController::class);
    Route::resource('posts', PostController::class);
    Route::resource('orders', OrderController::class)->only(['index', 'show', 'store']);
    Route::resource('wardrobe', \App\Http\Controllers\DigitalWardrobeController::class);

    // Wishlist Routes
    Route::get('/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [\App\Http\Controllers\WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{product}', [\App\Http\Controllers\WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // Outfit Routes
    Route::resource('outfits', \App\Http\Controllers\OutfitController::class);
});

// Authentication Routes
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function() {
    Route::get('/register/measurements', [AuthController::class, 'showMeasurementsForm'])->name('register.measurements');
    Route::post('/register/measurements', [AuthController::class, 'saveMeasurements'])->name('register.measurements.store');
});
