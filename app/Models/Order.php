<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
     use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'cart_id',
        'quantity',
        'discount',
        'tax',
        'total',
    ];

    // Relasi ke User (user_order: Users)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Cart (cart: Cart)
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }
    
    // Relasi ke Product (Products: Products)
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
