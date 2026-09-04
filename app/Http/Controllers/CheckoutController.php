<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'shipping_address' => ['required', 'string', 'max:2000'],
            'city' => ['required', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'coupon_code' => ['nullable', 'string', 'max:50'],
        ]);

        $order = DB::transaction(function () use ($request, $data) {
            $cartKey = $request->session()->get('cart_id');
            if (empty($cartKey)) {
                throw ValidationException::withMessages(['cart' => 'Votre panier est vide.']);
            }
            $items = CartItem::where('session_id', $cartKey)->lockForUpdate()->get();
            if ($items->isEmpty()) {
                throw ValidationException::withMessages(['cart' => 'Votre panier est vide.']);
            }

            $subtotal = 0.0;
            $lockedProducts = [];
            foreach ($items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item->product_id);
                if (! $product->is_active || ! $product->in_stock || ($product->stock_quantity !== null && $product->stock_quantity < $item->quantity)) {
                    throw ValidationException::withMessages(['cart' => "Le produit {$product->name} n'est plus disponible dans cette quantité."]);
                }
                $lockedProducts[$product->id] = $product;
                $subtotal += (float) $item->unit_price * $item->quantity;
            }
            $subtotal = round($subtotal, 2);

            $coupon = null;
            if (! empty($data['coupon_code'])) {
                $couponCode = mb_strtoupper(trim($data['coupon_code']));
                $coupon = Coupon::whereRaw('upper(code) = ?', [$couponCode])->lockForUpdate()->first();
                if (! $coupon) {
                    throw ValidationException::withMessages(['coupon_code' => 'Code promo invalide.']);
                }
                if (! $coupon->is_active) {
                    throw ValidationException::withMessages(['coupon_code' => 'Ce code promo est inactif.']);
                }
                if ($coupon->isExpired()) {
                    throw ValidationException::withMessages(['coupon_code' => 'Ce code promo a expiré.']);
                }
                if ($coupon->isExhausted()) {
                    throw ValidationException::withMessages(['coupon_code' => 'Ce code promo a atteint sa limite d\'utilisation.']);
                }
                if ($subtotal < (float) $coupon->min_order_amount) {
                    throw ValidationException::withMessages(['coupon_code' => 'Montant minimum d\'achat de ' . (int) $coupon->min_order_amount . ' DH requis pour ce code promo.']);
                }
            }

            $discount = $coupon?->calculateDiscount($subtotal) ?? 0.0;
            $shipping = 35.0;
            $order = Order::create(array_merge($data, [
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'discount_amount' => $discount,
                'total' => max(0, round($subtotal + $shipping - $discount, 2)),
                'coupon_code' => $coupon?->code,
                'status' => 'pending',
            ]));

            foreach ($items as $item) {
                $product = $lockedProducts[$item->product_id];
                $lineTotal = round((float) $item->unit_price * $item->quantity, 2);
                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_image' => $product->image,
                    'variant' => $item->variant ?: null,
                    'unit_price' => $item->unit_price,
                    'quantity' => $item->quantity,
                    'subtotal' => $lineTotal,
                ]);
                if ($product->stock_quantity !== null) {
                    $product->decrement('stock_quantity', $item->quantity);
                }
            }

            if ($coupon) {
                $coupon->increment('used_count');
            }
            CartItem::where('session_id', $cartKey)->delete();

            return $order->load('items');
        });

        return response()->json(['order' => $order], 201);
    }
}
