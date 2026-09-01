<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CatalogApiController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\AdminApiController;

Route::get('/', [BrandController::class, 'show'])->name('home');
Route::get('/marques/de-a-a-z/sol-de-janeiro-janei', [BrandController::class, 'show'])->name('brand.show');

// Boutique / Shop Routes
Route::get('/boutique', [ShopController::class, 'index'])->name('shop.index');
Route::get('/boutique/produit/{slug}', [ShopController::class, 'showProduct'])->name('shop.product');
Route::get('/boutique/{category}', [ShopController::class, 'index'])->name('shop.category');
Route::get('/api/search', [ShopController::class, 'apiSearch'])->name('api.search');
Route::get('/api/catalog/categories', fn () => response()->json(['data' => ShopController::catalogCategories()]));
Route::get('/api/catalog/products', [CatalogApiController::class, 'index']);
Route::get('/api/catalog/products/{slug}', [CatalogApiController::class, 'show']);
Route::get('/api/cart', [CartController::class, 'index']);
Route::post('/api/cart/items', [CartController::class, 'store']);
Route::patch('/api/cart/items/{cartItem}', [CartController::class, 'update']);
Route::delete('/api/cart/items/{cartItem}', [CartController::class, 'destroy']);
Route::post('/api/cart/coupon', [CartController::class, 'coupon']);
Route::post('/api/coupon/validate', function (\Illuminate\Http\Request $request) {
    $data = $request->validate([
        'code' => ['required', 'string', 'max:50'],
        'subtotal' => ['nullable', 'numeric', 'min:0'],
    ]);

    $rawCode = trim($data['code']);
    $code = mb_strtoupper($rawCode);
    $subtotal = (float) ($data['subtotal'] ?? 0);

    // Check in database first
    $coupon = \App\Models\Coupon::whereRaw('upper(code) = ?', [$code])->first();

    // Fallback static coupons if DB not available or not yet seeded
    if (! $coupon) {
        $fallbacks = [
            'SUMMER20' => ['type' => 'percent', 'value' => 20, 'min_order' => 0],
            'WELCOME10' => ['type' => 'percent', 'value' => 10, 'min_order' => 0],
            'RIO35' => ['type' => 'percent', 'value' => 35, 'min_order' => 300],
            'ZIZO10' => ['type' => 'percent', 'value' => 10, 'min_order' => 0],
        ];

        if (isset($fallbacks[$code])) {
            $fb = $fallbacks[$code];
            if ($subtotal > 0 && $subtotal < $fb['min_order']) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Montant minimum d\'achat de ' . $fb['min_order'] . ' DH requis.',
                ], 422);
            }
            $discount = $fb['type'] === 'percent' ? round($subtotal * $fb['value'] / 100, 2) : min($fb['value'], $subtotal);
            return response()->json([
                'valid' => true,
                'code' => $code,
                'type' => $fb['type'],
                'value' => (float) $fb['value'],
                'label' => $fb['type'] === 'percent' ? '-' . (int) $fb['value'] . '%' : '-' . (int) $fb['value'] . ' DH',
                'discount_amount' => $discount,
                'message' => 'Code promo ' . $code . ' appliqué avec succès !',
            ]);
        }

        return response()->json(['valid' => false, 'message' => 'Code promo invalide.'], 422);
    }

    if (! $coupon->is_active) {
        return response()->json(['valid' => false, 'message' => 'Ce code promo est inactif.'], 422);
    }

    if ($coupon->isExpired()) {
        return response()->json(['valid' => false, 'message' => 'Ce code promo a expiré.'], 422);
    }

    if ($coupon->isExhausted()) {
        return response()->json(['valid' => false, 'message' => 'Ce code promo a atteint sa limite d\'utilisation.'], 422);
    }

    if ($subtotal > 0 && $subtotal < (float) $coupon->min_order_amount) {
        return response()->json([
            'valid' => false,
            'message' => 'Montant minimum d\'achat de ' . (int) $coupon->min_order_amount . ' DH requis.',
        ], 422);
    }

    $discount = $subtotal > 0 ? $coupon->calculateDiscount($subtotal) : 0;

    return response()->json([
        'valid' => true,
        'code' => $coupon->code,
        'type' => $coupon->type,
        'value' => (float) $coupon->value,
        'label' => $coupon->type === 'percent' ? '-' . (int) $coupon->value . '%' : '-' . (int) $coupon->value . ' DH',
        'discount_amount' => $discount,
        'message' => 'Code promo ' . $coupon->code . ' appliqué avec succès !',
    ]);
});
Route::post('/api/checkout', [CheckoutController::class, 'store'])->middleware('throttle:checkout');

// Aliases
Route::get('/shop', [ShopController::class, 'index']);
Route::get('/shop/product/{slug}', [ShopController::class, 'showProduct']);

// Contact Routes
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// ─── Admin ────────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    // Auth (public)
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:admin-login')->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protected routes
    Route::middleware('admin.auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Products
        Route::post('/produits/restaurer/{id}', [ProductController::class, 'restore'])->name('products.restore');
        Route::post('/produits/{product}/dupliquer', [ProductController::class, 'duplicate'])->name('products.duplicate');
        Route::resource('produits', ProductController::class)->except(['show'])->parameters(['produits' => 'product'])->names('products');

        // Categories
        Route::resource('categories', CategoryController::class)->except(['show'])->names('categories');

        // Coupons
        Route::post('/coupons/{coupon}/toggle', [CouponController::class, 'toggle'])->name('coupons.toggle');
        Route::resource('coupons', CouponController::class)->except(['show'])->names('coupons');

        // Orders
        Route::put('/commandes/{order}/statut', [OrderController::class, 'updateStatus'])->name('orders.status');
        Route::resource('commandes', OrderController::class)
            ->only(['index', 'show'])
            ->parameters(['commandes' => 'order'])
            ->names('orders');
    });
});

Route::prefix('api/admin')->middleware('admin.auth')->group(function () {
    Route::get('/dashboard', [AdminApiController::class, 'dashboard']);
    Route::get('/products', [AdminApiController::class, 'products']);
    Route::post('/products', [AdminApiController::class, 'storeProduct']);
    Route::get('/products/{product}', [AdminApiController::class, 'product']);
    Route::put('/products/{product}', [AdminApiController::class, 'updateProduct']);
    Route::post('/products/{product}/duplicate', [AdminApiController::class, 'duplicateProduct']);
    Route::delete('/products/{product}', [AdminApiController::class, 'deleteProduct']);
    Route::post('/products/{id}/restore', [AdminApiController::class, 'restoreProduct']);
    Route::get('/categories', [AdminApiController::class, 'categories']);
    Route::post('/categories', [AdminApiController::class, 'storeCategory']);
    Route::put('/categories/{category}', [AdminApiController::class, 'updateCategory']);
    Route::delete('/categories/{category}', [AdminApiController::class, 'deleteCategory']);
    Route::get('/coupons', [AdminApiController::class, 'coupons']);
    Route::post('/coupons', [AdminApiController::class, 'storeCoupon']);
    Route::put('/coupons/{coupon}', [AdminApiController::class, 'updateCoupon']);
    Route::post('/coupons/{coupon}/toggle', [AdminApiController::class, 'toggleCoupon']);
    Route::delete('/coupons/{coupon}', [AdminApiController::class, 'deleteCoupon']);
    Route::get('/orders', [AdminApiController::class, 'orders']);
    Route::get('/orders/{order}', [AdminApiController::class, 'order']);
    Route::patch('/orders/{order}/status', [AdminApiController::class, 'updateOrderStatus']);
    Route::get('/messages', [AdminApiController::class, 'messages']);
    Route::patch('/messages/{contactMessage}/read', [AdminApiController::class, 'markMessageRead']);
    Route::delete('/messages/{contactMessage}', [AdminApiController::class, 'deleteMessage']);
});
