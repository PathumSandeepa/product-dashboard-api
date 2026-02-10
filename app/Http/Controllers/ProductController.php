<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(
            Product::query()
                ->when($request->search, function ($query, $search) {
                    $query->where('title', 'ILIKE', "%{$search}%")
                        ->orWhere('description', 'ILIKE', "%{$search}%");
                })
                ->when($request->category, function ($query, $category) {
                    $query->where('category', $category);
                })
                ->when($request->min_price, function ($query, $price) {
                    $query->where('price', '>=', $price);
                })
                ->when($request->max_price, function ($query, $price) {
                    $query->where('price', '<=', $price);
                })
                ->when($request->sort, function ($query, $sort) {
                    match ($sort) {
                        'price_asc' => $query->orderBy('price', 'asc'),
                        'price_desc' => $query->orderBy('price', 'desc'),
                        'newest' => $query->orderBy('created_at', 'desc'),
                        default => $query->orderBy('id', 'desc'),
                    };
                })
                ->paginate(12)
        );
    }

    public function show(string $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'image' => 'required|string|max:255',
            'rating' => 'nullable|array',
        ]);

        $product = Product::create($validated);

        return response()->json($product, 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'category' => 'sometimes|string|max:255',
            'image' => 'sometimes|string|max:255',
            'rating' => 'nullable|array',
        ]);

        $product->update($validated);

        return response()->json($product);
    }

    public function destroy(string $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();
        return response()->json(['message' => 'Product deleted'], 200);
    }
}
