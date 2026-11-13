<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductStockAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_marked_out_of_stock_is_automatically_inactive(): void
    {
        $category = Category::create(['name' => 'Soins', 'slug' => 'soins', 'is_active' => true]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Crème Hydratante',
            'slug' => 'creme-hydratante',
            'price' => 150,
            'image' => '/creme.jpg',
            'in_stock' => false,
            'is_active' => true,
        ]);

        $this->assertFalse($product->in_stock);
        $this->assertFalse($product->is_active);
    }

    public function test_product_with_zero_stock_quantity_becomes_inactive(): void
    {
        $category = Category::create(['name' => 'Soins', 'slug' => 'soins', 'is_active' => true]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Sérum Nuit',
            'slug' => 'serum-nuit',
            'price' => 220,
            'image' => '/serum-nuit.jpg',
            'in_stock' => true,
            'is_active' => true,
            'stock_quantity' => 0,
        ]);

        $this->assertFalse($product->in_stock);
        $this->assertFalse($product->is_active);
    }

    public function test_cannot_toggle_active_status_if_product_out_of_stock(): void
    {
        $category = Category::create(['name' => 'Soins', 'slug' => 'soins', 'is_active' => true]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Huile Démaquillante',
            'slug' => 'huile-demaquillante',
            'price' => 190,
            'image' => '/huile.jpg',
            'in_stock' => false,
        ]);

        $response = $this->withSession(['admin_authenticated' => true])
            ->postJson("/api/admin/products/{$product->id}/toggle-status");

        $response->assertStatus(422);
        $product->refresh();
        $this->assertFalse($product->is_active);
    }
}
