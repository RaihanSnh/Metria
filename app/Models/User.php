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

    public function wishlistItems()
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
}