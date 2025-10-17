<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;
use App\Models\Category;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'image',
    ];

    //Преднадлеци на Category
    public function category(){
        return $this->belongsTo(Category::class);
    }

    // Много OrederItem
    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }
}
