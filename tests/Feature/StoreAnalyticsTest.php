<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Order;
use App\Models\StoreAnalyticsEvent;
use Tests\TestCase;

class StoreAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_page_view_is_available_in_the_admin_performance_report(): void
    {
        $this->postJson('/api/analytics/events', [
            'event' => 'page_view',
            'path' => '/boutique',
        ])->assertNoContent();
        $this->postJson('/api/analytics/events', [
            'event' => 'store_click',
            'path' => '/boutique',
        ])->assertNoContent();

        $this->withSession(['admin_authenticated' => true])
            ->getJson('/api/admin/analytics?days=30')
            ->assertOk()
            ->assertJsonPath('metrics.page_views', 1)
            ->assertJsonPath('metrics.visitors', 1)
            ->assertJsonPath('metrics.store_clicks', 1)
            ->assertJsonPath('metrics.whatsapp_clicks', 0)
            ->assertJsonPath('metrics.web_checkout_clicks', 0);
    }

    public function test_report_counts_unique_order_path_visitors_and_excludes_cancelled_gmv(): void
    {
        foreach (['page_view', 'page_view', 'store_click', 'store_click', 'whatsapp_order_click', 'whatsapp_order_click', 'web_checkout_click'] as $event) {
            StoreAnalyticsEvent::create([
                'event_type' => $event,
                'visitor_hash' => str_repeat('a', 64),
                'path' => '/boutique',
                'created_at' => now(),
            ]);
        }

        $this->createOrder('pending', 500);
        $this->createOrder('cancelled', 75);
        $this->createOrder('delivered', 900, now()->subDays(40));

        $this->withSession(['admin_authenticated' => true])
            ->getJson('/api/admin/analytics?days=7')
            ->assertOk()
            ->assertHeader('Cache-Control', 'no-store, private')
            ->assertJsonPath('period.days', 7)
            ->assertJsonPath('metrics.gmv', 500)
            ->assertJsonPath('metrics.orders', 2)
            ->assertJsonPath('metrics.page_views', 2)
            ->assertJsonPath('metrics.store_clicks', 2)
            ->assertJsonPath('metrics.visitors', 1)
            ->assertJsonPath('metrics.whatsapp_clicks', 1)
            ->assertJsonPath('metrics.web_checkout_clicks', 1)
            ->assertJsonCount(7, 'series');
    }

    public function test_event_collection_rejects_unapproved_events_and_ignores_admin_pages(): void
    {
        $this->postJson('/api/analytics/events', ['event' => 'customer_email', 'path' => '/boutique'])
            ->assertUnprocessable();
        $this->postJson('/api/analytics/events', ['event' => 'page_view', 'path' => '/boutique?email=private'])
            ->assertUnprocessable();
        $this->postJson('/api/analytics/events', ['event' => 'page_view', 'path' => '/admin/orders'])
            ->assertNoContent();

        $this->withSession(['admin_authenticated' => true])
            ->getJson('/api/admin/analytics')
            ->assertOk()
            ->assertJsonPath('metrics.page_views', 0);
    }

    public function test_report_is_admin_only_and_rejects_unsupported_periods(): void
    {
        $this->getJson('/api/admin/analytics')->assertUnauthorized();
        $this->withSession(['admin_authenticated' => true])
            ->getJson('/api/admin/analytics?days=5')
            ->assertUnprocessable();
    }

    public function test_admin_navigation_and_store_order_choices_expose_tracking_hooks(): void
    {
        $this->withSession(['admin_authenticated' => true])
            ->get('/admin')
            ->assertOk()
            ->assertSee('href="#performance"', false)
            ->assertSee('data-view="performance"', false);

        $this->get('/boutique')
            ->assertOk()
            ->assertSee('data-store-analytics="whatsapp_order_click"', false)
            ->assertSee('data-store-analytics="web_checkout_click"', false);
    }

    private function createOrder(string $status, float $total, $createdAt = null): Order
    {
        $order = Order::create([
            'customer_name' => 'Client test',
            'customer_phone' => '0611223344',
            'shipping_address' => 'Adresse test',
            'city' => 'Casablanca',
            'subtotal' => $total,
            'shipping_cost' => 0,
            'discount_amount' => 0,
            'total' => $total,
            'status' => $status,
        ]);

        if ($createdAt) $order->forceFill(['created_at' => $createdAt])->save();

        return $order;
    }
}
