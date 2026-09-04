<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_xml_returns_valid_xml_and_status_200(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $this->assertStringContainsString('application/xml', $response->headers->get('Content-Type'));
        $content = $response->getContent();

        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $content);
        $this->assertStringContainsString('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"', $content);
        $this->assertStringContainsString('<loc>' . url('/') . '</loc>', $content);
        $this->assertStringContainsString('<loc>' . route('shop.index') . '</loc>', $content);
        $this->assertStringContainsString('<loc>' . route('contact') . '</loc>', $content);
    }

    public function test_sitemap_xml_includes_dynamic_products_with_images(): void
    {
        $category = Category::create(['name' => 'Soins Solaires', 'slug' => 'soins-solaires', 'is_active' => true]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Brume Cheirosa 68 Test',
            'slug' => 'brume-cheirosa-68-test',
            'price' => 290,
            'image' => '/images/cheirosa68.png',
            'in_stock' => true,
            'is_active' => true,
            'stock_quantity' => 10,
        ]);

        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $content = $response->getContent();

        $this->assertStringContainsString('<loc>' . route('shop.product', 'brume-cheirosa-68-test') . '</loc>', $content);
        $this->assertStringContainsString('<image:loc>' . url('/images/cheirosa68.png') . '</image:loc>', $content);
    }

    public function test_robots_txt_contains_sitemap_and_disallows_admin(): void
    {
        $robotsPath = public_path('robots.txt');
        $this->assertFileExists($robotsPath);

        $content = file_get_contents($robotsPath);
        $this->assertStringContainsString('Sitemap: https://www.zizoaura.store/sitemap.xml', $content);
        $this->assertStringContainsString('Disallow: /admin', $content);
        $this->assertStringContainsString('Disallow: /api/admin', $content);
    }

    public function test_homepage_has_moroccan_geo_meta_tags_and_schema(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $content = $response->getContent();

        // Geo targeting tags
        $this->assertStringContainsString('<meta name="geo.region" content="MA">', $content);
        $this->assertStringContainsString('<meta name="geo.placename" content="Morocco">', $content);
        $this->assertStringContainsString('<meta property="og:locale" content="fr_MA">', $content);

        // Schema.org OnlineStore & WebSite
        $this->assertStringContainsString('"@type": "OnlineStore"', $content);
        $this->assertStringContainsString('"currenciesAccepted": "MAD"', $content);
        $this->assertStringContainsString('"paymentAccepted": "Cash on Delivery"', $content);
    }

    public function test_product_page_has_moroccan_currency_mad_schema_and_open_graph(): void
    {
        $category = Category::create(['name' => 'Victoria Secret', 'slug' => 'victorias-secret', 'is_active' => true]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Bare Vanilla Test Mist',
            'slug' => 'bare-vanilla-test-mist',
            'price' => 195,
            'image' => '/images/vs_mist_bare_vanilla.png',
            'in_stock' => true,
            'is_active' => true,
            'stock_quantity' => 5,
        ]);

        $response = $this->get('/boutique/produit/bare-vanilla-test-mist');
        $response->assertStatus(200);
        $content = $response->getContent();

        // Open Graph
        $this->assertStringContainsString('<meta property="og:type" content="product">', $content);
        $this->assertStringContainsString('property="og:price:amount" content="195"', $content);
        $this->assertStringContainsString('property="og:price:currency" content="MAD"', $content);

        // Schema.org Product Offer
        $this->assertStringContainsString('"@type": "Product"', $content);
        $this->assertStringContainsString('"priceCurrency": "MAD"', $content);
        $this->assertStringContainsString('"price": "195"', $content);
        $this->assertStringContainsString('"itemCondition": "https://schema.org/NewCondition"', $content);
        $this->assertStringContainsString('"addressCountry": "MA"', $content);
    }

    public function test_category_page_has_dynamic_titles_and_breadcrumbs(): void
    {
        Category::create(['name' => 'Sol de Janeiro', 'slug' => 'sol-de-janeiro', 'is_active' => true]);

        $response = $this->get('/boutique/sol-de-janeiro');
        $response->assertStatus(200);
        $content = $response->getContent();

        $this->assertStringContainsString('Sol de Janeiro Maroc', $content);
        $this->assertStringContainsString('"@type": "CollectionPage"', $content);
        $this->assertStringContainsString('"@type": "BreadcrumbList"', $content);
    }

    public function test_contact_page_has_contact_schema_and_canonical(): void
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);
        $content = $response->getContent();

        $this->assertStringContainsString('"@type": "ContactPage"', $content);
        $this->assertStringContainsString('+212682787594', $content);
        $this->assertStringContainsString('<link rel="canonical" href="' . route('contact') . '">', $content);
    }
}
