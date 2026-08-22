<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ContactController;

Route::get('/', [BrandController::class, 'show'])->name('home');
Route::get('/marques/de-a-a-z/sol-de-janeiro-janei', [BrandController::class, 'show'])->name('brand.show');

// Boutique / Shop Routes
Route::get('/boutique', [ShopController::class, 'index'])->name('shop.index');
Route::get('/boutique/produit/{slug}', [ShopController::class, 'showProduct'])->name('shop.product');
Route::get('/boutique/{category}', [ShopController::class, 'index'])->name('shop.category');
Route::get('/api/search', [ShopController::class, 'apiSearch'])->name('api.search');

// Aliases
Route::get('/shop', [ShopController::class, 'index']);
Route::get('/shop/product/{slug}', [ShopController::class, 'showProduct']);

// Contact Routes
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
