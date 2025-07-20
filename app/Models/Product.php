<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'name',
        'description',
        'price',
        'category',
        'image_url',
        'condition',
        'clothing_type',
    ];

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'product_stock')->withPivot('stock');
    }

    public function materials()
    {
        return $this->belongsToMany(Material::class, 'product_materials');
    }

    public function genres()
    {
        return $this->belongsToMany(OutfitGenre::class, 'product_genres');
    }

    public function sizeCharts()
    {
        return $this->hasMany(ProductSizeChart::class);
    }

    public function stock()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'wishlist_items');
    }
}