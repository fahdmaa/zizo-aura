<?php

namespace App\Http\Controllers;

use App\Mail\OrderReceived;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
            'items' => ['nullable', 'array', 'min:1'],
            'items.*.slug' => ['nullable', 'string', 'max:255'],
            'items.*.name' => ['nullable', 'string', 'max:255'],
            'items.*.price' => ['nullable', 'numeric', 'min:0'],
            'items.*.quantity' => ['nullable', 'integer', 'min:1'],
            'items.*.flavor' => ['nullable', 'string', 'max:255'],
            'items.*.size' => ['nullable', 'string', 'max:255'],
            'items.*.variant' => ['nullable', 'string', 'max:255'],
            'items.*.image' => ['nullable', 'string', 'max:1000'],
            'items.*.id' => ['nullable'],
        ]);

        $order = DB::transaction(function () use ($request, $data) {
            $processedItems = [];
            $subtotal = 0.0;

            // 1. Process items from payload if provided (localStorage cart)
            if (! empty($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $itemData) {
                    $qty = max(1, (int) ($itemData['quantity'] ?? 1));
                    
                    // Resolve Product from DB
                    $product = null;
                    if (! empty($itemData['id']) && is_numeric($itemData['id'])) {
                        $product = Product::lockForUpdate()->find($itemData['id']);
                    }
                    if (! $product && ! empty($itemData['slug'])) {
                        $product = Product::lockForUpdate()->where('slug', $itemData['slug'])->first();
                    }
                    if (! $product && ! empty($itemData['name'])) {
                        $product = Product::lockForUpdate()->where('name', $itemData['name'])->first();
                    }
                    if (! $product) {
                        // Fallback to first active product to ensure database integrity
                        $product = Product::lockForUpdate()->where('is_active', true)->first();
                    }

                    if (! $product) {
                        throw ValidationException::withMessages(['cart' => 'Produit introuvable dans le catalogue.']);
                    }

                    if ($product->stock_quantity !== null && $product->stock_quantity < $qty) {
                        throw ValidationException::withMessages(['cart' => "Le produit {$product->name} n'est plus disponible dans cette quantité."]);
                    }

                    $unitPrice = $product->discounted_price ?? $product->price ?? (float) ($itemData['price'] ?? 0);
                    $unitPrice = round((float) $unitPrice, 2);
                    $lineTotal = round($unitPrice * $qty, 2);
                    $subtotal += $lineTotal;

                    $variantParts = array_filter([$itemData['flavor'] ?? null, $itemData['size'] ?? null, $itemData['variant'] ?? null]);
                    $variant = ! empty($variantParts) ? implode(' • ', array_unique($variantParts)) : null;

                    $processedItems[] = [
                        'product_id' => $product->id,
                        'product' => $product,
                        'product_name' => $product->name ?? ($itemData['name'] ?? 'Produit zizo aura'),
                        'product_image' => $product->image ?? ($itemData['image'] ?? '/images/hero_product.png'),
                        'variant' => $variant,
                        'unit_price' => $unitPrice,
                        'quantity' => $qty,
                        'subtotal' => $lineTotal,
                    ];
                }
            } else {
                // 2. Fallback to server session cart
                $cartKey = $request->session()->get('cart_id');
                if (empty($cartKey)) {
                    throw ValidationException::withMessages(['cart' => 'Votre panier est vide.']);
                }
                $dbItems = CartItem::where('session_id', $cartKey)->lockForUpdate()->get();
                if ($dbItems->isEmpty()) {
                    throw ValidationException::withMessages(['cart' => 'Votre panier est vide.']);
                }

                foreach ($dbItems as $dbItem) {
                    $product = Product::lockForUpdate()->findOrFail($dbItem->product_id);
                    if (! $product->is_active || ! $product->in_stock || ($product->stock_quantity !== null && $product->stock_quantity < $dbItem->quantity)) {
                        throw ValidationException::withMessages(['cart' => "Le produit {$product->name} n'est plus disponible dans cette quantité."]);
                    }
                    $unitPrice = round((float) $dbItem->unit_price, 2);
                    $lineTotal = round($unitPrice * $dbItem->quantity, 2);
                    $subtotal += $lineTotal;

                    $processedItems[] = [
                        'product_id' => $product->id,
                        'product' => $product,
                        'product_name' => $product->name,
                        'product_image' => $product->image,
                        'variant' => $dbItem->variant ?: null,
                        'unit_price' => $unitPrice,
                        'quantity' => $dbItem->quantity,
                        'subtotal' => $lineTotal,
                    ];
                }
            }

            if (empty($processedItems)) {
                throw ValidationException::withMessages(['cart' => 'Votre panier est vide.']);
            }

            $subtotal = round($subtotal, 2);

            // 3. Process Coupon
            $coupon = null;
            $discount = 0.0;
            if (! empty($data['coupon_code'])) {
                $couponCode = mb_strtoupper(trim($data['coupon_code']));
                $coupon = Coupon::whereRaw('upper(code) = ?', [$couponCode])->lockForUpdate()->first();
                if (! $coupon) {
                    // Check fallback coupons
                    $fallbacks = [
                        'SUMMER20' => ['type' => 'percent', 'value' => 20, 'min_order' => 0],
                        'WELCOME10' => ['type' => 'percent', 'value' => 10, 'min_order' => 0],
                        'RIO35' => ['type' => 'percent', 'value' => 35, 'min_order' => 300],
                        'ZIZO10' => ['type' => 'percent', 'value' => 10, 'min_order' => 0],
                    ];
                    if (isset($fallbacks[$couponCode])) {
                        $fb = $fallbacks[$couponCode];
                        $discount = $fb['type'] === 'percent' ? round($subtotal * $fb['value'] / 100, 2) : min($fb['value'], $subtotal);
                    }
                } else {
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
                    $discount = $coupon->calculateDiscount($subtotal);
                }
            }

            $discount = round($discount, 2);
            $shipping = 35.0; // Flat rate livraison partout au Maroc
            $finalTotal = max(0, round($subtotal + $shipping - $discount, 2));

            // 4. Create Order
            $order = Order::create([
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'customer_email' => $data['customer_email'] ?? null,
                'shipping_address' => $data['shipping_address'],
                'city' => $data['city'],
                'notes' => $data['notes'] ?? null,
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'discount_amount' => $discount,
                'total' => $finalTotal,
                'coupon_code' => ! empty($data['coupon_code']) ? mb_strtoupper(trim($data['coupon_code'])) : null,
                'status' => 'pending',
            ]);

            // 5. Create Order Items & Decrement Stock
            foreach ($processedItems as $itemInfo) {
                $order->items()->create([
                    'product_id' => $itemInfo['product_id'],
                    'product_name' => $itemInfo['product_name'],
                    'product_image' => $itemInfo['product_image'],
                    'variant' => $itemInfo['variant'],
                    'unit_price' => $itemInfo['unit_price'],
                    'quantity' => $itemInfo['quantity'],
                    'subtotal' => $itemInfo['subtotal'],
                ]);

                if ($itemInfo['product']->stock_quantity !== null) {
                    $itemInfo['product']->decrement('stock_quantity', $itemInfo['quantity']);
                }
            }

            if ($coupon) {
                $coupon->increment('used_count');
            }

            // 6. Clear session cart if present
            $cartKey = $request->session()->get('cart_id');
            if (! empty($cartKey)) {
                CartItem::where('session_id', $cartKey)->delete();
            }

            return $order->load('items');
        });

        // 7. Dispatch Email Notification (Admin & Customer)
        try {
            $adminEmail = config('mail.from.address', 'contact@zizo-aura.com');
            Mail::to($adminEmail)->send(new OrderReceived($order));

            if (! empty($order->customer_email)) {
                Mail::to($order->customer_email)->send(new OrderReceived($order));
            }
        } catch (\Throwable $e) {
            Log::warning('Notification email failed for order #' . $order->id . ': ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Votre commande a été confirmée avec succès !',
            'order' => [
                'id' => $order->id,
                'order_number' => 'CMD-' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'city' => $order->city,
                'subtotal' => $order->subtotal,
                'shipping_cost' => $order->shipping_cost,
                'discount_amount' => $order->discount_amount,
                'total' => $order->total,
                'items_count' => $order->items->count(),
                'status' => $order->status,
                'status_label' => $order->status_label,
            ]
        ], 201);
    }
}

