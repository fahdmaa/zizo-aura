<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommerceApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_persists_a_message(): void
    {
        $this->post('/contact', ['name' => 'Amina', 'email' => 'amina@example.com', 'subject' => 'Question', 'message' => 'Bonjour'])
            ->assertRedirect(route('contact'));

        $this->assertDatabaseHas('contact_messages', ['email' => 'amina@example.com', 'subject' => 'Question']);
    }

    public function test_guest_checkout_creates_an_order_applies_coupon_and_reduces_stock(): void
    {
        $category = Category::create(['name' => 'Soins', 'slug' => 'soins']);
        $product = Product::create([
            'category_id' => $category->id, 'name' => 'Crème', 'slug' => 'creme', 'price' => 300,
            'image' => '/cream.jpg', 'in_stock' => true, 'is_active' => true, 'stock_quantity' => 4,
        ]);
        Coupon::create(['code' => 'SAVE10', 'type' => 'percent', 'value' => 10, 'min_order_amount' => 0, 'is_active' => true]);

        $this->withSession(['cart_id' => 'test-cart'])->postJson('/api/cart/items', ['product_id' => $product->id, 'quantity' => 2])->assertCreated();
        $this->withSession(['cart_id' => 'test-cart'])->postJson('/api/checkout', [
            'customer_name' => 'Amina', 'customer_phone' => '0600000000', 'shipping_address' => '1 rue Atlas', 'city' => 'Casablanca', 'coupon_code' => 'SAVE10',
        ])->assertCreated()->assertJsonPath('order.total', '540.00');

        $this->assertDatabaseHas('orders', ['customer_name' => 'Amina', 'subtotal' => 600, 'discount_amount' => 60, 'total' => 540]);
        $this->assertDatabaseHas('coupons', ['code' => 'SAVE10', 'used_count' => 1]);
        $this->assertSame(2, $product->fresh()->stock_quantity);
        $this->assertSame(1, Order::first()->items()->count());
    }

    public function test_admin_api_requires_the_admin_session(): void
    {
        $this->getJson('/api/admin/dashboard')->assertUnauthorized();
        $this->withSession(['admin_authenticated' => true])->getJson('/api/admin/dashboard')->assertOk();
    }

    public function test_admin_auth_cookie_bypass_is_rejected(): void
    {
        // Forging admin_logged_in cookie without active session MUST be rejected
        $this->withCookie('admin_logged_in', '1')
            ->getJson('/api/admin/dashboard')
            ->assertUnauthorized();
    }

    public function test_checkout_handles_long_base64_product_images(): void
    {
        $category = Category::create(['name' => 'Parfums', 'slug' => 'parfums']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Brume Solaire',
            'slug' => 'brume-solaire',
            'price' => 200,
            'image' => 'data:image/jpeg;base64,' . str_repeat('abcde12345', 50),
            'in_stock' => true,
            'is_active' => true,
            'stock_quantity' => 10,
        ]);

        $this->withSession(['cart_id' => 'cart-base64'])->postJson('/api/cart/items', [
            'product_id' => $product->id,
            'quantity' => 1,
        ])->assertCreated();

        $checkoutRes = $this->withSession(['cart_id' => 'cart-base64'])->postJson('/api/checkout', [
            'customer_name' => 'Yasmine',
            'customer_phone' => '0612345678',
            'shipping_address' => 'Bd d\'Anfa',
            'city' => 'Casablanca',
        ]);

        $checkoutRes->assertCreated();
        $this->assertDatabaseHas('orders', ['customer_name' => 'Yasmine']);
        $orderItem = Order::latest()->first()->items()->first();
        $this->assertStringStartsWith('data:image/jpeg;base64,', $orderItem->product_image);
    }

    public function test_order_search_by_non_numeric_name_and_phone(): void
    {
        Order::create([
            'customer_name' => 'Sara El Fassi',
            'customer_phone' => '+212611223344',
            'shipping_address' => '10 Rue Zerktouni',
            'city' => 'Rabat',
            'subtotal' => 300,
            'total' => 300,
            'status' => 'pending',
        ]);

        $res = $this->withSession(['admin_authenticated' => true])
            ->getJson('/api/admin/orders?search=Sara');
        $res->assertOk();
        $this->assertCount(1, $res->json('data'));

        $resPhone = $this->withSession(['admin_authenticated' => true])
            ->getJson('/api/admin/orders?search=212611');
        $resPhone->assertOk();
        $this->assertCount(1, $resPhone->json('data'));
    }

    public function test_blank_slug_automatically_generates_unique_slug(): void
    {
        $category = Category::create(['name' => 'Soins Visage', 'slug' => 'soins-visage']);

        // Create product 1 with blank slug
        $p1Res = $this->withSession(['admin_authenticated' => true])->postJson('/api/admin/products', [
            'category_id' => $category->id,
            'name' => 'Crème Hydratante',
            'slug' => '',
            'price' => 250,
            'image' => '/img1.jpg',
            'is_active' => true,
        ]);
        $p1Res->assertCreated()->assertJsonPath('slug', 'creme-hydratante');

        // Create product 2 with same name and blank slug
        $p2Res = $this->withSession(['admin_authenticated' => true])->postJson('/api/admin/products', [
            'category_id' => $category->id,
            'name' => 'Crème Hydratante',
            'slug' => '',
            'price' => 260,
            'image' => '/img2.jpg',
            'is_active' => true,
        ]);
        $p2Res->assertCreated()->assertJsonPath('slug', 'creme-hydratante-1');
    }

    public function test_scope_in_stock_filters_zero_stock_quantity(): void
    {
        $category = Category::create(['name' => 'Corps', 'slug' => 'corps']);
        $pInStock = Product::create([
            'category_id' => $category->id,
            'name' => 'En Stock',
            'slug' => 'en-stock',
            'price' => 100,
            'image' => '/p.jpg',
            'in_stock' => true,
            'stock_quantity' => 5,
        ]);
        $pUnlimited = Product::create([
            'category_id' => $category->id,
            'name' => 'Illimité',
            'slug' => 'illimite',
            'price' => 100,
            'image' => '/p.jpg',
            'in_stock' => true,
            'stock_quantity' => null,
        ]);
        $pDepleted = Product::create([
            'category_id' => $category->id,
            'name' => 'Épuisé',
            'slug' => 'epuise',
            'price' => 100,
            'image' => '/p.jpg',
            'in_stock' => true,
            'stock_quantity' => 0,
        ]);

        $activeInStockIds = Product::inStock()->pluck('id')->all();
        $this->assertContains($pInStock->id, $activeInStockIds);
        $this->assertContains($pUnlimited->id, $activeInStockIds);
        $this->assertNotContains($pDepleted->id, $activeInStockIds);
    }
}
