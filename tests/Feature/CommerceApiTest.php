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
}
