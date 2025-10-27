<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->constrained('products'); // productsid
        $table->foreignId('user_id')->constrained('users'); // userid
        $table->foreignId('cart_id')->constrained('carts'); // from_cart
        $table->integer('quantity');
        $table->integer('discount')->default(0);
        $table->integer('tax')->default(0);
        $table->integer('total');
        $table->timestamps(); // createAt
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
