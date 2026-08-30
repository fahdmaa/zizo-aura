<?php

namespace Database\Seeders;

use App\Http\Controllers\ShopController;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogSeeder extends Seeder
{
    /** Import the legacy visual catalog into the admin-managed database. */
    public function run(): void
    {
        $legacyProducts = ShopController::getProducts();
        $now = now();
        $categories = collect($legacyProducts)->unique('category')->values();

        DB::table('categories')->upsert(
            $categories->map(fn (array $product, int $index) => [
                'name' => $product['category_label'], 'slug' => $product['category'], 'is_active' => true,
                'sort_order' => $index, 'created_at' => $now, 'updated_at' => $now,
            ])->all(),
            ['slug'], ['name', 'is_active', 'sort_order', 'updated_at']
        );
        $categoryIds = DB::table('categories')->whereIn('slug', $categories->pluck('category'))->pluck('id', 'slug');

        DB::table('products')->upsert(
            collect($legacyProducts)->map(fn (array $legacy, int $position) => [
                'category_id' => $categoryIds[$legacy['category']], 'name' => $legacy['name'], 'subtitle' => $legacy['subtitle'], 'slug' => $legacy['slug'],
                'description' => $legacy['description'], 'ingredients' => $legacy['ingredients'], 'olfactory' => $legacy['olfactory'], 'usage' => $legacy['usage'],
                'price' => $legacy['original_price'], 'discounted_price' => $legacy['raw_price'] < $legacy['original_price'] ? $legacy['raw_price'] : null,
                'image' => $legacy['image'], 'gallery' => null, 'badge' => $legacy['badge'], 'badge_color' => $legacy['badge_color'], 'rating' => $legacy['rating'], 'review_count' => $legacy['review_count'],
                'is_new' => false, 'is_bestseller' => false, 'in_stock' => true, 'is_active' => true, 'stock_quantity' => null,
                'has_sizes' => ! empty($legacy['sizes']), 'has_flavors' => ! empty($legacy['flavors']), 'sort_order' => $position, 'created_at' => $now, 'updated_at' => $now, 'deleted_at' => null,
            ])->all(),
            ['slug'], ['category_id', 'name', 'subtitle', 'description', 'ingredients', 'olfactory', 'usage', 'price', 'discounted_price', 'image', 'gallery', 'badge', 'badge_color', 'rating', 'review_count', 'is_new', 'is_bestseller', 'in_stock', 'is_active', 'stock_quantity', 'has_sizes', 'has_flavors', 'sort_order', 'updated_at', 'deleted_at']
        );

        $productIds = DB::table('products')->whereIn('slug', collect($legacyProducts)->pluck('slug'))->pluck('id', 'slug');
        DB::table('product_sizes')->whereIn('product_id', $productIds->values())->delete();
        DB::table('product_flavors')->whereIn('product_id', $productIds->values())->delete();
        $sizes = []; $flavors = [];
        foreach ($legacyProducts as $legacy) {
            foreach ($legacy['sizes'] as $index => $label) $sizes[] = ['product_id' => $productIds[$legacy['slug']], 'label' => $label, 'price' => null, 'in_stock' => true, 'sort_order' => $index, 'created_at' => $now, 'updated_at' => $now];
            foreach ($legacy['flavors'] as $index => $flavor) $flavors[] = ['product_id' => $productIds[$legacy['slug']], 'label' => $flavor['name'], 'color_hex' => $flavor['color'], 'in_stock' => true, 'sort_order' => $index, 'created_at' => $now, 'updated_at' => $now];
        }
        if ($sizes) DB::table('product_sizes')->insert($sizes);
        if ($flavors) DB::table('product_flavors')->insert($flavors);
    }
}
