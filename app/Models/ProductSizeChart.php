<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSizeChart extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size_label',
        'chest_cm',
        'waist_cm',
        'hip_cm',
        'length_cm',
        'shoulder_cm',
        'sleeve_cm',
        'weight_recommendation_min',
        'weight_recommendation_max',
        'height_recommendation_min',
        'height_recommendation_max',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
