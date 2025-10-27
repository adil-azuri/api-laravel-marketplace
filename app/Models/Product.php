<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'photo_product',
        'description',
        'stock',
        'delete_at',
    ];
    
    // Relasi ke Cart (Cart: Cart[])
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    // Relasi ke Order (Order: Order[])
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
