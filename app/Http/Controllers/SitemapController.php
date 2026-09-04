<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $baseUrl = rtrim(url('/'), '/');
        if (empty($baseUrl) || ! str_starts_with($baseUrl, 'http')) {
            $baseUrl = rtrim(config('app.url', 'https://www.zizoaura.store'), '/');
        }

        $urls = [];

        // 1. Homepage
        $urls[] = [
            'loc' => $baseUrl,
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'daily',
            'priority' => '1.0',
        ];

        // 2. Shop Main Page
        $urls[] = [
            'loc' => $baseUrl . '/boutique',
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'daily',
            'priority' => '0.9',
        ];

        // 3. Category Pages
        $categories = ShopController::catalogCategories();
        foreach ($categories as $cat) {
            if (empty($cat['slug']) || $cat['slug'] === 'all') {
                continue;
            }
            $urls[] = [
                'loc' => $baseUrl . '/boutique/' . $cat['slug'],
                'lastmod' => date('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        // 4. Products Pages
        $products = ShopController::catalogProducts();
        foreach ($products as $product) {
            if (empty($product['slug'])) {
                continue;
            }
            $urls[] = [
                'loc' => $baseUrl . '/boutique/produit/' . $product['slug'],
                'lastmod' => date('Y-m-d'),
                'changefreq' => 'daily',
                'priority' => '0.8',
                'image' => ! empty($product['image']) ? (str_starts_with($product['image'], 'http') ? $product['image'] : $baseUrl . $product['image']) : null,
                'title' => $product['name'] ?? null,
            ];
        }

        // 5. Featured Brand Landing
        $urls[] = [
            'loc' => $baseUrl . '/marques/de-a-a-z/sol-de-janeiro-janei',
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'weekly',
            'priority' => '0.7',
        ];

        // 6. Contact Page
        $urls[] = [
            'loc' => $baseUrl . '/contact',
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'monthly',
            'priority' => '0.5',
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        $xml .= '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        foreach ($urls as $u) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1, 'UTF-8') . "</loc>\n";
            $xml .= '    <lastmod>' . $u['lastmod'] . "</lastmod>\n";
            $xml .= '    <changefreq>' . $u['changefreq'] . "</changefreq>\n";
            $xml .= '    <priority>' . $u['priority'] . "</priority>\n";

            if (! empty($u['image'])) {
                $xml .= "    <image:image>\n";
                $xml .= '      <image:loc>' . htmlspecialchars($u['image'], ENT_XML1, 'UTF-8') . "</image:loc>\n";
                if (! empty($u['title'])) {
                    $xml .= '      <image:title>' . htmlspecialchars($u['title'], ENT_XML1, 'UTF-8') . "</image:title>\n";
                }
                $xml .= "    </image:image>\n";
            }

            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'X-Robots-Tag' => 'noindex, follow', // Do not index sitemap itself, but follow URLs inside
        ]);
    }
}
