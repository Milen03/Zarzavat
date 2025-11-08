<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    
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
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Поръчката има много order_items

    public function items() : HasMany
    {

        return $this->hasMany(OrderItem::class);
    }
}
