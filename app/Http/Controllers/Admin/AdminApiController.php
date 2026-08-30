<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminApiController extends Controller
{
    public function dashboard(): JsonResponse
    {
        return response()->json([
            'stats' => [
                'products' => Product::count(),
                'orders' => Order::count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'revenue' => (float) Order::whereNotIn('status', ['cancelled'])->sum('total'),
                'active_coupons' => Coupon::where('is_active', true)->count(),
                'unread_messages' => ContactMessage::where('is_read', false)->count(),
            ],
            'recent_orders' => Order::with('items')->latest()->limit(5)->get(),
        ]);
    }

    public function products(Request $request): JsonResponse
    {
        $query = Product::with(['category', 'sizes', 'flavors'])->withTrashed();
        if ($request->filled('category_id')) $query->where('category_id', $request->integer('category_id'));
        if ($request->filled('search')) $query->where('name', 'ilike', '%'.$request->string('search').'%');
        if ($request->input('status') === 'deleted') $query->onlyTrashed();
        if ($request->input('status') === 'active') $query->whereNull('deleted_at')->where('is_active', true);
        if ($request->input('status') === 'inactive') $query->whereNull('deleted_at')->where('is_active', false);

        return response()->json($query->orderBy('sort_order')->paginate($request->integer('per_page', 25))->withQueryString());
    }

    public function product(Product $product): JsonResponse { return response()->json($product->load(['category', 'sizes', 'flavors'])); }

    public function storeProduct(Request $request): JsonResponse
    {
        $product = DB::transaction(function () use ($request) {
            $data = $this->productData($request);
            $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['name']);
            $product = Product::create($data);
            $this->syncVariants($product, $data);
            return $product;
        });
        return response()->json($product->load(['category', 'sizes', 'flavors']), 201);
    }

    public function updateProduct(Request $request, Product $product): JsonResponse
    {
        DB::transaction(function () use ($request, $product) {
            $data = $this->productData($request, $product->id);
            $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['name']);
            $product->update($data);
            $this->syncVariants($product, $data);
        });
        return response()->json($product->fresh()->load(['category', 'sizes', 'flavors']));
    }

    public function deleteProduct(Product $product): JsonResponse { $product->delete(); return response()->json([], 204); }
    public function restoreProduct(int $id): JsonResponse { $product = Product::withTrashed()->findOrFail($id); $product->restore(); return response()->json($product); }

    public function categories(): JsonResponse { return response()->json(Category::withCount('products')->orderBy('sort_order')->get()); }
    public function storeCategory(Request $request): JsonResponse
    {
        $data = $this->categoryData($request); $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['name']);
        return response()->json(Category::create($data), 201);
    }
    public function updateCategory(Request $request, Category $category): JsonResponse
    {
        $data = $this->categoryData($request, $category->id); $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['name']); $category->update($data);
        return response()->json($category->fresh());
    }
    public function deleteCategory(Category $category): JsonResponse
    {
        abort_if($category->products()->exists(), 422, 'Impossible de supprimer une catégorie contenant des produits.');
        $category->delete(); return response()->json([], 204);
    }

    public function coupons(): JsonResponse { return response()->json(Coupon::latest()->get()); }
    public function storeCoupon(Request $request): JsonResponse { return response()->json(Coupon::create($this->couponData($request)), 201); }
    public function updateCoupon(Request $request, Coupon $coupon): JsonResponse { $coupon->update($this->couponData($request, $coupon->id)); return response()->json($coupon->fresh()); }
    public function toggleCoupon(Coupon $coupon): JsonResponse { $coupon->update(['is_active' => ! $coupon->is_active]); return response()->json($coupon->fresh()); }
    public function deleteCoupon(Coupon $coupon): JsonResponse { $coupon->delete(); return response()->json([], 204); }

    public function orders(Request $request): JsonResponse
    {
        $query = Order::with('items')->latest();
        if ($request->filled('status')) $query->where('status', $request->string('status'));
        if ($request->filled('search')) $query->where(fn ($q) => $q->where('customer_name', 'ilike', '%'.$request->string('search').'%')->orWhere('customer_phone', 'like', '%'.$request->string('search').'%')->orWhere('id', $request->string('search')));
        return response()->json($query->paginate($request->integer('per_page', 20))->withQueryString());
    }
    public function order(Order $order): JsonResponse { return response()->json($order->load('items.product')); }
    public function updateOrderStatus(Request $request, Order $order): JsonResponse
    {
        $data = $request->validate(['status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled']);
        $column = ['confirmed' => 'confirmed_at', 'shipped' => 'shipped_at', 'delivered' => 'delivered_at'][$data['status']] ?? null;
        $order->update(array_filter(['status' => $data['status'], $column => $column && ! $order->$column ? now() : null]));
        return response()->json($order->fresh());
    }

    public function messages(Request $request): JsonResponse { return response()->json(ContactMessage::latest()->paginate($request->integer('per_page', 20))); }
    public function markMessageRead(ContactMessage $contactMessage): JsonResponse { $contactMessage->markRead(); return response()->json($contactMessage->fresh()); }

    private function productData(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'category_id' => 'required|exists:categories,id', 'name' => 'required|string|max:255', 'subtitle' => 'nullable|string|max:500',
            'slug' => ['nullable', 'string', 'max:255', "unique:products,slug,{$id}"], 'description' => 'nullable|string', 'ingredients' => 'nullable|string', 'olfactory' => 'nullable|string', 'usage' => 'nullable|string',
            'price' => 'required|numeric|min:0.01', 'discounted_price' => 'nullable|numeric|min:0|lt:price', 'image' => 'required|string|max:500', 'gallery' => 'nullable|array', 'gallery.*' => 'string|max:500',
            'badge' => 'nullable|string|max:100', 'badge_color' => 'nullable|string|max:100', 'rating' => 'nullable|numeric|min:0|max:5', 'review_count' => 'nullable|integer|min:0',
            'is_new' => 'boolean', 'is_bestseller' => 'boolean', 'in_stock' => 'boolean', 'is_active' => 'boolean', 'stock_quantity' => 'nullable|integer|min:0', 'has_sizes' => 'boolean', 'has_flavors' => 'boolean', 'sort_order' => 'integer',
            'sizes' => 'nullable|array', 'sizes.*.label' => 'required_with:sizes|string|max:100', 'sizes.*.price' => 'nullable|numeric|min:0', 'sizes.*.in_stock' => 'boolean',
            'flavors' => 'nullable|array', 'flavors.*.label' => 'required_with:flavors|string|max:100', 'flavors.*.color_hex' => 'nullable|regex:/^#[A-Fa-f0-9]{6}$/', 'flavors.*.in_stock' => 'boolean',
        ]);
    }
    private function syncVariants(Product $product, array $data): void
    {
        if (array_key_exists('sizes', $data)) { $product->sizes()->delete(); foreach ($data['sizes'] ?? [] as $i => $size) $product->sizes()->create(['label' => $size['label'], 'price' => $size['price'] ?? null, 'in_stock' => $size['in_stock'] ?? true, 'sort_order' => $i]); }
        if (array_key_exists('flavors', $data)) { $product->flavors()->delete(); foreach ($data['flavors'] ?? [] as $i => $flavor) $product->flavors()->create(['label' => $flavor['label'], 'color_hex' => $flavor['color_hex'] ?? null, 'in_stock' => $flavor['in_stock'] ?? true, 'sort_order' => $i]); }
    }
    private function categoryData(Request $request, ?int $id = null): array { return $request->validate(['name' => 'required|string|max:255', 'slug' => ["nullable", 'string', 'max:255', "unique:categories,slug,{$id}"], 'image' => 'nullable|string|max:500', 'description' => 'nullable|string', 'is_active' => 'boolean', 'sort_order' => 'integer']); }
    private function couponData(Request $request, ?int $id = null): array { return $request->validate(['code' => ["required", 'string', 'max:50', "unique:coupons,code,{$id}"], 'type' => 'required|in:percent,fixed', 'value' => 'required|numeric|min:0.01', 'min_order_amount' => 'nullable|numeric|min:0', 'max_uses' => 'nullable|integer|min:1', 'is_active' => 'boolean', 'expires_at' => 'nullable|date']); }
}
