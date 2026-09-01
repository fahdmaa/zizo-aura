<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminApiFullTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin()
    {
        return $this->withSession(['admin_authenticated' => true]);
    }

    public function test_dashboard_endpoint_returns_expected_kpis(): void
    {
        $category = Category::create(['name' => 'Soins', 'slug' => 'soins']);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Produit Test',
            'slug' => 'produit-test',
            'price' => 250,
            'image' => '/test.jpg',
            'is_active' => true,
        ]);
        Coupon::create([
            'code' => 'PROMO10',
            'type' => 'percent',
            'value' => 10,
            'is_active' => true,
        ]);
        Order::create([
            'customer_name' => 'Sara',
            'customer_phone' => '0611223344',
            'shipping_address' => '20 Bd Zerktouni',
            'city' => 'Casablanca',
            'subtotal' => 500,
            'shipping_cost' => 0,
            'discount_amount' => 50,
            'total' => 450,
            'status' => 'pending',
        ]);
        ContactMessage::create([
            'name' => 'Karim',
            'email' => 'karim@example.com',
            'subject' => 'Disponibilité',
            'message' => 'Est-ce disponible ?',
            'is_read' => false,
        ]);

        $response = $this->actingAsAdmin()->getJson('/api/admin/dashboard');

        $response->assertOk()
            ->assertJsonStructure([
                'stats' => ['products', 'orders', 'pending_orders', 'revenue', 'active_coupons', 'unread_messages'],
                'recent_orders',
            ])
            ->assertJsonPath('stats.products', 1)
            ->assertJsonPath('stats.orders', 1)
            ->assertJsonPath('stats.pending_orders', 1)
            ->assertJsonPath('stats.active_coupons', 1)
            ->assertJsonPath('stats.unread_messages', 1)
            ->assertJsonPath('stats.revenue', 450);
    }

    public function test_product_crud_and_restore(): void
    {
        $category = Category::create(['name' => 'Parfums', 'slug' => 'parfums']);

        // 1. Create product with sizes and flavors
        $createRes = $this->actingAsAdmin()->postJson('/api/admin/products', [
            'category_id' => $category->id,
            'name' => 'Brume Cheirosa 68',
            'subtitle' => 'Brume florale',
            'price' => 350,
            'discounted_price' => 280,
            'image' => 'https://example.com/image.jpg',
            'gallery' => ['https://example.com/g1.jpg'],
            'is_new' => true,
            'is_bestseller' => true,
            'in_stock' => true,
            'is_active' => true,
            'has_sizes' => true,
            'has_flavors' => true,
            'sizes' => [
                ['label' => '90ml', 'price' => 280, 'in_stock' => true],
                ['label' => '240ml', 'price' => 450, 'in_stock' => true],
            ],
            'flavors' => [
                ['label' => 'Cheirosa 68', 'color_hex' => '#ff1b7a', 'in_stock' => true],
            ],
        ]);

        $createRes->assertCreated();
        $productId = $createRes->json('id');
        $this->assertDatabaseHas('products', ['id' => $productId, 'name' => 'Brume Cheirosa 68']);

        // 2. Fetch product
        $getRes = $this->actingAsAdmin()->getJson("/api/admin/products/{$productId}");
        $getRes->assertOk()->assertJsonPath('name', 'Brume Cheirosa 68');

        // 3. Update product
        $updateRes = $this->actingAsAdmin()->putJson("/api/admin/products/{$productId}", [
            'category_id' => $category->id,
            'name' => 'Brume Cheirosa 68 Modifiée',
            'price' => 360,
            'discounted_price' => 300,
            'image' => 'https://example.com/image.jpg',
            'in_stock' => true,
            'is_active' => true,
        ]);
        $updateRes->assertOk()->assertJsonPath('name', 'Brume Cheirosa 68 Modifiée');

        // 4. Soft Delete product
        $delRes = $this->actingAsAdmin()->deleteJson("/api/admin/products/{$productId}");
        $delRes->assertNoContent();
        $this->assertSoftDeleted('products', ['id' => $productId]);

        // 5. Restore product
        $restoreRes = $this->actingAsAdmin()->postJson("/api/admin/products/{$productId}/restore");
        $restoreRes->assertOk();
        $this->assertNotSoftDeleted('products', ['id' => $productId]);
    }

    public function test_categories_crud(): void
    {
        // 1. Create category
        $res = $this->actingAsAdmin()->postJson('/api/admin/categories', [
            'name' => 'Coffrets Cadeaux',
            'slug' => 'coffrets-cadeaux',
            'description' => 'Idéal pour offrir',
            'is_active' => true,
            'sort_order' => 1,
        ]);
        $res->assertCreated();
        $categoryId = $res->json('id');

        // 2. List categories
        $listRes = $this->actingAsAdmin()->getJson('/api/admin/categories');
        $listRes->assertOk()->assertJsonCount(1);

        // 3. Update category
        $upRes = $this->actingAsAdmin()->putJson("/api/admin/categories/{$categoryId}", [
            'name' => 'Coffrets Exclusifs',
            'is_active' => true,
        ]);
        $upRes->assertOk()->assertJsonPath('name', 'Coffrets Exclusifs');

        // 4. Delete category
        $delRes = $this->actingAsAdmin()->deleteJson("/api/admin/categories/{$categoryId}");
        $delRes->assertNoContent();
        $this->assertDatabaseMissing('categories', ['id' => $categoryId]);
    }

    public function test_coupon_crud_and_toggle(): void
    {
        // 1. Create coupon
        $createRes = $this->actingAsAdmin()->postJson('/api/admin/coupons', [
            'code' => 'VIP20',
            'type' => 'percent',
            'value' => 20,
            'min_order_amount' => 200,
            'max_uses' => 50,
            'is_active' => true,
        ]);
        $createRes->assertCreated();
        $couponId = $createRes->json('id');

        // 2. Toggle coupon
        $toggleRes = $this->actingAsAdmin()->postJson("/api/admin/coupons/{$couponId}/toggle");
        $toggleRes->assertOk()->assertJsonPath('is_active', false);

        // 3. Update coupon
        $updateRes = $this->actingAsAdmin()->putJson("/api/admin/coupons/{$couponId}", [
            'code' => 'VIP20',
            'type' => 'fixed',
            'value' => 50,
            'is_active' => true,
        ]);
        $updateRes->assertOk()->assertJsonPath('type', 'fixed')->assertJsonPath('value', '50.00');

        // 4. Delete coupon
        $delRes = $this->actingAsAdmin()->deleteJson("/api/admin/coupons/{$couponId}");
        $delRes->assertNoContent();
        $this->assertDatabaseMissing('coupons', ['id' => $couponId]);
    }

    public function test_order_status_update(): void
    {
        $order = Order::create([
            'customer_name' => 'Yassine',
            'customer_phone' => '0622334455',
            'shipping_address' => '50 Av Mohammed V',
            'city' => 'Rabat',
            'subtotal' => 350,
            'shipping_cost' => 35,
            'discount_amount' => 0,
            'total' => 385,
            'status' => 'pending',
        ]);

        $res = $this->actingAsAdmin()->patchJson("/api/admin/orders/{$order->id}/status", [
            'status' => 'confirmed',
        ]);

        $res->assertOk()->assertJsonPath('status', 'confirmed');
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'confirmed']);
    }

    public function test_contact_message_mark_as_read(): void
    {
        $msg = ContactMessage::create([
            'name' => 'Leila',
            'email' => 'leila@example.com',
            'subject' => 'Livraison',
            'message' => 'Quels sont les délais ?',
            'is_read' => false,
        ]);

        $res = $this->actingAsAdmin()->patchJson("/api/admin/messages/{$msg->id}/read");
        $res->assertOk()->assertJsonPath('is_read', true);
        $this->assertDatabaseHas('contact_messages', ['id' => $msg->id, 'is_read' => true]);
    }

    public function test_contact_message_delete(): void
    {
        $msg = ContactMessage::create([
            'name' => 'Sara',
            'email' => 'sara@example.com',
            'subject' => 'Question',
            'message' => 'Disponibilité ?',
            'is_read' => false,
        ]);

        $res = $this->actingAsAdmin()->deleteJson("/api/admin/messages/{$msg->id}");
        $res->assertOk()->assertJsonPath('success', true);
        $this->assertDatabaseMissing('contact_messages', ['id' => $msg->id]);
    }
}
