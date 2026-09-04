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
        $category = (string) $request->input('category', '');
        if ($category !== '' && $category !== 'all') {
            $products = array_values(array_filter($products, fn (array $product) => $product['category'] === $category));
        }
        return response()->json(['data' => $products, 'meta' => ['count' => count($products)]]);
    }

    public function show(string $slug): JsonResponse
    {
        $product = Product::with(['category', 'sizes', 'flavors'])
            ->active()
            ->whereHas('category', fn ($q) => $q->where('is_active', true))
            ->where(function ($q) use ($slug) {
                $q->where('slug', $slug);
                if (is_numeric($slug)) {
                    $q->orWhere('id', (int) $slug);
                }
            })
            ->firstOrFail();

        return response()->json(['data' => $product->toStorefrontArray()]);
    }
}
