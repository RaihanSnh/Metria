<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OutfitGenre extends Model
{
use HasFactory;

    protected $fillable = ['name', 'description', 'cover_image_url'];

    // outfit genre can have many products
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_genres');
    }
}