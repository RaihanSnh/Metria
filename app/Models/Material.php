<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Material extends Model
{
use HasFactory;

protected $fillable = ['name', 'description', 'care_instructions'];

    // material can belong to many products
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_materials');
    }
}