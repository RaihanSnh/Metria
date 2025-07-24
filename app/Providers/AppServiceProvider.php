<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Product;
use App\Models\DigitalWardrobeItem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define morph map for polymorphic relationships
        Relation::morphMap([
            'product' => Product::class,
            'digital_wardrobe_item' => DigitalWardrobeItem::class,
        ]);
    }
}
