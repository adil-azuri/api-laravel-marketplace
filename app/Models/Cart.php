<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
     use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'total',
    ];

    // Relasi ke User (user: Users)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Product (product: Products)
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    
    // Relasi ke Order (Order: Order[])
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
