<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $products = ShopController::catalogProducts();
        if ($request->filled('category') && $request->string('category') !== 'all') {
            $products = array_values(array_filter($products, fn (array $product) => $product['category'] === $request->string('category')));
        }
        return response()->json(['data' => $products, 'meta' => ['count' => count($products)]]);
    }

    public function show(string $slug): JsonResponse
    {
        $product = Product::with(['category', 'sizes', 'flavors'])->active()->where('slug', $slug)->firstOrFail();
        abort_unless($product->category?->is_active, 404);
        return response()->json(['data' => $product->toStorefrontArray()]);
    }
}
