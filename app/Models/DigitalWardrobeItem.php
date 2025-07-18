<?php

namespace App\Models;

use App\Enums\ClothingType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class DigitalWardrobeItem extends Model
{
use HasFactory;

    protected $fillable = [
        'user_id',
        'item_name',
        'item_image_url',
        'clothing_type',
    ];

    protected $casts = [
        'clothing_type' => ClothingType::class,
    ];

    // this item belongs to a user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // this item can be part of many outfits (polymorphic)
    public function outfits(): MorphToMany
    {
        return $this->morphToMany(Outfit::class, 'itemable', 'outfit_items');
    }
}