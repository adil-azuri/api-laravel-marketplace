<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function create(Request $request)
    {
        // 1. Validate Input
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:1000',
            'stock' => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
            'photo_product' => 'required|image|mimes:jpg,jpeg,png,gif|max:5120', // max 5MB
        ]);

        // 2.Handle File Upload
        $path = $request->file('photo_product')->store('product', 'public');

        // 3. Create Product
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'photo_product' => $path,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Add Product Success',
            'data' => $product,
        ], 201);
    }

    public function index(Request $request)
    {
        // 1. Inisiasi Query Builder
        $query = Product::query();

        // Filter: Pencarian berdasarkan Nama Produk
        if ($request->has('name')) {
            $query->where('name', 'like', '%'.$request->input('name').'%');
        }

        // Filter: Harga Minimum (min_price)
        if ($request->has('min_price')) {
            $minPrice = (float) $request->input('min_price');
            $query->where('price', '>=', $minPrice);
        }

        // Filter: Harga Maksimum (max_price)
        if ($request->has('max_price')) {
            $maxPrice = (float) $request->input('max_price');
            $query->where('price', '<=', $maxPrice);
        }

        // --- 3. Terapkan Sorting ---

        // Tentukan kolom yang boleh diurutkan (whitelist)
        $allowedSorts = ['id', 'name', 'price', 'stock', 'created_at'];

        $sortBy = $request->input('sort_by'); // Kolom untuk mengurutkan
        $orderBy = $request->input('order_by', 'asc'); // Urutan (default ASC)

        // Cek apakah kolom sort_by valid (ada di $allowedSorts)
        if (in_array($sortBy, $allowedSorts)) {
            // Cek apakah order_by valid (asc atau desc)
            if (in_array(strtolower($orderBy), ['asc', 'desc'])) {
                // Terapkan sorting
                $query->orderBy($sortBy, $orderBy);
            }
        } else {
            // Default sorting jika tidak ada parameter atau tidak valid
            $query->orderBy('created_at', 'desc');
        }

        // --- 4. Terapkan Pagination ---

        $maxLimit = 100;
        $limit = min((int) $request->input('limit', 15), $maxLimit);

        // Lakukan pagination dan eksekusi query
        $products = $query->paginate($limit);

        return response()->json([
            'status' => 'success',
            'message' => 'Get All Product Success',
            'data' => $products,
        ], 200);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product Not Found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Get All Product Success',
            'data' => $product,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product Not Found',
            ], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:1000',
            'stock' => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
            'photo_product' => 'sometimes|image|mimes:jpg,jpeg,png,gif|max:5120',
        ]);

        if ($request->hasFile('photo_product')) {
            // Hapus foto lama
            if ($product->photo_product && Storage::disk('public')->exists($product->photo_product)) {
                Storage::disk('public')->delete($product->photo_product);
            }

            // Upload foto baru
            $path = $request->file('photo_product')->store('product', 'public');
            $validatedData['photo_product'] = $path;
        }

        $product->update($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Update Product Success',
            'data' => $product,
        ], 200);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product Not Found',
            ], 404);
        }

        // lakukan delete
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product Deleted Successfully',
        ], 200);
    }
}
