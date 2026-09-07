<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function show(Request $request)
    {
        $allProducts = ShopController::catalogProducts();

        // Sort by discount percentage descending, then by review count / rating
        $sortedProducts = $allProducts;
        usort($sortedProducts, function ($a, $b) {
            $discA = abs((int) filter_var($a['discount'] ?? 0, FILTER_SANITIZE_NUMBER_INT));
            $discB = abs((int) filter_var($b['discount'] ?? 0, FILTER_SANITIZE_NUMBER_INT));
            if ($discB !== $discA) {
                return $discB <=> $discA;
            }
            return ($b['review_count'] ?? 0) <=> ($a['review_count'] ?? 0);
        });

        // Visible reviews
        $reviews = Review::visible()->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->get();

        return view('brand', [
            'top8Discounts' => $sortedProducts,
            'products' => $sortedProducts,
            'reviews' => $reviews,
        ]);
    }
}
