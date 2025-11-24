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
       if (!$this->image) {
            return asset('images/placeholder.svg');
         }
         
         return asset('storage/' . ltrim($this->image, '/'));
        }
}
