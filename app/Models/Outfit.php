<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphedByMany;



class Outfit extends Model
{
use HasFactory;

    protected $fillable = ['user_id', 'name'];

    // this outfit belongs to a user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // this outfit has many products
    public function products(): MorphedByMany
    {
        return $this->morphedByMany(Product::class, 'itemable', 'outfit_items');
    }

    // this outfit has many digital wardrobe items
    public function digitalWardrobeItems(): MorphedByMany
    {
        return $this->morphedByMany(DigitalWardrobeItem::class, 'itemable', 'outfit_items');
    }
}