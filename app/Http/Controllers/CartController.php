<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json($this->summary($request));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
            'variant' => ['nullable', 'string', 'max:255'],
            'size_id' => ['nullable', 'integer', 'exists:product_sizes,id'],
        ]);

        $product = Product::active()->inStock()->findOrFail($data['product_id']);
        $price = (float) $product->effective_price;
        $variant = trim($data['variant'] ?? '');

        if (! empty($data['size_id'])) {
            $size = ProductSize::where('product_id', $product->id)->where('in_stock', true)->findOrFail($data['size_id']);
            $price = $size->price === null ? $price : (float) $size->price;
            $variant = $variant ?: $size->label;
        }

        $item = CartItem::firstOrNew([
            'session_id' => $this->cartKey($request),
            'product_id' => $product->id,
            'variant' => $variant,
        ]);
        $item->quantity = min(($item->exists ? $item->quantity : 0) + $data['quantity'], 20);
        $item->unit_price = $price;
        $item->save();

        return response()->json($this->summary($request), 201);
    }

    public function update(Request $request, CartItem $cartItem): JsonResponse
    {
        $this->ensureOwner($request, $cartItem);
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:20']]);
        $cartItem->update($data);

        return response()->json($this->summary($request));
    }

    public function destroy(Request $request, CartItem $cartItem): JsonResponse
    {
        $this->ensureOwner($request, $cartItem);
        $cartItem->delete();

        return response()->json($this->summary($request));
    }

    public function coupon(Request $request): JsonResponse
    {
        $data = $request->validate(['code' => ['required', 'string', 'max:50']]);
        $summary = $this->summary($request);
        $coupon = Coupon::whereRaw('upper(code) = ?', [mb_strtoupper(trim($data['code']))])->first();

        if (! $coupon || ! $coupon->appliesTo((float) $summary['subtotal'])) {
            return response()->json(['message' => 'Code promo invalide ou non applicable.'], 422);
        }

        return response()->json($this->summary($request, $coupon));
    }

    private function ensureOwner(Request $request, CartItem $cartItem): void
    {
        abort_unless($cartItem->session_id === $this->cartKey($request), 404);
    }

    private function summary(Request $request, ?Coupon $coupon = null): array
    {
        $items = CartItem::with('product.category')
            ->where('session_id', $this->cartKey($request))
            ->get();
        $subtotal = round($items->sum(fn (CartItem $item) => (float) $item->unit_price * $item->quantity), 2);
        $discount = $coupon?->calculateDiscount($subtotal) ?? 0.0;
        $shipping = $subtotal > 0 && $subtotal < 500 ? 35.0 : 0.0;

        return [
            'items' => $items->map(fn (CartItem $item) => [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'image' => $item->product->image,
                'variant' => $item->variant ?: null,
                'quantity' => $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'line_total' => round((float) $item->unit_price * $item->quantity, 2),
            ])->values(),
            'count' => $items->sum('quantity'),
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping,
            'discount_amount' => $discount,
            'coupon' => $coupon?->code,
            'total' => max(0, round($subtotal + $shipping - $discount, 2)),
        ];
    }

    private function cartKey(Request $request): string
    {
        return $request->session()->get('cart_id') ?? tap((string) Str::uuid(), fn (string $key) => $request->session()->put('cart_id', $key));
    }
}
