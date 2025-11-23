<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'image',
    ];

    // Преднадлеци на Category
    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Много OrederItem
    public function orderItems() : HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getImageUrlAttribute(): string
    {
        $raw = (string) ($this->image ?? '');
        if ($raw === '') {
            return asset('images/placeholder.svg');
        }

        $normalized = ltrim($raw, '/');
        $variants = [];

        // Provided value as-is
        $variants[] = $normalized;
        // Common products/ prefix (if not already)
        if (!str_contains($normalized, '/')) {
            $variants[] = 'products/' . $normalized;
        }

        // Public disk lookup
        foreach ($variants as $candidate) {
            if (Storage::disk('public')->exists($candidate)) {
                return asset('storage/' . $candidate);
            }
        }

        // Fallback: check direct public/products (committed assets)
        foreach ($variants as $candidate) {
            $publicPath = public_path($candidate);
            if (file_exists($publicPath)) {
                return asset($candidate);
            }
        }

        return asset('images/placeholder.svg');
    }
}
