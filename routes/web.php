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
Route::post('/api/coupon/validate', [CartController::class, 'validateCoupon']);
Route::post('/api/checkout', [CheckoutController::class, 'store'])->middleware('throttle:checkout');

// Aliases
Route::get('/shop', [ShopController::class, 'index']);
Route::get('/shop/product/{slug}', [ShopController::class, 'showProduct']);
Route::get('/shop/{category}', [ShopController::class, 'index']);

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
        Route::post('/produits/{product}/toggle', [ProductController::class, 'toggleStatus'])->name('products.toggle');
        Route::delete('/produits/{product}/force', [ProductController::class, 'forceDestroy'])->name('products.force-destroy');
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
    Route::post('/products/{product}/toggle-status', [AdminApiController::class, 'toggleProductStatus']);
    Route::delete('/products/{product}/force', [AdminApiController::class, 'forceDeleteProduct']);
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
    Route::get('/reviews', [AdminApiController::class, 'reviews']);
    Route::post('/reviews', [AdminApiController::class, 'storeReview']);
    Route::get('/reviews/{review}', [AdminApiController::class, 'review']);
    Route::put('/reviews/{review}', [AdminApiController::class, 'updateReview']);
    Route::post('/reviews/{review}/toggle', [AdminApiController::class, 'toggleReview']);
    Route::delete('/reviews/{review}', [AdminApiController::class, 'deleteReview']);
});
