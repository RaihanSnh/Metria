<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OutfitItem extends Model
{
    use HasFactory;

    protected $fillable = ['outfit_id', 'itemable_type', 'itemable_id'];

    public function outfit(): BelongsTo
    {
        return $this->belongsTo(Outfit::class);
    }

    public function itemable(): MorphTo
    {
        return $this->morphTo();
    }
}
