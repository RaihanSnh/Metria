<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'full_name',
        'email',
        'password',
        'profile_picture_url',
        'height_cm',
        'weight_kg',
        'bust_circumference_cm',
        'waist_circumference_cm',
        'hip_circumference_cm',
        'is_affiliate',
        'affiliate_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_affiliate' => 'boolean',
    ];

    // user can have one store (if they are a seller)
    public function store(): HasOne
    {
        return $this->hasOne(Store::class);
    }

    // user can have many posts
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    // user can have many digital wardrobe items
    public function digitalWardrobeItems(): HasMany
    {
        return $this->hasMany(DigitalWardrobeItem::class);
    }

    // user can have many wishlist products
    public function wishlistProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'wishlist_items');
    }

    // user can create many outfits
    public function outfits(): HasMany
    {
        return $this->hasMany(Outfit::class);
    }

    // user can have many orders as a buyer
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'buyer_user_id');
    }

    // user can have many referred orders
    public function referredOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'referred_by_affiliate_id');
    }

    // user can have many affiliate commissions
    public function affiliateCommissions(): HasMany
    {
        return $this->hasMany(AffiliateCommission::class, 'affiliate_user_id');
    }

    // user can have many size recommendations
    public function sizeRecommendations(): HasMany
    {
        return $this->hasMany(SizeRecommendation::class);
    }

    // Helper method untuk generate affiliate code
    public function generateAffiliateCode(): string
    {
        return strtoupper(substr($this->username, 0, 4) . rand(1000, 9999));
    }

    // Helper method untuk activate affiliate
    public function activateAffiliate(): void
    {
        $this->update([
            'is_affiliate' => true,
            'affiliate_code' => $this->generateAffiliateCode(),
        ]);
    }

    // Helper method untuk check if user is affiliate
    public function isAffiliate(): bool
    {
        return $this->is_affiliate;
    }
}