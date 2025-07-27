<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'full_name', 'bio', 
        'profile_image', 'cover_image', 'location', 'website',
        'birth_date', 'gender', 'is_private'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_affiliate' => 'boolean',
    ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_user_id');
    }

    public function wishlist()
    {
        return $this->belongsToMany(Product::class, 'wishlist_items');
    }

    public function affiliateCommissions()
    {
        return $this->hasMany(AffiliateCommission::class, 'affiliate_user_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function digitalWardrobeItems()
    {
        return $this->hasMany(DigitalWardrobeItem::class);
    }

    public function outfits()
    {
        return $this->hasMany(Outfit::class);
    }

    public function isAffiliate(): bool
    {
        return $this->is_affiliate;
    }

    public function store()
    {
        return $this->hasOne(Store::class);
    }

    // Follow relationships
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follows', 'following_id', 'follower_id')
                    ->withTimestamps();
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'user_follows', 'follower_id', 'following_id')
                    ->withTimestamps();
    }

    public function isFollowing($userId)
    {
        return $this->following()->where('following_id', $userId)->exists();
    }

    public function isFollowedBy($userId)
    {
        return $this->followers()->where('follower_id', $userId)->exists();
    }

    // Add wishlists method for profile controller compatibility
    public function wishlists()
    {
        return $this->belongsToMany(Product::class, 'wishlist_items');
    }
}