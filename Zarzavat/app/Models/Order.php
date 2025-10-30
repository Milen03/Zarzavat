<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'total_price',
        'address',
        'phone',
        'name',
        'email',
    ];

    // Преднадлеци на User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Поръчката има много order_items

    public function items()
    {

        return $this->hasMany(OrderItem::class);
    }
}
