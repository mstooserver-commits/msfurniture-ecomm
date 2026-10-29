<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with(['images', 'brand', 'category']);

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->category) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        return response()->json($query->paginate($request->per_page ?? 20));
    }

    public function show(Product $product)
    {
        return response()->json($product->load(['images', 'videos', 'variants', 'brand', 'reviews']));
    }

    public function categories()
    {
        return response()->json(Category::active()->with('subcategories')->orderBy('sort_order')->get());
    }
}
