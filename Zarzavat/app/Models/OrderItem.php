<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    // OrderItem принадлежи на поръчка
    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // OrderItem принадлежи на продукт
    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
