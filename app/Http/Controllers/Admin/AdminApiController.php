<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
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
                'total_reviews' => Review::count(),
                'visible_reviews' => Review::where('is_visible', true)->count(),
            ],
            'recent_orders' => Order::with('items')->latest()->limit(5)->get(),
        ]);
    }

    public function products(Request $request): JsonResponse
    {
        $query = Product::select([
            'id', 'category_id', 'name', 'subtitle', 'slug', 'price', 'discounted_price',
            'image', 'badge', 'badge_color', 'in_stock', 'stock_quantity', 'is_active',
            'is_bestseller', 'is_new', 'sort_order', 'deleted_at', 'created_at', 'updated_at'
        ])->with(['category:id,name,slug'])->withTrashed();

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
            $data['image'] = \App\Services\ImageOptimizer::optimizeBase64($data['image']);
            if (isset($data['gallery'])) {
                $data['gallery'] = \App\Services\ImageOptimizer::optimizeGallery($data['gallery']);
            }
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
            if (isset($data['image'])) {
                $data['image'] = \App\Services\ImageOptimizer::optimizeBase64($data['image']);
            }
            if (isset($data['gallery'])) {
                $data['gallery'] = \App\Services\ImageOptimizer::optimizeGallery($data['gallery']);
            }
            $product->update($data);
            $this->syncVariants($product, $data);
        });
        return response()->json($product->fresh()->load(['category', 'sizes', 'flavors']));
    }

    public function duplicateProduct(Product $product): JsonResponse
    {
        $product->load(['sizes', 'flavors']);

        $duplicated = DB::transaction(function () use ($product) {
            $baseName = preg_replace('/\s*\(\d+\)$/', '', $product->name);
            $i = 1;
            $newName = "{$baseName} (1)";
            $newSlug = Str::slug($newName);

            do {
                $candidateName = "{$baseName} ({$i})";
                $candidateSlug = Str::slug($candidateName);
                $exists = Product::withTrashed()->where(function ($q) use ($candidateName, $candidateSlug) {
                    $q->where('name', $candidateName)->orWhere('slug', $candidateSlug);
                })->exists();

                if (! $exists) {
                    $newName = $candidateName;
                    $newSlug = $candidateSlug;
                    break;
                }
                $i++;
            } while ($i < 1000);

            $data = $product->only([
                'category_id',
                'subtitle',
                'description',
                'ingredients',
                'olfactory',
                'usage',
                'price',
                'discounted_price',
                'image',
                'gallery',
                'badge',
                'badge_color',
                'rating',
                'review_count',
                'is_new',
                'is_bestseller',
                'in_stock',
                'is_active',
                'stock_quantity',
                'has_sizes',
                'has_flavors',
                'sort_order',
            ]);

            $data['name'] = $newName;
            $data['slug'] = $newSlug;
            $data['sort_order'] = ($product->sort_order ?? 0) + 1;

            $newProduct = Product::create($data);

            foreach ($product->sizes as $size) {
                $newProduct->sizes()->create([
                    'label' => $size->label,
                    'price' => $size->price,
                    'in_stock' => $size->in_stock,
                    'sort_order' => $size->sort_order,
                ]);
            }

            foreach ($product->flavors as $flavor) {
                $newProduct->flavors()->create([
                    'label' => $flavor->label,
                    'color_hex' => $flavor->color_hex,
                    'in_stock' => $flavor->in_stock,
                    'sort_order' => $flavor->sort_order,
                ]);
            }

            return $newProduct;
        });

        return response()->json($duplicated->fresh()->load(['category', 'sizes', 'flavors']), 201);
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

    public function messages(Request $request): JsonResponse
    {
        $paginator = ContactMessage::latest()->paginate($request->integer('per_page', 20));
        $data = $paginator->toArray();
        $data['unread_messages'] = ContactMessage::where('is_read', false)->count();
        return response()->json($data);
    }
    public function markMessageRead(ContactMessage $contactMessage): JsonResponse
    {
        $contactMessage->markRead();
        $fresh = $contactMessage->fresh();
        $fresh->unread_messages = ContactMessage::where('is_read', false)->count();
        return response()->json($fresh);
    }
    public function deleteMessage(ContactMessage $contactMessage): JsonResponse
    {
        $contactMessage->delete();
        return response()->json([
            'success' => true,
            'unread_messages' => ContactMessage::where('is_read', false)->count(),
        ]);
    }

    public function reviews(Request $request): JsonResponse
    {
        $query = Review::query();

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('author_name', 'ilike', "%{$search}%")
                  ->orWhere('author_role', 'ilike', "%{$search}%")
                  ->orWhere('comment', 'ilike', "%{$search}%");
            });
        }

        if ($request->input('status') === 'visible') {
            $query->where('is_visible', true);
        } elseif ($request->input('status') === 'hidden') {
            $query->where('is_visible', false);
        }

        $reviews = $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->get();
        return response()->json($reviews);
    }

    public function review(Review $review): JsonResponse
    {
        return response()->json($review);
    }

    public function storeReview(Request $request): JsonResponse
    {
        $data = $this->reviewData($request);
        if (!empty($data['avatar'])) {
            $data['avatar'] = \App\Services\ImageOptimizer::optimizeBase64($data['avatar'], 400, 85);
        }
        $review = Review::create($data);
        return response()->json($review, 201);
    }

    public function updateReview(Request $request, Review $review): JsonResponse
    {
        $data = $this->reviewData($request, $review->id);
        if (!empty($data['avatar'])) {
            $data['avatar'] = \App\Services\ImageOptimizer::optimizeBase64($data['avatar'], 400, 85);
        }
        $review->update($data);
        return response()->json($review->fresh());
    }

    public function toggleReview(Review $review): JsonResponse
    {
        $review->update(['is_visible' => ! $review->is_visible]);
        return response()->json($review->fresh());
    }

    public function deleteReview(Review $review): JsonResponse
    {
        $review->delete();
        return response()->json([], 204);
    }

    private function reviewData(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'author_name' => 'required|string|max:255',
            'author_role' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:2000',
            'avatar' => 'nullable|string',
            'badge' => 'nullable|string|max:100',
            'ring_color' => 'nullable|string|max:50',
            'is_visible' => 'boolean',
            'sort_order' => 'integer',
        ]);
    }

    private function productData(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'category_id' => 'required|exists:categories,id', 'name' => 'required|string|max:255', 'subtitle' => 'nullable|string|max:500',
            'slug' => ['nullable', 'string', 'max:255', "unique:products,slug,{$id}"], 'description' => 'nullable|string', 'ingredients' => 'nullable|string', 'olfactory' => 'nullable|string', 'usage' => 'nullable|string',
            'price' => 'required|numeric|min:0.01', 'discounted_price' => 'nullable|numeric|min:0|lt:price', 'image' => 'required|string', 'gallery' => 'nullable|array', 'gallery.*' => 'string',
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
    private function categoryData(Request $request, ?int $id = null): array { return $request->validate(['name' => 'required|string|max:255', 'slug' => ["nullable", 'string', 'max:255', "unique:categories,slug,{$id}"], 'image' => 'nullable|string', 'description' => 'nullable|string', 'is_active' => 'boolean', 'sort_order' => 'integer']); }
    private function couponData(Request $request, ?int $id = null): array { return $request->validate(['code' => ["required", 'string', 'max:50', "unique:coupons,code,{$id}"], 'type' => 'required|in:percent,fixed', 'value' => 'required|numeric|min:0.01', 'min_order_amount' => 'nullable|numeric|min:0', 'max_uses' => 'nullable|integer|min:1', 'is_active' => 'boolean', 'expires_at' => 'nullable|date']); }
}
