<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function show(Request $request)
    {
        $allProducts = ShopController::catalogProducts();

        // Sort by discount percentage descending and take top 8
        $discountProducts = $allProducts;
        usort($discountProducts, function ($a, $b) {
            $discA = abs((int) filter_var($a['discount'], FILTER_SANITIZE_NUMBER_INT));
            $discB = abs((int) filter_var($b['discount'], FILTER_SANITIZE_NUMBER_INT));
            return $discB <=> $discA;
        });

        $top8Discounts = array_slice($discountProducts, 0, 8);

        // Visible reviews
        $reviews = Review::visible()->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->get();

        return view('brand', [
            'top8Discounts' => $top8Discounts,
            'products' => $top8Discounts,
            'reviews' => $reviews,
        ]);
    }
}
