<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    // A Post has many items tagged in it (polymorphic relation)
    public function items(): HasMany
    {
        return $this->hasMany(PostItem::class);
    }

    // Helper method to get only tagged products from the polymorphic relation
    public function getTaggedProducts()
    {
        return $this->items()->where('item_type', 'product')->with('item')->get()->pluck('item');
    }

    // Helper method untuk check apakah post adalah sponsored
    public function isSponsored(): bool
    {
        return $this->is_sponsored;
    }
}