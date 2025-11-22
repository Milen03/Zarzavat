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

        $candidates = [];
        // If value already includes a folder
        if (str_contains($raw, '/')) {
            $candidates[] = ltrim($raw, '/');
        } else {
            // Try in root and in products/ subdir
            $candidates[] = ltrim($raw, '/');
            $candidates[] = 'products/' . ltrim($raw, '/');
        }

        foreach ($candidates as $path) {
            if (Storage::disk('public')->exists($path)) {
                return asset('storage/' . $path);
            }
        }

        return asset('images/placeholder.svg');
    }
}
