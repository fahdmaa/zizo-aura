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
                'image' => $legacy['image'], 'gallery' => json_encode($legacy['gallery'] ?? [$legacy['image']]), 'badge' => $legacy['badge'], 'badge_color' => $legacy['badge_color'], 'rating' => $legacy['rating'], 'review_count' => $legacy['review_count'],
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

        DB::table('coupons')->upsert([
            [
                'code' => 'SUMMER20',
                'type' => 'percent',
                'value' => 20.00,
                'min_order_amount' => 0.00,
                'max_uses' => 500,
                'used_count' => 0,
                'is_active' => true,
                'expires_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'WELCOME10',
                'type' => 'percent',
                'value' => 10.00,
                'min_order_amount' => 0.00,
                'max_uses' => 1000,
                'used_count' => 0,
                'is_active' => true,
                'expires_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'RIO35',
                'type' => 'percent',
                'value' => 35.00,
                'min_order_amount' => 300.00,
                'max_uses' => 1000,
                'used_count' => 0,
                'is_active' => true,
                'expires_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'ZIZO10',
                'type' => 'percent',
                'value' => 10.00,
                'min_order_amount' => 0.00,
                'max_uses' => 1000,
                'used_count' => 0,
                'is_active' => true,
                'expires_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['code'], ['type', 'value', 'min_order_amount', 'max_uses', 'is_active', 'updated_at']);

        DB::table('reviews')->upsert([
            [
                'id' => 1,
                'author_name' => 'Sarah Laurent',
                'author_role' => 'Cliente vérifiée • Bare Vanilla Duo',
                'rating' => 5,
                'comment' => 'Commande reçue en 48h chrono ! Le pack Bare Vanilla est absolument divin et 100% authentique. Les petits échantillons offerts dans le colis sont une délicate attention.',
                'avatar' => '/images/reviews/sarah.jpg',
                'badge' => 'Achat vérifié',
                'ring_color' => 'pink',
                'is_visible' => true,
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'author_name' => 'Yasmine Benali',
                'author_role' => 'Cliente vérifiée • The Ritual of Sakura',
                'rating' => 5,
                'comment' => 'L\'emballage origami The Ritual of Sakura est splendide, prêt à être offert ! La mousse de douche est tellement onctueuse et le parfum de fleur de cerisier tient toute la journée.',
                'avatar' => '/images/reviews/yasmine.jpg',
                'badge' => 'Achat vérifié',
                'ring_color' => 'amber',
                'is_visible' => true,
                'sort_order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'author_name' => 'Camille Moreau',
                'author_role' => 'Cliente vérifiée • Bum Bum Jet Set',
                'rating' => 5,
                'comment' => 'Le Bum Bum Jet Set est un indispensable de l\'été ! L\'odeur de pistache et caramel salé est complètement addictive. Prix super avantageux avec la réduction.',
                'avatar' => '/images/reviews/camille.jpg',
                'badge' => 'Achat vérifié',
                'ring_color' => 'rose',
                'is_visible' => true,
                'sort_order' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'author_name' => 'Léa Dubois',
                'author_role' => 'Cliente vérifiée • VS Bombshell Prestige',
                'rating' => 5,
                'comment' => 'Le flacon Bombshell en cristal avec son nœud satiné est une merveille. La crème pour le corps sublime la peau et fait tenir le parfum toute la soirée.',
                'avatar' => '/images/reviews/lea.jpg',
                'badge' => 'Achat vérifié',
                'ring_color' => 'purple',
                'is_visible' => true,
                'sort_order' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'author_name' => 'Nadia Fourati',
                'author_role' => 'Cliente vérifiée • The Ritual of Ayurveda',
                'rating' => 5,
                'comment' => 'The Ritual of Ayurveda est mon rituel réconfortant préféré. L\'accord rose indienne et amande douce laisse la peau nourrie et satinée. Colis très bien sécurisé.',
                'avatar' => '/images/reviews/nadia.jpg',
                'badge' => 'Achat vérifié',
                'ring_color' => 'emerald',
                'is_visible' => true,
                'sort_order' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 6,
                'author_name' => 'Emma Vidal',
                'author_role' => 'Cliente vérifiée • Beija Flor Jet Set',
                'rating' => 5,
                'comment' => 'Le Beija Flor Jet Set avec la brume 68 sent divinement bon les fleurs fraîches et les vacances. Ma peau est visiblement plus rebondie avec la crème.',
                'avatar' => '/images/reviews/emma.jpg',
                'badge' => 'Achat vérifié',
                'ring_color' => 'teal',
                'is_visible' => true,
                'sort_order' => 6,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['id'], ['author_name', 'author_role', 'rating', 'comment', 'avatar', 'badge', 'ring_color', 'is_visible', 'sort_order', 'updated_at']);
    }
}
