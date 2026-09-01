<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductDuplicateTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): self
    {
        return $this->withSession(['admin_authenticated' => true]);
    }

    public function test_can_duplicate_product_via_api(): void
    {
        $category = Category::create([
            'name' => 'Soins Solaires',
            'slug' => 'soins-solaires',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Brazilian Bum Bum Cream',
            'slug' => 'brazilian-bum-bum-cream',
            'price' => 350,
            'discounted_price' => 280,
            'image' => '/images/bum_bum_cream.jpg',
            'description' => 'Crème brésilienne iconique',
            'in_stock' => true,
            'is_active' => true,
        ]);

        $product->sizes()->create(['label' => '75ml', 'price' => 280, 'in_stock' => true, 'sort_order' => 0]);
        $product->sizes()->create(['label' => '240ml', 'price' => 490, 'in_stock' => true, 'sort_order' => 1]);

        // Duplicate 1
        $res1 = $this->actingAsAdmin()->postJson("/api/admin/products/{$product->id}/duplicate");
        $res1->assertStatus(201);
        $res1->assertJsonFragment([
            'name' => 'Brazilian Bum Bum Cream (1)',
            'slug' => 'brazilian-bum-bum-cream-1',
        ]);
        $this->assertCount(2, $res1->json('sizes'));

        // Duplicate 2 (from original)
        $res2 = $this->actingAsAdmin()->postJson("/api/admin/products/{$product->id}/duplicate");
        $res2->assertStatus(201);
        $res2->assertJsonFragment([
            'name' => 'Brazilian Bum Bum Cream (2)',
            'slug' => 'brazilian-bum-bum-cream-2',
        ]);

        // Duplicate from a duplicated product (Brazilian Bum Bum Cream (1))
        $dup1Id = $res1->json('id');
        $res3 = $this->actingAsAdmin()->postJson("/api/admin/products/{$dup1Id}/duplicate");
        $res3->assertStatus(201);
        $res3->assertJsonFragment([
            'name' => 'Brazilian Bum Bum Cream (3)',
            'slug' => 'brazilian-bum-bum-cream-3',
        ]);
    }
}
