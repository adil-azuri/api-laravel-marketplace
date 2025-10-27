<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function addproduct(Request $request)
    {
        // 1. Validate Input
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:10000',
            'stock' => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
            'photo_product' => 'required|image|mimes:jpg,jpeg,png,gif|max:5120' // max 5MB
        ]);

        //2.Handle File Upload
        $path = $request->file('photo_product')->store('product','public');

        // 3. Create Product
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'photo_product' => $path            
        ]);

         return response()->json([
            'status' => 'success',
            'message' => 'Add Product Success',
            'data' => $product
        ], 201);

    }


}
