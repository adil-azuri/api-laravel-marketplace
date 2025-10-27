<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens,HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
       'email',
        'username',
        'fullname',
        'password',
        'role',
        'point',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    /**
     * Get the full URL for the user's profile picture.
     *
     * This is an accessor that modifies the profile_picture attribute.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function profilePicture(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? Storage::url($value) : null,
        );
    }


        ## Relasi Eloquent
    
    /**
     * Relasi ke Cart (User memiliki banyak Cart).
     *
     * @return HasMany
     */
    public function carts(): HasMany
    {
        // Secara default, akan mencari 'user_id' di tabel 'carts'
        return $this->hasMany(Cart::class);
    }

    /**
     * Relasi ke Order (User memiliki banyak Order).
     *
     * @return HasMany
     */
    public function orders(): HasMany
    {
        // Secara default, akan mencari 'user_id' di tabel 'orders'
        return $this->hasMany(Order::class);
    }

    /**
     * Relasi ke Product (User memiliki banyak Product).
     *
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
