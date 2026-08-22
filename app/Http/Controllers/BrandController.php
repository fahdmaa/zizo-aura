<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function show(Request $request)
    {
        $allProducts = ShopController::getProducts();

        // Sort by discount percentage descending and take top 8
        $discountProducts = $allProducts;
        usort($discountProducts, function ($a, $b) {
            $discA = abs((int) filter_var($a['discount'], FILTER_SANITIZE_NUMBER_INT));
            $discB = abs((int) filter_var($b['discount'], FILTER_SANITIZE_NUMBER_INT));
            return $discB <=> $discA;
        });

        $top8Discounts = array_slice($discountProducts, 0, 8);

        return view('brand', [
            'top8Discounts' => $top8Discounts,
            'products' => $top8Discounts,
        ]);
    }
}
