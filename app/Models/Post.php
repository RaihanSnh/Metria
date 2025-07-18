<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_image_url',
        'caption',
        'is_sponsored',
    ];

    protected $casts = [
        'is_sponsored' => 'boolean',
    ];

    // Post belongs to a user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Post has many tagged products
    public function taggedProducts(): BelongsToMany
    {
        // Pivot table: post_tagged_products
        // Related model: Product
        // Foreign key on the pivot table: post_id
        // Related key on the pivot table: product_id
        return $this->belongsToMany(Product::class, 'post_tagged_products');
    }
}