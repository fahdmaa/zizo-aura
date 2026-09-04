<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public static function getProducts()
    {
        return [
            // ==================== VICTORIA'S SECRET — BRUMES PARFUMÉES (250ML) ====================
            [
                'id' => 'vs-mist-1',
                'slug' => 'victorias-secret-bare-vanilla-brume-parfumee',
                'brand' => 'Victoria\'s Secret',
                'name' => 'Brume Parfumée Bare Vanilla',
                'subtitle' => 'Vanille Fouettée & Cachemire Doux (Flacon 250ml)',
                'discount' => '-30%',
                'badge' => 'N°1 des Ventes Brumes',
                'badge_color' => 'bg-amber-500 text-white',
                'price' => '195',
                'original_price' => '280',
                'raw_price' => 195,
                'image' => '/images/vs_mist_bare_vanilla.png',
                'gallery' => ['/images/vs_mist_bare_vanilla.png', '/images/vs_bare_vanilla.png', '/images/vs_mist_velvet_petals.png'],
                'category' => 'victorias-secret',
                'category_label' => 'Victoria\'s Secret',
                'rating' => 4.9,
                'review_count' => 3240,
                'sizes' => ['Format Standard (250 ml)'],
                'description' => 'La brume corporelle emblématique la plus vendue au monde. Bare Vanilla enveloppe le corps d\'un nuage gourmand et chaleureux mêlant la vanille fouettée crémeuse et le cachemire soyeux. Un sillage irrésistible et réconfortant du matin au soir.',
                'ingredients' => 'Alcohol Denat., Aqua/Water/Eau, Fragrance (Parfum), Vanilla Tahitensis Fruit Extract, Glycerin, Propylene Glycol, PPG-26-Buteth-26, Ethylhexyl Methoxycinnamate, Aloe Barbadensis Leaf Extract.',
                'olfactory' => 'Notes olfactives : Vanille fouettée gourmande, Cachemire doux velouté, Peau chaude sensuelle.',
                'usage' => 'Vaporisez généreusement sur le corps, le cou et les cheveux. Réappliquez tout au long de la journée pour raviver le sillage.',
                'flavors' => [
                    ['name' => 'Bare Vanilla Classic', 'color' => '#d97706'],
                ]
            ],
            [
                'id' => 'vs-mist-2',
                'slug' => 'victorias-secret-pure-seduction-brume-parfumee',
                'brand' => 'Victoria\'s Secret',
                'name' => 'Brume Parfumée Pure Seduction',
                'subtitle' => 'Prune Rouge Juteuse & Freesia Sucré (Flacon 250ml)',
                'discount' => '-30%',
                'badge' => 'Best-Seller Absolu',
                'badge_color' => 'bg-rose-500 text-white',
                'price' => '195',
                'original_price' => '280',
                'raw_price' => 195,
                'image' => '/images/vs_mist_pure_seduction.png',
                'gallery' => ['/images/vs_mist_pure_seduction.png', '/images/vs_pure_seduction.png', '/images/vs_mist_love_spell.png'],
                'category' => 'victorias-secret',
                'category_label' => 'Victoria\'s Secret',
                'rating' => 4.9,
                'review_count' => 2890,
                'sizes' => ['Format Standard (250 ml)'],
                'description' => 'Une explosion fruitée-florale pétillante et addictive. Pure Seduction capture l\'énergie irrésistible de la prune rouge gorgée de soleil associée à la délicatesse du freesia écrasé pour un sillage jeune, frais et terriblement séduisant.',
                'ingredients' => 'Alcohol Denat., Water (Aqua, Eau), Fragrance (Parfum), Red Plum Extract, Freesia Flower Extract, Aloe Barbadensis Leaf Juice, Chamomilla Recutita Extract.',
                'olfactory' => 'Notes olfactives : Prune rouge juteuse, Freesia écrasé velouté, Melon doux d\'été.',
                'usage' => 'Vaporisez sur l\'ensemble du corps après la douche ou avant de sortir pour une fraîcheur fruitée instantanée.',
                'flavors' => [
                    ['name' => 'Pure Seduction Classic', 'color' => '#be123c'],
                ]
            ],
            [
                'id' => 'vs-mist-3',
                'slug' => 'victorias-secret-velvet-petals-brume-parfumee',
                'brand' => 'Victoria\'s Secret',
                'name' => 'Brume Parfumée Velvet Petals',
                'subtitle' => 'Fleurs Luxuriantes & Glaçage d\'Amande Douce (Flacon 250ml)',
                'discount' => '-30%',
                'badge' => 'Coup de Cœur',
                'badge_color' => 'bg-pink-400 text-pink-950',
                'price' => '195',
                'original_price' => '280',
                'raw_price' => 195,
                'image' => '/images/vs_mist_velvet_petals.png',
                'gallery' => ['/images/vs_mist_velvet_petals.png', '/images/vs_velvet_petals.png', '/images/vs_mist_bare_vanilla.png'],
                'category' => 'victorias-secret',
                'category_label' => 'Victoria\'s Secret',
                'rating' => 4.9,
                'review_count' => 2150,
                'sizes' => ['Format Standard (250 ml)'],
                'description' => 'Un voile floral gourmand d\'une douceur infinie. Velvet Petals sublime la féminité avec des pétales veloutés aériens et un accord amande douce pralinée. Une caresse parfumée addictive et élégante.',
                'ingredients' => 'Alcohol Denat., Aqua/Water, Fragrance (Parfum), Sweet Almond Glaze Accord, White Petal Essence, Glycerin, Tocopheryl Acetate.',
                'olfactory' => 'Notes olfactives : Glaçage à l\'amande douce, Pétales veloutés, Santal crémeux.',
                'usage' => 'Vaporisez sur la peau, le décolleté et les vêtements pour diffuser une aura florale douce.',
                'flavors' => [
                    ['name' => 'Velvet Petals Classic', 'color' => '#f472b6'],
                ]
            ],
            [
                'id' => 'vs-mist-4',
                'slug' => 'victorias-secret-love-spell-brume-parfumee',
                'brand' => 'Victoria\'s Secret',
                'name' => 'Brume Parfumée Love Spell',
                'subtitle' => 'Fleur de Cerisier & Pêche Gorgée de Soleil (Flacon 250ml)',
                'discount' => '-30%',
                'badge' => 'Icône Mythique',
                'badge_color' => 'bg-purple-500 text-white',
                'price' => '195',
                'original_price' => '280',
                'raw_price' => 195,
                'image' => '/images/vs_mist_love_spell.png',
                'gallery' => ['/images/vs_mist_love_spell.png', '/images/vs_love_spell.png', '/images/vs_mist_pure_seduction.png'],
                'category' => 'victorias-secret',
                'category_label' => 'Victoria\'s Secret',
                'rating' => 4.8,
                'review_count' => 3100,
                'sizes' => ['Format Standard (250 ml)'],
                'description' => 'Le philtre d\'amour iconique de Victoria\'s Secret. Love Spell marie l\'éclat vibrant de la pêche de vigne juteuse et la délicatesse poétique de la fleur de cerisier japonais. Une fragrance fraîche, féminine et intemporelle.',
                'ingredients' => 'Alcohol Denat., Aqua, Fragrance (Parfum), Cherry Blossom Extract, Peach Juice Extract, Aloe Vera, Chamomile Extract.',
                'olfactory' => 'Notes olfactives : Fleur de cerisier japonais, Pêche mûre juteuse, Jasmin blanc.',
                'usage' => 'Vaporisez généreusement sur les points de pulsation et les longueurs pour une fraîcheur florale persistante.',
                'flavors' => [
                    ['name' => 'Love Spell Classic', 'color' => '#9333ea'],
                ]
            ],
            [
                'id' => 'vs-mist-5',
                'slug' => 'victorias-secret-coconut-passion-brume-parfumee',
                'brand' => 'Victoria\'s Secret',
                'name' => 'Brume Parfumée Coconut Passion',
                'subtitle' => 'Noix de Coco Chaude & Sable Vanillé Solaire (Flacon 250ml)',
                'discount' => '-30%',
                'badge' => 'Solaire Tropical',
                'badge_color' => 'bg-amber-400 text-amber-950',
                'price' => '195',
                'original_price' => '280',
                'raw_price' => 195,
                'image' => '/images/vs_mist_coconut_passion.png',
                'gallery' => ['/images/vs_mist_coconut_passion.png', '/images/vs_coconut_passion.png', '/images/vs_mist_bare_vanilla.png'],
                'category' => 'victorias-secret',
                'category_label' => 'Victoria\'s Secret',
                'rating' => 4.9,
                'review_count' => 1980,
                'sizes' => ['Format Standard (250 ml)'],
                'description' => 'L\'évasion tropicale par excellence. Coconut Passion évoque une plage de sable chaud sous le soleil couchant avec ses notes de noix de coco lactée et de vanille réconfortante. Le parfum de vacances perpétuelles.',
                'ingredients' => 'Alcohol Denat., Aqua, Fragrance (Parfum), Coconut Extract, Vanilla Bean Extract, Glycerin, Aloe Leaf Juice.',
                'olfactory' => 'Notes olfactives : Noix de coco des îles, Vanille crémeuse, Brise de sable chaud.',
                'usage' => 'Vaporisez sur tout le corps pour une sensation ensoleillée et sensuelle.',
                'flavors' => [
                    ['name' => 'Coconut Passion Classic', 'color' => '#eab308'],
                ]
            ],
            [
                'id' => 'vs-mist-6',
                'slug' => 'victorias-secret-aqua-kiss-brume-parfumee',
                'brand' => 'Victoria\'s Secret',
                'name' => 'Brume Parfumée Aqua Kiss',
                'subtitle' => 'Pluie Fraîche & Marguerite Étoilée Cristalline (Flacon 250ml)',
                'discount' => '-30%',
                'badge' => 'Fraîcheur Aquatique',
                'badge_color' => 'bg-cyan-500 text-white',
                'price' => '195',
                'original_price' => '280',
                'raw_price' => 195,
                'image' => '/images/vs_mist_aqua_kiss.png',
                'gallery' => ['/images/vs_mist_aqua_kiss.png', '/images/vs_aqua_kiss.png', '/images/vs_mist_pure_seduction.png'],
                'category' => 'victorias-secret',
                'category_label' => 'Victoria\'s Secret',
                'rating' => 4.8,
                'review_count' => 1650,
                'sizes' => ['Format Standard (250 ml)'],
                'description' => 'Une vague vivifiante et désaltérante de pure fraîcheur. Aqua Kiss capture la pureté d\'une pluie printanière mêlée à l\'éclat des marguerites sauvages et du freesia blanc. Une sensation propre, énergisante et lumineuse.',
                'ingredients' => 'Alcohol Denat., Water (Aqua), Fragrance (Parfum), Rainkissed Daisy Extract, White Freesia Extract, Aloe Vera Gel.',
                'olfactory' => 'Notes olfactives : Marguerite étoilée, Pluie fraîche cristalline, Freesia blanc aqueux.',
                'usage' => 'Idéal en sortie de douche ou par temps chaud pour un coup de boost rafraîchissant.',
                'flavors' => [
                    ['name' => 'Aqua Kiss Classic', 'color' => '#06b6d4'],
                ]
            ],
            [
                'id' => 'vs-mist-7',
                'slug' => 'victorias-secret-midnight-bloom-brume-parfumee',
                'brand' => 'Victoria\'s Secret',
                'name' => 'Brume Parfumée Midnight Bloom',
                'subtitle' => 'Fleur de Lune & Bois Crémeux Envoûtants (Flacon 250ml)',
                'discount' => '-30%',
                'badge' => 'Mystérieux & Boisé',
                'badge_color' => 'bg-indigo-600 text-white',
                'price' => '195',
                'original_price' => '280',
                'raw_price' => 195,
                'image' => '/images/vs_mist_midnight_bloom.png',
                'gallery' => ['/images/vs_mist_midnight_bloom.png', '/images/vs_midnight_bloom.png', '/images/vs_mist_velvet_petals.png'],
                'category' => 'victorias-secret',
                'category_label' => 'Victoria\'s Secret',
                'rating' => 4.9,
                'review_count' => 2040,
                'sizes' => ['Format Standard (250 ml)'],
                'description' => 'Un parfum sophistiqué aux accords boisés-floraux profonds souvent comparé aux grands jus de niche. Midnight Bloom unit la fleur de lune mystérieuse à la rondeur de bois crémeux et d\'une touche vanillée sombre.',
                'ingredients' => 'Alcohol Denat., Aqua/Water, Fragrance (Parfum), Moonflower Extract, Creamy Woods Accord, Glycerin, Tocopherol.',
                'olfactory' => 'Notes olfactives : Fleur de lune nocturne, Bois crémeux chauds, Vanille noire veloutée.',
                'usage' => 'Parfait pour les soirées et les journées fraîches pour une allure magnétique.',
                'flavors' => [
                    ['name' => 'Midnight Bloom Classic', 'color' => '#4f46e5'],
                ]
            ],
            [
                'id' => 'vs-mist-8',
                'slug' => 'victorias-secret-amber-romance-brume-parfumee',
                'brand' => 'Victoria\'s Secret',
                'name' => 'Brume Parfumée Amber Romance',
                'subtitle' => 'Ambre Précieux & Crème Anglaise Onctueuse (Flacon 250ml)',
                'discount' => '-30%',
                'badge' => 'Sensuel & Chaud',
                'badge_color' => 'bg-amber-600 text-white',
                'price' => '195',
                'original_price' => '280',
                'raw_price' => 195,
                'image' => '/images/vs_mist_amber_romance.png',
                'gallery' => ['/images/vs_mist_amber_romance.png', '/images/vs_amber_romance.png', '/images/vs_mist_bare_vanilla.png'],
                'category' => 'victorias-secret',
                'category_label' => 'Victoria\'s Secret',
                'rating' => 4.9,
                'review_count' => 1820,
                'sizes' => ['Format Standard (250 ml)'],
                'description' => 'Une signature sensuelle, chaude et enveloppante. Amber Romance combine l\'élégance profonde de l\'ambre ambré à la gourmandise d\'une crème anglaise sucrée pour un sillage réconfortant et ultra-séduisant.',
                'ingredients' => 'Alcohol Denat., Aqua, Fragrance (Parfum), Golden Amber Extract, Sweet Creme Anglaise Accord, Aloe Leaf Extract.',
                'olfactory' => 'Notes olfactives : Ambre doré profond, Crème anglaise gourmande, Bois de santal doux.',
                'usage' => 'Vaporisez sur les vêtements et les points chauds du corps pour un sillage longue durée.',
                'flavors' => [
                    ['name' => 'Amber Romance Classic', 'color' => '#d97706'],
                ]
            ],
            [
                'id' => 'vs-mist-9',
                'slug' => 'victorias-secret-rush-brume-parfumee',
                'brand' => 'Victoria\'s Secret',
                'name' => 'Brume Parfumée Rush',
                'subtitle' => 'Clémentine Succulente & Ambre Cristallin Vibrant (Flacon 250ml)',
                'discount' => '-30%',
                'badge' => 'Énergie Vibrante',
                'badge_color' => 'bg-orange-500 text-white',
                'price' => '195',
                'original_price' => '280',
                'raw_price' => 195,
                'image' => '/images/vs_mist_rush.png',
                'gallery' => ['/images/vs_mist_rush.png', '/images/vs_mist_pure_seduction.png', '/images/vs_mist_bare_vanilla.png'],
                'category' => 'victorias-secret',
                'category_label' => 'Victoria\'s Secret',
                'rating' => 4.8,
                'review_count' => 1470,
                'sizes' => ['Format Standard (250 ml)'],
                'description' => 'Un coup d\'éclat sensuel et pétillant. Rush fait fusionner la vivacité juteuse d\'une clémentine gorgée d\'énergie et la chaleur magnétique d\'un ambre cristallin lumineux. Un sillage ultra-dynamique et séducteur.',
                'ingredients' => 'Alcohol Denat., Water (Aqua), Fragrance (Parfum), Clementine Peel Extract, Crystal Amber Accord, Glycerin, Aloe Vera.',
                'olfactory' => 'Notes olfactives : Clémentine juteuse, Ambre cristallin, Musc blanc velouté.',
                'usage' => 'Vaporisez généreusement pour démarrer la journée avec un élan de fraîcheur vitaminée.',
                'flavors' => [
                    ['name' => 'Rush Classic', 'color' => '#ea580c'],
                ]
            ],
            [
                'id' => 'vs-mist-10',
                'slug' => 'victorias-secret-bombshell-fine-fragrance-mist',
                'brand' => 'Victoria\'s Secret',
                'name' => 'Brume Fine Parfumée Bombshell',
                'subtitle' => 'Fruit de la Passion Violet & Pivoine Shangri-La (Flacon 250ml)',
                'discount' => '-25%',
                'badge' => 'N°1 des Parfums USA',
                'badge_color' => 'bg-fuchsia-600 text-white',
                'price' => '240',
                'original_price' => '320',
                'raw_price' => 240,
                'image' => '/images/vs_mist_bombshell.png',
                'gallery' => ['/images/vs_mist_bombshell.png', '/images/vs_bombshell.png', '/images/vs_mist_pure_seduction.png'],
                'category' => 'victorias-secret',
                'category_label' => 'Victoria\'s Secret',
                'rating' => 5.0,
                'review_count' => 4680,
                'sizes' => ['Format Flacon Luxe (250 ml)'],
                'description' => 'La version brume fine et aérienne du parfum le plus célèbre de Victoria\'s Secret. Bombshell s\'ouvre sur le pétillement du fruit de la passion pourpre et des agrumes frais, relevé d\'un cœur floral de pivoine Shangri-La et d\'orchidée vanille.',
                'ingredients' => 'Alcohol Denat., Aqua/Water, Fragrance (Parfum), Purple Passionfruit Extract, Shangri-la Yellow Peony Essence, Vanilla Orchid.',
                'olfactory' => 'Notes olfactives : Fruit de la passion pourpre, Pivoine Shangri-La, Orchidée vanille de Madagascar.',
                'usage' => 'Vaporisez sur le corps et les cheveux pour une allure glamour et irrésistible.',
                'flavors' => [
                    ['name' => 'Bombshell Fine Mist', 'color' => '#db2777'],
                ]
            ],

            // ==================== VICTORIA'S SECRET — PACKS DUOS & COFFRETS ====================
            [
                'id' => 'vs-1',
                'slug' => 'victorias-secret-bare-vanilla-pack-duo',
                'brand' => 'Victoria\'s Secret',
                'name' => 'Pack Duo Bare Vanilla',
                'subtitle' => 'Brume Parfumée (250ml) & Lait Corps Nourrissant (236ml)',
                'discount' => '-30%',
                'badge' => 'Culte Victoria\'s Secret',
                'badge_color' => 'bg-amber-500 text-white',
                'price' => '350',
                'original_price' => '490',
                'raw_price' => 350,
                'image' => '/images/vs_bare_vanilla.png',
                'gallery' => ['/images/vs_bare_vanilla.png', '/images/vs_velvet_petals.png', '/images/vs_bombshell.png'],
                'category' => 'victorias-secret',
                'category_label' => 'Victoria\'s Secret',
                'rating' => 4.9,
                'review_count' => 1240,
                'sizes' => ['Pack Duo Complet (Mist 250ml + Lotion 236ml)'],
                'description' => 'Le duo signature Victoria\'s Secret Bare Vanilla enveloppe la peau d\'une sensualité gourmande et réconfortante. La lotion hydratante 24h nourrit intensément sans effet gras, tandis que la brume corporelle diffuse un sillage vanillé chaud et doux tout au long de la journée.',
                'ingredients' => 'Extrait pur de Vanille Bourbon, Glycérine végétale hydratante, Huile minérale adoucissante, Vitamine E antioxydante, Aloe Vera apaisant.',
                'olfactory' => 'Notes clés : Vanille fouettée crémeuse, Cachemire soyeux doux, Chaleur peau contre peau envoûtante.',
                'usage' => 'Appliquez d\'abord le lait pour le corps sur une peau propre et sèche pour fixer l\'hydratation, puis vaporisez la brume parfumée sur les points de pulsation (cou, poignets, décolleté).',
                'flavors' => [
                    ['name' => 'Bare Vanilla Classic', 'color' => '#d97706'],
                ]
            ],
            [
                'id' => 'vs-2',
                'slug' => 'victorias-secret-pure-seduction-pack-duo',
                'brand' => 'Victoria\'s Secret',
                'name' => 'Pack Duo Pure Seduction',
                'subtitle' => 'Prune Juteuse & Freesia Écrasé (Brume 250ml & Lotion 236ml)',
                'discount' => '-30%',
                'badge' => 'Best-Seller',
                'badge_color' => 'bg-rose-500 text-white',
                'price' => '350',
                'original_price' => '490',
                'raw_price' => 350,
                'image' => '/images/vs_pure_seduction.png',
                'gallery' => ['/images/vs_pure_seduction.png', '/images/vs_love_spell.png', '/images/vs_bare_vanilla.png'],
                'category' => 'victorias-secret',
                'category_label' => 'Victoria\'s Secret',
                'rating' => 4.8,
                'review_count' => 980,
                'sizes' => ['Pack Duo Complet (Mist 250ml + Lotion 236ml)'],
                'description' => 'Pure Seduction de Victoria\'s Secret est une explosion fruitée-florale irrésistible. Ses notes pétillantes de prune rouge gorgée de jus et de freesia frais s\'unissent dans une brume et un lait corps hydratant 24h qui laissent la peau douce et intensément parfumée.',
                'ingredients' => 'Extrait de Prune rouge sauvage, Essence de Freesia rose, Glycérine hydratante, Beurre de Karité, Vitamines C & E.',
                'olfactory' => 'Notes clés : Prune rouge juteuse, Freesia écrasé velouté, Melon doux d\'été.',
                'usage' => 'Appliquez le lait corporel après la douche pour une peau satinée, puis superposez la brume parfumée pour une tenue prolongée.',
                'flavors' => [
                    ['name' => 'Pure Seduction Classic', 'color' => '#be123c'],
                ]
            ],
            [
                'id' => 'vs-3',
                'slug' => 'victorias-secret-velvet-petals-pack-duo',
                'brand' => 'Victoria\'s Secret',
                'name' => 'Pack Duo Velvet Petals',
                'subtitle' => 'Fleurs Luxuriantes & Glaze d\'Amande Douce (Brume + Lait)',
                'discount' => '-30%',
                'badge' => 'Coup de Cœur',
                'badge_color' => 'bg-pink-400 text-pink-950',
                'price' => '350',
                'original_price' => '490',
                'raw_price' => 350,
                'image' => '/images/vs_velvet_petals.png',
                'gallery' => ['/images/vs_velvet_petals.png', '/images/vs_pure_seduction.png', '/images/vs_bare_vanilla.png'],
                'category' => 'victorias-secret',
                'category_label' => 'Victoria\'s Secret',
                'rating' => 4.9,
                'review_count' => 840,
                'sizes' => ['Pack Duo Complet (Mist 250ml + Lotion 236ml)'],
                'description' => 'Velvet Petals révèle un sillage floral-gourmand velouté. La douceur du glaçage à l\'amande se mêle harmonieusement à des pétales de fleurs délicates pour une expérience sensorielle enveloppante et ultra-féminine.',
                'ingredients' => 'Extrait d\'Amande douce, Essences florales fraîches, Huile de Noix de Coco fractionnée, Vitamine E.',
                'olfactory' => 'Notes clés : Glaçage à l\'amande douce, Pétales veloutés de fleurs blanches, Santal crémeux.',
                'usage' => 'Vaporisez sur la peau et les cheveux tout au long de la journée pour un voile de douceur parfumé.',
                'flavors' => [
                    ['name' => 'Velvet Petals Classic', 'color' => '#f472b6'],
                ]
            ],
            [
                'id' => 'vs-4',
                'slug' => 'victorias-secret-love-spell-pack-duo',
                'brand' => 'Victoria\'s Secret',
                'name' => 'Pack Duo Love Spell',
                'subtitle' => 'Fleur de Cerisier & Pêche Fraîche (Brume 250ml & Lotion 236ml)',
                'discount' => '-30%',
                'badge' => 'Icône Mythique',
                'badge_color' => 'bg-purple-500 text-white',
                'price' => '350',
                'original_price' => '490',
                'raw_price' => 350,
                'image' => '/images/vs_love_spell.png',
                'gallery' => ['/images/vs_love_spell.png', '/images/vs_pure_seduction.png', '/images/vs_velvet_petals.png'],
                'category' => 'victorias-secret',
                'category_label' => 'Victoria\'s Secret',
                'rating' => 4.8,
                'review_count' => 1150,
                'sizes' => ['Pack Duo Complet (Mist + Lotion)'],
                'description' => 'La fragrance légendaire qui a conquis le monde. Love Spell associe la fraîcheur pétillante de la pêche mûre et l\'élégance de la fleur de cerisier japonais. Un sillage séduisant, vibrant et frais.',
                'ingredients' => 'Extrait de Pêche de vigne, Fleur de Cerisier, Aloe Vera régénérant, Extrait de Camomille calmante.',
                'olfactory' => 'Notes clés : Fleur de cerisier japonais, Pêche juteuse gorgée de soleil, Jasmin blanc.',
                'usage' => 'Idéal après la douche pour une sensation de fraîcheur immédiate et une hydratation continue 24 heures.',
                'flavors' => [
                    ['name' => 'Love Spell Classic', 'color' => '#9333ea'],
                ]
            ],
            [
                'id' => 'vs-5',
                'slug' => 'victorias-secret-coconut-passion-pack-duo',
                'brand' => 'Victoria\'s Secret',
                'name' => 'Pack Duo Coconut Passion',
                'subtitle' => 'Noix de Coco Insulaire & Sable Chaud Vanillé (Duo Culte)',
                'discount' => '-30%',
                'badge' => 'Solaire Tropical',
                'badge_color' => 'bg-amber-400 text-amber-950',
                'price' => '350',
                'original_price' => '490',
                'raw_price' => 350,
                'image' => '/images/vs_coconut_passion.png',
                'gallery' => ['/images/vs_coconut_passion.png', '/images/vs_bare_vanilla.png', '/images/vs_pure_seduction.png'],
                'category' => 'victorias-secret',
                'category_label' => 'Victoria\'s Secret',
                'rating' => 4.9,
                'review_count' => 790,
                'sizes' => ['Pack Duo Complet (Mist + Lotion)'],
                'description' => 'Une véritable escapade sous les tropiques. Coconut Passion mêle la richesse lactée de la noix de coco fraîche à la chaleur réconfortante de la vanille et du sable chaud. Le parfum de l\'été perpétuel.',
                'ingredients' => 'Huile de Noix de Coco des îles, Extrait de Gousse de Vanille, Beurre de Cacao, Vitamine E.',
                'olfactory' => 'Notes clés : Noix de coco des îles, Vanille crémeuse, Accords sable chaud et brise marine.',
                'usage' => 'Appliquez généreusement après une exposition au soleil pour nourrir et prolonger le hâle de la peau.',
                'flavors' => [
                    ['name' => 'Coconut Passion Classic', 'color' => '#eab308'],
                ]
            ],
            [
                'id' => 'vs-6',
                'slug' => 'victorias-secret-bombshell-prestige-gift-set',
                'brand' => 'Victoria\'s Secret',
                'name' => 'Coffret Bombshell Prestige',
                'subtitle' => 'Eau de Parfum Flacon Cristal & Crème Parfumée Velours',
                'discount' => '-25%',
                'badge' => 'N°1 des Ventes Parfums',
                'badge_color' => 'bg-fuchsia-600 text-white',
                'price' => '590',
                'original_price' => '790',
                'raw_price' => 590,
                'image' => '/images/vs_bombshell.png',
                'gallery' => ['/images/vs_bombshell.png', '/images/vs_tease.png', '/images/vs_bare_vanilla.png'],
                'category' => 'victorias-secret',
                'category_label' => 'Victoria\'s Secret',
                'rating' => 5.0,
                'review_count' => 2100,
                'sizes' => ['Coffret Prestige (Eau de Parfum 50ml + Crème 200ml)'],
                'description' => 'Le parfum emblématique numéro 1 en Amérique dans un coffret de luxe somptueux. Bombshell est un bouquet floral-fruité audacieux et éclatant de fruit de la passion pourpre, de pivoine Shangri-La et d\'orchidée vanille.',
                'ingredients' => 'Essence pure de Fruit de la Passion pourpre, Pivoine jaune Shangri-La, Orchidée vanille de Madagascar.',
                'olfactory' => 'Notes de tête : Fruit de la Passion violet, Pamplemousse pétillant. Notes de cœur : Pivoine Shangri-La, Lys blanc. Notes de fond : Orchidée vanille, Musc soyeux.',
                'usage' => 'Vaporisez l\'Eau de Parfum sur les points de pulsation et sublimez les bras et les jambes avec la crème parfumée veloutée.',
                'flavors' => [
                    ['name' => 'Bombshell Classic', 'color' => '#ec4899'],
                ]
            ],
            [
                'id' => 'vs-7',
                'slug' => 'victorias-secret-aqua-kiss-pack-duo',
                'brand' => 'Victoria\'s Secret',
                'name' => 'Pack Duo Aqua Kiss',
                'subtitle' => 'Pluie Fraîche & Marguerite Étoilée (Brume 250ml & Lotion 236ml)',
                'discount' => '-30%',
                'badge' => 'Fraîcheur Aquatique',
                'badge_color' => 'bg-cyan-500 text-white',
                'price' => '350',
                'original_price' => '490',
                'raw_price' => 350,
                'image' => '/images/vs_aqua_kiss.png',
                'gallery' => ['/images/vs_aqua_kiss.png', '/images/vs_pure_seduction.png', '/images/vs_bare_vanilla.png'],
                'category' => 'victorias-secret',
                'category_label' => 'Victoria\'s Secret',
                'rating' => 4.8,
                'review_count' => 620,
                'sizes' => ['Pack Duo Complet (Mist 250ml + Lotion 236ml)'],
                'description' => 'Une immersion d\'une fraîcheur éclatante et vivifiante. Aqua Kiss évoque une pluie de printemps infusée de marguerites étoilées et de freesia frais pour une sensation vivifiante et purifiante toute la journée.',
                'ingredients' => 'Extrait de Marguerite étoilée, Eau florale de Freesia, Aloe Vera apaisant, Glycérine végétale.',
                'olfactory' => 'Notes clés : Marguerite étoilée, Pluie fraîche cristalline, Freesia aqueux, Musc propre.',
                'usage' => 'Vaporisez généreusement sur le corps pour une vague de fraîcheur immédiate après le bain ou l\'effort physique.',
                'flavors' => [
                    ['name' => 'Aqua Kiss Classic', 'color' => '#06b6d4'],
                ]
            ],
            [
                'id' => 'vs-8',
                'slug' => 'victorias-secret-midnight-bloom-pack-duo',
                'brand' => 'Victoria\'s Secret',
                'name' => 'Pack Duo Midnight Bloom',
                'subtitle' => 'Fleur de Lune & Bois Doux Crépusculaire (Duo Envoûtant)',
                'discount' => '-30%',
                'badge' => 'Mystérieux & Boisé',
                'badge_color' => 'bg-indigo-600 text-white',
                'price' => '350',
                'original_price' => '490',
                'raw_price' => 350,
                'image' => '/images/vs_midnight_bloom.png',
                'gallery' => ['/images/vs_midnight_bloom.png', '/images/vs_love_spell.png', '/images/vs_velvet_petals.png'],
                'category' => 'victorias-secret',
                'category_label' => 'Victoria\'s Secret',
                'rating' => 4.9,
                'review_count' => 870,
                'sizes' => ['Pack Duo Complet (Mist 250ml + Lotion 236ml)'],
                'description' => 'Un sillage nocturne hypnotisant et raffiné. Midnight Bloom fusionne l\'élégance de la fleur de lune nocturne et la chaleur enveloppante des bois crémeux pour une aura magnétique et sophistiquée.',
                'ingredients' => 'Extrait de Fleur de Lune (Moonflower), Essence de Bois crémeux, Huile de Coco régénérante, Vitamine E.',
                'olfactory' => 'Notes clés : Fleur de lune délicate, Bois crémeux chauds, Vanille noire veloutée.',
                'usage' => 'Appliquez la lotion veloutée sur le corps puis vaporisez la brume parfumée pour une intensité nocturne captivante.',
                'flavors' => [
                    ['name' => 'Midnight Bloom Classic', 'color' => '#4f46e5'],
                ]
            ],
            [
                'id' => 'vs-9',
                'slug' => 'victorias-secret-amber-romance-pack-duo',
                'brand' => 'Victoria\'s Secret',
                'name' => 'Pack Duo Amber Romance',
                'subtitle' => 'Ambre Chaud & Crème Anglaise Gourmande (Duo Sensuel)',
                'discount' => '-30%',
                'badge' => 'Sensuel & Chaud',
                'badge_color' => 'bg-amber-600 text-white',
                'price' => '350',
                'original_price' => '490',
                'raw_price' => 350,
                'image' => '/images/vs_amber_romance.png',
                'gallery' => ['/images/vs_amber_romance.png', '/images/vs_bare_vanilla.png', '/images/vs_coconut_passion.png'],
                'category' => 'victorias-secret',
                'category_label' => 'Victoria\'s Secret',
                'rating' => 4.9,
                'review_count' => 940,
                'sizes' => ['Pack Duo Complet (Mist 250ml + Lotion 236ml)'],
                'description' => 'Un voile chaleureux et infiniment sensuel. Amber Romance célèbre l\'accord gourmand de l\'ambre précieux et de la crème anglaise vanillée pour un sillage riche, enveloppant et ultra-séduisant.',
                'ingredients' => 'Résinoïde d\'Ambre précieux, Accord Crème Anglaise, Huile d\'Amande douce, Aloe Vera apaisant.',
                'olfactory' => 'Notes clés : Ambre profond et chaud, Crème anglaise sucrée, Bois de santal crémeux.',
                'usage' => 'Massez la crème soyeuse sur la peau puis sublimez d\'un nuage de brume parfumée sur le buste et les cheveux.',
                'flavors' => [
                    ['name' => 'Amber Romance Classic', 'color' => '#d97706'],
                ]
            ],
            [
                'id' => 'vs-10',
                'slug' => 'victorias-secret-tease-prestige-gift-set',
                'brand' => 'Victoria\'s Secret',
                'name' => 'Coffret Tease Prestige',
                'subtitle' => 'Eau de Parfum & Crème Parfumée Veloutée (Coffret Cadeau Luxe)',
                'discount' => '-25%',
                'badge' => 'Séduction Couture',
                'badge_color' => 'bg-rose-700 text-white',
                'price' => '590',
                'original_price' => '790',
                'raw_price' => 590,
                'image' => '/images/vs_tease.png',
                'gallery' => ['/images/vs_tease.png', '/images/vs_bombshell.png', '/images/vs_pure_seduction.png'],
                'category' => 'victorias-secret',
                'category_label' => 'Victoria\'s Secret',
                'rating' => 5.0,
                'review_count' => 1420,
                'sizes' => ['Coffret Prestige (Eau de Parfum 50ml + Crème 200ml)'],
                'description' => 'L\'expression ultime de la séduction espiègle et gourmande. Tease mêle la fraîcheur croquante de la poire d\'Anjou, la douceur de la gardenia blanche et l\'attrait irrésistible de la vanille noire.',
                'ingredients' => 'Essence de Poire d\'Anjou, Fleur de Gardénia blanc, Vanille noire de Madagascar, Musc doux.',
                'olfactory' => 'Notes de tête : Poire d\'Anjou croquante, Mandarine givrée. Notes de cœur : Gardénia blanc solaire, Jasmin étoilé. Notes de fond : Vanille noire, Ambre doré.',
                'usage' => 'Vaporisez l\'Eau de Parfum sur les points de pulsation et appliquez la crème nourrissante sur le décolleté et les bras pour un sillage inoubliable.',
                'flavors' => [
                    ['name' => 'Tease Classic', 'color' => '#e11d48'],
                ]
            ],

            // ==================== RITUALS ====================
            [
                'id' => 'rit-1',
                'slug' => 'rituals-the-ritual-of-sakura-gift-set',
                'brand' => 'Rituals',
                'name' => 'Coffret The Ritual of Sakura',
                'subtitle' => 'Coffret Cadeau Soins Corps : Mousse de Douche, Gommage & Crème Veloutée',
                'discount' => '-20%',
                'badge' => 'Best-Seller Mondial',
                'badge_color' => 'bg-pink-400 text-pink-950',
                'price' => '390',
                'original_price' => '490',
                'raw_price' => 390,
                'image' => '/images/rituals_sakura.png',
                'gallery' => ['/images/rituals_sakura.png', '/images/rituals_ayurveda.png', '/images/rituals_karma.png'],
                'category' => 'rituals',
                'category_label' => 'Rituals',
                'rating' => 4.9,
                'review_count' => 1650,
                'sizes' => ['Coffret Origami Medium (3 Produits + Bougie)', 'Coffret Large Prestige'],
                'description' => 'Inspiré par la floraison des cerisiers japonais (Hanami), le coffret The Ritual of Sakura célèbre chaque jour comme un nouveau départ. Il contient une mousse de douche soyeuse à la texture gel-en-mousse unique, un gommage corps au sucre lissant et une crème corps onctueuse enrichie en lait de riz nourrissant.',
                'ingredients' => 'Extrait de Fleur de Cerisier japonais (Sakura), Lait de Riz biologique adoucissant, Huile de Tournesol bio, Vitamine E, Sucre naturel exfoliant.',
                'olfactory' => 'Notes de tête : Lait d\'amande crémeux, Accords verts frais. Notes de cœur : Fleur de Cerisier japonais, Jasmin délicat. Notes de fond : Vanille poudrée, Bois de Cèdre doux.',
                'usage' => 'Faites mousser une petite noisette de gel de douche qui se transforme instantanément en mousse dense. Exfoliez avec le gommage une à deux fois par semaine, puis terminez par l\'application de la crème corps Sakura.',
                'flavors' => [
                    ['name' => 'Fleur de Cerisier & Lait de Riz', 'color' => '#f472b6'],
                ]
            ],
            [
                'id' => 'rit-2',
                'slug' => 'rituals-the-ritual-of-ayurveda-gift-set',
                'brand' => 'Rituals',
                'name' => 'Coffret The Ritual of Ayurveda',
                'subtitle' => 'Coffret Cadeau Harmonie : Rose Indienne & Huile d\'Amande Douce Rééquilibrante',
                'discount' => '-20%',
                'badge' => 'Harmonie & Sérénité',
                'badge_color' => 'bg-rose-600 text-white',
                'price' => '390',
                'original_price' => '490',
                'raw_price' => 390,
                'image' => '/images/rituals_ayurveda.png',
                'gallery' => ['/images/rituals_ayurveda.png', '/images/rituals_sakura.png', '/images/rituals_mehr.png'],
                'category' => 'rituals',
                'category_label' => 'Rituals',
                'rating' => 4.8,
                'review_count' => 1120,
                'sizes' => ['Coffret Cadeau Élixir (4 Produits)'],
                'description' => 'Rétablissez l\'harmonie du corps et de l\'esprit grâce aux principes ancestraux de l\'Ayurveda. Ce coffret luxueux enveloppe les sens avec la douceur florale de la Rose Indienne et les vertus hautement nourrissantes de l\'Huile d\'Amande Douce.',
                'ingredients' => 'Extrait de Rose de Damas indienne, Huile d\'Amande Douce pressée à froid, Huile de Moringa protectrice, Sel rose de l\'Himalaya, Huile de Basilic sacré.',
                'olfactory' => 'Notes de tête : Amande douce, Mandarine pétillante. Notes de cœur : Rose Indienne veloutée, Muguet. Notes de fond : Bois de Santal, Ambre chaleureux, Fève Tonka.',
                'usage' => 'Appliquez la mousse de douche sous un filet d\'eau tiède, puis appliquez la crème nourrissante sur peau humide pour sceller les bienfaits de l\'amande douce.',
                'flavors' => [
                    ['name' => 'Rose Indienne & Amande', 'color' => '#e11d48'],
                ]
            ],
            [
                'id' => 'rit-3',
                'slug' => 'rituals-the-ritual-of-karma-gift-set',
                'brand' => 'Rituals',
                'name' => 'Coffret The Ritual of Karma',
                'subtitle' => 'Coffret Cadeau Solaire : Lotus Sacré & Thé Blanc Biologique avec Huile Scintillante',
                'discount' => '-20%',
                'badge' => 'Ondes Positives & Éclat',
                'badge_color' => 'bg-teal-500 text-white',
                'price' => '390',
                'original_price' => '490',
                'raw_price' => 390,
                'image' => '/images/rituals_karma.png',
                'gallery' => ['/images/rituals_karma.png', '/images/rituals_sakura.png', '/images/rituals_jing.png'],
                'category' => 'rituals',
                'category_label' => 'Rituals',
                'rating' => 4.9,
                'review_count' => 880,
                'sizes' => ['Coffret Origami Solaire (Mousse + Huile Scintillante + Crème)'],
                'description' => 'Diffusez de bonnes ondes autour de vous avec The Ritual of Karma. Conçu pour apporter un sentiment d\'été instantané, il nourrit la peau grâce au pouvoir antioxydant du Thé Blanc et apaise avec le Lotus Sacré.',
                'ingredients' => 'Extrait de Lotus Sacré purifiant, Extrait de Thé Blanc biologique riche en antioxydants, Huile de Jojoba dorée, Particules de mica minéral nacré.',
                'olfactory' => 'Notes clés : Lotus blanc pur, Thé vert & blanc floral, Bergamote fraîche, Nuance boisée légère.',
                'usage' => 'Appliquez la mousse sous la douche, hydratez avec la crème corps et sublimez le décolleté avec l\'huile scintillante pour un éclat doré.',
                'flavors' => [
                    ['name' => 'Lotus Sacré & Thé Blanc', 'color' => '#14b8a6'],
                ]
            ],
            [
                'id' => 'rit-4',
                'slug' => 'rituals-the-ritual-of-mehr-gift-set',
                'brand' => 'Rituals',
                'name' => 'Coffret The Ritual of Mehr',
                'subtitle' => 'Coffret Cadeau Énergisant : Orange Douce Stimulante & Bois de Cèdre Réchauffant',
                'discount' => '-20%',
                'badge' => 'Coup d\'Éclat Solaire',
                'badge_color' => 'bg-amber-500 text-white',
                'price' => '390',
                'original_price' => '490',
                'raw_price' => 390,
                'image' => '/images/rituals_mehr.png',
                'gallery' => ['/images/rituals_mehr.png', '/images/rituals_ayurveda.png', '/images/rituals_sakura.png'],
                'category' => 'rituals',
                'category_label' => 'Rituals',
                'rating' => 4.8,
                'review_count' => 760,
                'sizes' => ['Coffret Cadeau Énergisant (3 Produits)'],
                'description' => 'Inspiré par Mehr, le soleil de la mythologie perse, ce coffret revigore le corps et l\'âme. L\'orange douce pétillante apporte de la joie et de l\'énergie, tandis que le bois de cèdre noble apporte une chaleur boisée apaisante.',
                'ingredients' => 'Huile essentielle d\'Orange Douce, Extrait d\'Écorce de Cèdre noble, Huile de Tournesol biologique, Vitamine C naturelle.',
                'olfactory' => 'Notes clés : Zeste d\'orange douce juteuse, Épices douces, Bois de cèdre fumé velouté.',
                'usage' => 'Utilisez la mousse de douche le matin pour dynamiser votre réveil, puis appliquez la crème corps pour nourrir votre peau.',
                'flavors' => [
                    ['name' => 'Orange Douce & Cèdre', 'color' => '#f59e0b'],
                ]
            ],
            [
                'id' => 'rit-5',
                'slug' => 'rituals-the-ritual-of-jing-gift-set',
                'brand' => 'Rituals',
                'name' => 'Coffret The Ritual of Jing',
                'subtitle' => 'Coffret Cadeau Relaxation : Lotus Sacré & Jujube Apaisant avec Brume d\'Oreiller',
                'discount' => '-20%',
                'badge' => 'Relaxation Profonde',
                'badge_color' => 'bg-emerald-600 text-white',
                'price' => '390',
                'original_price' => '490',
                'raw_price' => 390,
                'image' => '/images/rituals_jing.png',
                'gallery' => ['/images/rituals_jing.png', '/images/rituals_karma.png', '/images/rituals_sakura.png'],
                'category' => 'rituals',
                'category_label' => 'Rituals',
                'rating' => 4.9,
                'review_count' => 930,
                'sizes' => ['Coffret Origami Relaxant (Mousse + Brume Oreiller + Crème)'],
                'description' => 'Inspiré par le concept chinois ancien de Jing représentant la quiétude et la tranquillité, ce coffret vous aide à ralentir le rythme, apaiser l\'esprit et favoriser un sommeil réparateur profond.',
                'ingredients' => 'Extrait de Jujube relaxant, Lotus Sacré calmant, Huile essentielle de Lavande vraie, Extrait de Bois de Santal.',
                'olfactory' => 'Notes clés : Jujube doux, Fleur de Lotus apaisante, Lavande poudrée délicate.',
                'usage' => 'Prenez une douche relaxante avec la mousse onctueuse, appliquez la crème corps et vaporisez la brume sur votre oreiller 15 minutes avant de dormir.',
                'flavors' => [
                    ['name' => 'Jujube & Lotus Sacré', 'color' => '#059669'],
                ]
            ],

            // ==================== SOL DE JANEIRO ====================
            [
                'id' => 'sdj-1',
                'slug' => 'sol-de-janeiro-bum-bum-jet-set',
                'brand' => 'Sol de Janeiro',
                'name' => 'Coffret Brazilian Bum Bum Jet Set',
                'subtitle' => 'Trio culte voyage : Crème Bum Bum, Brume 62 & Gel douche 4 Play',
                'discount' => '-25%',
                'badge' => 'Best-Seller Absolu',
                'badge_color' => 'bg-amber-400 text-amber-950',
                'price' => '320',
                'original_price' => '420',
                'raw_price' => 320,
                'image' => '/images/sdj_bum_bum_set.png',
                'gallery' => ['/images/sdj_bum_bum_set.png', '/images/bum_bum_cream.png', '/images/cheirosa_mist.png', '/images/ultimate_bundle.png'],
                'category' => 'sol-de-janeiro',
                'category_label' => 'Sol de Janeiro',
                'rating' => 4.9,
                'review_count' => 2450,
                'sizes' => ['Pack Voyage (3x Produits)', 'Coffret Full Size'],
                'description' => 'Le coffret culte Sol de Janeiro prêt pour le voyage ! Il réunit la crème corps raffermissante Brazilian Bum Bum Cream (50ml), la brume parfumée Cheirosa 62 iconique (30ml) et la crème-douche hydratante Brazilian 4 Play (90ml). Enrichi en extrait de Guaraná amazonien et beurre de Cupuaçu pour une peau lisse, douce et délicieusement parfumée.',
                'ingredients' => 'Extrait de Guaraná (caféine naturelle stimulante), Beurre de Cupuaçu nourrissant, Huile d\'Açaí antioxydante, Huile de Noix du Brésil, Beurre de Karité.',
                'olfactory' => 'Notes de tête : Pistache torréfiée, Amande douce. Notes de cœur : Héliotrope, Pétales de Jasmin. Notes de fond : Caramel salé, Vanille Bourbon, Bois de Santal.',
                'usage' => 'Massez la crème Brazilian Bum Bum sur tout le corps en mouvements circulaires. Utilisez le gel douche 4 Play sous la douche puis vaporisez généreusement la brume Cheirosa 62 sur le corps et les cheveux.',
                'flavors' => [
                    ['name' => 'Cheirosa 62 Original', 'color' => '#f59e0b'],
                ]
            ],
            [
                'id' => 'sdj-2',
                'slug' => 'sol-de-janeiro-beija-flor-jet-set',
                'brand' => 'Sol de Janeiro',
                'name' => 'Coffret Beija Flor Jet Set',
                'subtitle' => 'Crème Elasti-Cream Collagène, Brume Cheirosa 68 & Gel Douche',
                'discount' => '-25%',
                'badge' => 'Collagène Végétal',
                'badge_color' => 'bg-pink-500 text-white',
                'price' => '320',
                'original_price' => '420',
                'raw_price' => 320,
                'image' => '/images/sdj_beija_flor.png',
                'gallery' => ['/images/sdj_beija_flor.png', '/images/sdj_bum_bum_set.png', '/images/bum_bum_cream.png'],
                'category' => 'sol-de-janeiro',
                'category_label' => 'Sol de Janeiro',
                'rating' => 4.9,
                'review_count' => 1780,
                'sizes' => ['Pack Voyage Beija Flor (3 Produits)'],
                'description' => 'Un trio éclat et fermeté boosté au collagène végétal et au beurre de Cacay. Le parfum floral-fruité frais Cheirosa 68 évoque un jardin luxuriant au bord de l\'océan brésilien avec des notes de jasmin du Brésil et de fruit du dragon rose.',
                'ingredients' => 'Collagène végétal bio-fermenté, Huile de Cacay alternative naturelle au rétinol, Extrait de Fruit du Dragon rose, Squalane végétal pur.',
                'olfactory' => 'Notes de tête : Fruit du Dragon rose, Essence de Litchi. Notes de cœur : Jasmin du Brésil, Brise océanique, Hibiscus. Notes de fond : Vanille pure, Musc solaire.',
                'usage' => 'Appliquez la crème Beija Flor sur le corps pour stimuler l\'élasticité et le renouvellement cellulaire, puis vaporisez la brume 68 sur peau et cheveux.',
                'flavors' => [
                    ['name' => 'Cheirosa 68 Beija Flor', 'color' => '#ec4899'],
                ]
            ],
            [
                'id' => 'sdj-bom-dia',
                'slug' => 'sol-de-janeiro-bom-dia-bright-set',
                'brand' => 'Sol de Janeiro',
                'name' => 'Coffret Bom Dia Bright Jet Set',
                'subtitle' => 'Crème Bom Dia Bright AHA, Brume Cheirosa 40 & Gel Douche Clarifiant',
                'discount' => '-25%',
                'badge' => 'AHA Fruits & Éclat',
                'badge_color' => 'bg-red-500 text-white',
                'price' => '320',
                'original_price' => '420',
                'raw_price' => 320,
                'image' => '/images/sdj_bom_dia.png',
                'gallery' => ['/images/sdj_bom_dia.png', '/images/sdj_bum_bum_set.png', '/images/sdj_beija_flor.png', '/images/sdj_delicia_drench.png'],
                'category' => 'sol-de-janeiro',
                'category_label' => 'Sol de Janeiro',
                'rating' => 4.9,
                'review_count' => 1420,
                'sizes' => ['Pack Voyage Bom Dia (3 Produits)'],
                'description' => 'Un rituel éclat et lissant enrichi en acides de fruits AHA et vitamine C. Le parfum envoûtant et sophistiqué Cheirosa 40 mêle des notes gourmandes de prune noire ambrée, de fleurs de jasmin et de bois vanillé.',
                'ingredients' => 'AHA de fruits exfoliants naturels, Vitamine C estérifiée, Beurre de Cupuaçu, Huile de Cajá régénérante.',
                'olfactory' => 'Notes de tête : Prune noire ambrée, Crème de cassis. Notes de cœur : Fleur de jasmin, Orchidée brésilienne. Notes de fond : Bois vanillé, Musc chaud.',
                'usage' => 'Nettoyez avec le gel douche clarifiant, appliquez la crème Bom Dia Bright sur le corps pour révéler un grain de peau lisse et éclatant, puis vaporisez la brume Cheirosa 40.',
                'flavors' => [
                    ['name' => 'Cheirosa 40 Bom Dia', 'color' => '#e11d48'],
                ]
            ],
            [
                'id' => 'sdj-delicia',
                'slug' => 'sol-de-janeiro-delicia-drench-set',
                'brand' => 'Sol de Janeiro',
                'name' => 'Coffret Delícia Drench Jet Set',
                'subtitle' => 'Beurre Corps Réparateur Barrière, Brume Cheirosa 59 & Huile Douche',
                'discount' => '-25%',
                'badge' => 'Nutrition Intense & Apaisant',
                'badge_color' => 'bg-purple-600 text-white',
                'price' => '320',
                'original_price' => '420',
                'raw_price' => 320,
                'image' => '/images/sdj_delicia_drench.png',
                'gallery' => ['/images/sdj_delicia_drench.png', '/images/sdj_bum_bum_set.png', '/images/sdj_beija_flor.png', '/images/sdj_bom_dia.png'],
                'category' => 'sol-de-janeiro',
                'category_label' => 'Sol de Janeiro',
                'rating' => 4.9,
                'review_count' => 1630,
                'sizes' => ['Pack Voyage Delícia Drench (3 Produits)'],
                'description' => 'Le rituel de soin ultra-nourrissant cliniquement prouvé pour réparer la barrière cutanée et apaiser les peaux les plus sèches. Infusé du parfum boisé et gourmand Cheirosa 59 aux accords de vanille orchidée, violette sucrée et bois de santal.',
                'ingredients' => 'Beurre de Bacuri réparateur, Complexe Prébiotique d\'Hibiscus, Huile de Noix du Brésil, Beurre de Karité.',
                'olfactory' => 'Notes de tête : Orchidée vanille, Prune sucrée. Notes de cœur : Violette givrée, Ambre crémeux. Notes de fond : Bois de santal, Vétiver velouté.',
                'usage' => 'Émulsionnez l\'huile de douche en mousse lactée, appliquez le beurre corporel riche Delícia Drench pour sceller l\'hydratation et terminez par la brume Cheirosa 59.',
                'flavors' => [
                    ['name' => 'Cheirosa 59 Delícia Drench', 'color' => '#7c3aed'],
                ]
            ],
            [
                'id' => 'sdj-3',
                'slug' => 'sol-de-janeiro-brazilian-bum-bum-cream-240ml',
                'brand' => 'Sol de Janeiro',
                'name' => 'Brazilian Bum Bum Cream (Grand Format)',
                'subtitle' => 'Crème Corps Raffermissante Iconique (Pot 240ml)',
                'discount' => '-20%',
                'badge' => 'Produit Star',
                'badge_color' => 'bg-amber-400 text-amber-950',
                'price' => '390',
                'original_price' => '490',
                'raw_price' => 390,
                'image' => '/images/bum_bum_cream.png',
                'gallery' => ['/images/bum_bum_cream.png', '/images/sdj_bum_bum_set.png', '/images/cheirosa_mist.png'],
                'category' => 'sol-de-janeiro',
                'category_label' => 'Sol de Janeiro',
                'rating' => 4.9,
                'review_count' => 3890,
                'sizes' => ['75 ml', '240 ml', '500 ml'],
                'description' => 'La crème pour le corps primée qui raffermit et lisse visiblement la peau tout en délivrant une hydratation profonde et un fini lumineux irrésistible. Formulée avec du Guaraná riche en caféine et du beurre de Cupuaçu.',
                'ingredients' => 'Guaraná naturel, Beurre de Cupuaçu, Huile d\'Açaí, Huile de Noix du Brésil, Beurre de Karité, Poudre de Mica dorée.',
                'olfactory' => 'Notes : Pistache torréfiée, Caramel salé, Vanille bourbon, Jasmin délicat.',
                'usage' => 'Massez sur les fesses, les jambes, le ventre et les bras en mouvements circulaires pour activer la pénétration.',
                'flavors' => [
                    ['name' => 'Beurre de Cupuaçu Solaire', 'color' => '#facc15'],
                ]
            ],
            [
                'id' => 'sdj-4',
                'slug' => 'sol-de-janeiro-cheirosa-62-perfume-mist-240ml',
                'brand' => 'Sol de Janeiro',
                'name' => 'Cheirosa 62 Brume Parfumée (Grand Format)',
                'subtitle' => 'Brume Cheveux & Corps Solaire et Gourmande (Flacon 240ml)',
                'discount' => '-20%',
                'badge' => 'Fragrance Mythique',
                'badge_color' => 'bg-amber-500 text-white',
                'price' => '360',
                'original_price' => '450',
                'raw_price' => 360,
                'image' => '/images/cheirosa_mist.png',
                'gallery' => ['/images/cheirosa_mist.png', '/images/bum_bum_cream.png', '/images/sdj_bum_bum_set.png'],
                'category' => 'sol-de-janeiro',
                'category_label' => 'Sol de Janeiro',
                'rating' => 4.9,
                'review_count' => 4120,
                'sizes' => ['90 ml', '240 ml'],
                'description' => 'Le parfum chaud et gourmand qui a tout déclenché. Cheirosa 62 vous transporte instantanément sur les plages de Rio de Janeiro avec ses notes envoûtantes de pistache et de caramel salé.',
                'ingredients' => 'Alcool végétal dénaturé, Eau purifiée, Parfum naturel sans phtalates, Fleur de Tiaré, Noix de coco.',
                'olfactory' => 'Notes de tête : Pistache, Amande. Notes de cœur : Héliotrope, Pétales de jasmin. Notes de fond : Vanille, Caramel salé, Santal.',
                'usage' => 'Vaporisez sans modération sur l\'ensemble du corps, les vêtements et les longueurs des cheveux.',
                'flavors' => [
                    ['name' => 'Cheirosa 62 Iconique', 'color' => '#d97706'],
                ]
            ],
            [
                'id' => 'sdj-5',
                'slug' => 'sol-de-janeiro-ultimate-summer-glow-pack',
                'brand' => 'Sol de Janeiro',
                'name' => 'Coffret Prestige Summer Glow & Mist',
                'subtitle' => 'Pack Complet Soin, Brume Solaire & Shimmer Body Elixir',
                'discount' => '-35%',
                'badge' => 'Édition Prestige Limitée',
                'badge_color' => 'bg-rose-500 text-white',
                'price' => '490',
                'original_price' => '740',
                'raw_price' => 490,
                'image' => '/images/ultimate_bundle.png',
                'gallery' => ['/images/ultimate_bundle.png', '/images/sdj_bum_bum_set.png', '/images/bum_bum_cream.png', '/images/cheirosa_mist.png'],
                'category' => 'sol-de-janeiro',
                'category_label' => 'Sol de Janeiro',
                'rating' => 5.0,
                'review_count' => 1560,
                'sizes' => ['Coffret Cadeau Prestige'],
                'description' => 'Le grand coffret cadeau réunissant toute l\'expérience Sol de Janeiro : soins corporels haute performance, brumes solaires et huiles scintillantes pour illuminer chaque journée d\'une énergie estivale.',
                'ingredients' => 'Beurre de Cupuaçu, Huile de Noix du Brésil, Squalane végétal pur, Vitamine E.',
                'olfactory' => 'Notes signatures : Fleur de Tiaré, Noix de coco givrée, Vanille pure et bois précieux.',
                'usage' => 'Le rituel beauté complet pour le matin et le soir.',
                'flavors' => [
                    ['name' => 'Glow Prestige Edition', 'color' => '#e11d48'],
                ]
            ],
            // ==================== THE ORDINARY ====================
            [
                'id' => 'ordinary-1',
                'slug' => 'the-ordinary-niacinamide-10-zinc-1-serum',
                'brand' => 'The Ordinary',
                'name' => 'The Ordinary — Niacinamide 10% + Zinc 1%',
                'subtitle' => 'Sérum Anti-Imperfections & Contrôle de Sébum (30ml)',
                'discount' => '-20%',
                'badge' => 'N°1 Mondial Sérums',
                'badge_color' => 'bg-zinc-900 text-white',
                'price' => '130',
                'original_price' => '165',
                'raw_price' => 130,
                'image' => '/images/ordinary_niacinamide.png',
                'gallery' => ['/images/ordinary_niacinamide.png', '/images/ordinary_hyaluronic.png', '/images/ordinary_peeling.png'],
                'category' => 'the-ordinary',
                'category_label' => 'The Ordinary',
                'rating' => 4.9,
                'review_count' => 5420,
                'sizes' => ['Format Standard (30 ml)', 'Grand Format (60 ml)'],
                'description' => 'Le sérum culte formulé avec 10% de Niacinamide pure (Vitamine B3) et 1% de Zinc PCA. Il réduit visiblement l\'apparence des imperfections, resserre les pores dilatés et régule l\'excès de sébum pour un teint net, uniforme et matifié.',
                'ingredients' => 'Aqua (Water), Niacinamide 10%, Pentylene Glycol, Zinc PCA 1%, Dimethyl Isosorbide, Tamarindus Indica Seed Gum, Xanthan Gum, Isoceteth-20, Ethoxydiglycol, Phenoxyethanol, Chlorphenesin.',
                'olfactory' => 'Texture : Sérum fluide translucide à absorption rapide, sans parfum synthétique, fini non gras et matifiant.',
                'usage' => 'Appliquez quelques gouttes matin et soir sur l\'ensemble du visage propre avant les crèmes plus épaisses. Ne pas associer directement avec la Vitamine C pure.',
                'flavors' => [
                    ['name' => 'Formule Originelle Pure', 'color' => '#18181b'],
                ]
            ],
            [
                'id' => 'ordinary-2',
                'slug' => 'the-ordinary-hyaluronic-acid-2-b5-serum',
                'brand' => 'The Ordinary',
                'name' => 'The Ordinary — Hyaluronic Acid 2% + B5',
                'subtitle' => 'Sérum Hydratant Repulpant Multi-Moléculaire (30ml)',
                'discount' => '-20%',
                'badge' => 'Hydratation Culte',
                'badge_color' => 'bg-sky-500 text-white',
                'price' => '140',
                'original_price' => '175',
                'raw_price' => 140,
                'image' => '/images/ordinary_hyaluronic.png',
                'gallery' => ['/images/ordinary_hyaluronic.png', '/images/ordinary_niacinamide.png', '/images/ordinary_caffeine.png'],
                'category' => 'the-ordinary',
                'category_label' => 'The Ordinary',
                'rating' => 4.9,
                'review_count' => 3890,
                'sizes' => ['Format Standard (30 ml)', 'Grand Format (60 ml)'],
                'description' => 'Une formule hydratante avancée combinant 3 poids moléculaires d\'acide hyaluronique pur et de la Provitamine B5 pour une hydratation en profondeur et en surface. Repulpe instantanément la peau et lisse les ridules de déshydratation.',
                'ingredients' => 'Aqua (Water), Sodium Hyaluronate (Acide Hyaluronique 2%), Sodium Hyaluronate Crosspolymer, Panthenol (Provitamine B5), Ahnfeltia Concinna Extract, Glycerin, Pentylene Glycol, Citric Acid.',
                'olfactory' => 'Texture : Gel aqueux frais et léger, pénétration instantanée, sans film collant, sans parfum.',
                'usage' => 'Appliquez quelques gouttes sur peau légèrement humide matin et soir avant votre crème hydratante pour sceller l\'hydratation.',
                'flavors' => [
                    ['name' => 'Hydratation B5 Intense', 'color' => '#0284c7'],
                ]
            ],
            [
                'id' => 'ordinary-3',
                'slug' => 'the-ordinary-aha-30-bha-2-peeling-solution',
                'brand' => 'The Ordinary',
                'name' => 'The Ordinary — AHA 30% + BHA 2% Peeling Solution',
                'subtitle' => 'Masque Exfoliant Peeling Rouge Éclat & Grain de Peau (30ml)',
                'discount' => '-25%',
                'badge' => 'Culte & Viral',
                'badge_color' => 'bg-rose-600 text-white',
                'price' => '150',
                'original_price' => '195',
                'raw_price' => 150,
                'image' => '/images/ordinary_peeling.png',
                'gallery' => ['/images/ordinary_peeling.png', '/images/ordinary_glycolic.png', '/images/ordinary_niacinamide.png'],
                'category' => 'the-ordinary',
                'category_label' => 'The Ordinary',
                'rating' => 4.8,
                'review_count' => 4750,
                'sizes' => ['Flacon Goutte 30 ml'],
                'description' => 'Le soin peeling viral rouge rubis qui transforme le grain de peau en 10 minutes. Les AHA (30%) exfolient la surface pour un éclat radieux, tandis que les BHA (2%) désobstruent les pores en profondeur. Enrichi en Poivre de Tasmanie apaisant.',
                'ingredients' => 'Glycolic Acid (AHA), Aqua, Aloe Barbadensis Leaf Water, Sodium Hydroxide, Daucus Carota Sativa Root, Propanediol, Salicylic Acid (BHA 2%), Lactic Acid, Tartaric Acid, Citric Acid, Tasmannia Lanceolata Fruit Extract.',
                'olfactory' => 'Texture : Solution liquide pourpre intense, sans parfum, rinçage facile à l\'eau tiède.',
                'usage' => 'À utiliser 1 à 2 fois par semaine maximum, exclusivement le soir sur peau parfaitement sèche. Laisser poser 10 minutes maximum puis rincer abondamment à l\'eau tiède. Utilisez impérativement une protection solaire SPF en journée.',
                'flavors' => [
                    ['name' => 'Peeling Ruby Intense', 'color' => '#e11d48'],
                ]
            ],
            [
                'id' => 'ordinary-4',
                'slug' => 'the-ordinary-glycolic-acid-7-exfoliating-toner',
                'brand' => 'The Ordinary',
                'name' => 'The Ordinary — Glycolic Acid 7% Toning Solution',
                'subtitle' => 'Lotion Tonique Exfoliante Éclat & Clarté Teint (240ml)',
                'discount' => '-20%',
                'badge' => 'Grand Format 240ml',
                'badge_color' => 'bg-amber-500 text-white',
                'price' => '180',
                'original_price' => '225',
                'raw_price' => 180,
                'image' => '/images/ordinary_glycolic.png',
                'gallery' => ['/images/ordinary_glycolic.png', '/images/ordinary_peeling.png', '/images/ordinary_hyaluronic.png'],
                'category' => 'the-ordinary',
                'category_label' => 'The Ordinary',
                'rating' => 4.9,
                'review_count' => 3100,
                'sizes' => ['Grand Flacon 240 ml'],
                'description' => 'Une solution exfoliante douce à base de 7% d\'Acide Glycolique qui améliore la texture de la peau et restaure son éclat naturel. Formule enrichie en dérivés de baie de poivre de Tasmanie, racine de ginseng et eau de bleuet pour apaiser la peau.',
                'ingredients' => 'Aqua (Water), Glycolic Acid 7%, Rosa Damascena Flower Water, Centaurea Cyanus Flower Water, Aloe Barbadensis Leaf Water, Propanediol, Glycerin, Tasmannia Lanceolata Fruit/Leaf Extract, Panax Ginseng Root Extract.',
                'olfactory' => 'Texture : Tonique limpide rafraîchissant, fini soyeux et non collant.',
                'usage' => 'À appliquer le soir uniquement à l\'aide d\'un coton sur le visage et le cou propres, en évitant le contour des yeux. Ne pas rincer. Idéal également sur les zones du corps (coudes, décolleté).',
                'flavors' => [
                    ['name' => 'Éclat Glycolic 7%', 'color' => '#f59e0b'],
                ]
            ],
            [
                'id' => 'ordinary-5',
                'slug' => 'the-ordinary-caffeine-solution-5-egcg-eye-serum',
                'brand' => 'The Ordinary',
                'name' => 'The Ordinary — Caffeine Solution 5% + EGCG',
                'subtitle' => 'Sérum Contour des Yeux Anti-Cernes & Anti-Poches (30ml)',
                'discount' => '-20%',
                'badge' => 'Regard Reposé',
                'badge_color' => 'bg-emerald-600 text-white',
                'price' => '140',
                'original_price' => '175',
                'raw_price' => 140,
                'image' => '/images/ordinary_caffeine.png',
                'gallery' => ['/images/ordinary_caffeine.png', '/images/ordinary_niacinamide.png', '/images/ordinary_hyaluronic.png'],
                'category' => 'the-ordinary',
                'category_label' => 'The Ordinary',
                'rating' => 4.8,
                'review_count' => 2840,
                'sizes' => ['Flacon Pipette 30 ml'],
                'description' => 'Une formule ultraconcentrée combinant 5% de caféine pure et de l\'EGCG purifié issu de feuilles de thé vert. Cible efficacement les cernes pigmentaires, réduit les poches et réveille le contour du regard fatigué.',
                'ingredients' => 'Aqua (Water), Caffeine 5%, Maltodextrin, Glycerin, Propanediol, Epigallocatechin Gallatyl Glucoside (EGCG), Gallyl Glucoside, Hyaluronic Acid, Melanin, Glycine Soja Seed Extract, Hydroxyethylcellulose.',
                'olfactory' => 'Texture : Sérum fluide ambré ultraléger, pénètre en quelques secondes sans graisser.',
                'usage' => 'Massez délicatement une petite quantité sur le contour des yeux matin et soir sur peau propre.',
                'flavors' => [
                    ['name' => 'Énergie Caféine 5%', 'color' => '#059669'],
                ]
            ],
        ];
    }

    public static function getCategories()
    {
        return [
            ['slug' => 'all', 'name' => 'Tous les packs & produits', 'count' => 37],
            ['slug' => 'victorias-secret', 'name' => 'Victoria\'s Secret', 'count' => 20],
            ['slug' => 'sol-de-janeiro', 'name' => 'Sol de Janeiro', 'count' => 7],
            ['slug' => 'rituals', 'name' => 'Rituals', 'count' => 5],
            ['slug' => 'the-ordinary', 'name' => 'The Ordinary', 'count' => 5],
        ];
    }

    /**
     * Database-backed catalog. The static catalog is retained only as a safe
     * fallback until the initial import has been run.
     */
    public static function catalogProducts(): array
    {
        $products = Product::query()
            ->with(['category', 'sizes', 'flavors'])
            ->whereHas('category', fn ($query) => $query->where('is_active', true))
            ->orderBy('sort_order')
            ->get();

        return $products->isNotEmpty()
            ? $products->map(fn (Product $product) => $product->toStorefrontArray())->all()
            : self::getProducts();
    }

    public static function catalogCategories(): array
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->withCount(['products as count'])
            ->orderBy('sort_order')
            ->get();

        if ($categories->isEmpty()) {
            return self::getCategories();
        }

        $totalCount = Product::whereHas('category', fn ($query) => $query->where('is_active', true))
            ->count();

        return array_merge([['slug' => 'all', 'name' => 'Tous les packs & produits', 'count' => $totalCount]], $categories
            ->map(fn (Category $category) => ['slug' => $category->slug, 'name' => $category->name, 'count' => $category->count])
            ->all());
    }

    public function index(Request $request, $category = null)
    {
        $allProducts = self::catalogProducts();
        $categories = self::catalogCategories();

        $selectedCategory = $category ?? $request->query('category', 'all');
        if ($selectedCategory === 'ordinary') {
            $selectedCategory = 'the-ordinary';
        } elseif ($selectedCategory === 'victoria-secret') {
            $selectedCategory = 'victorias-secret';
        }
        $sortBy = $request->query('sort', 'popular');
        $searchQuery = trim($request->query('q', ''));

        $products = $allProducts;

        // 1. Filter by Search Query if present
        if (!empty($searchQuery)) {
            $qLower = mb_strtolower($searchQuery);
            $products = array_values(array_filter($products, function ($p) use ($qLower) {
                $searchable = mb_strtolower(
                    $p['name'] . ' ' .
                    $p['subtitle'] . ' ' .
                    $p['brand'] . ' ' .
                    $p['category_label'] . ' ' .
                    $p['description'] . ' ' .
                    $p['olfactory']
                );
                return str_contains($searchable, $qLower);
            }));
        }

        // 2. Filter by Category
        if ($selectedCategory && $selectedCategory !== 'all') {
            $products = array_values(array_filter($products, function ($p) use ($selectedCategory) {
                return $p['category'] === $selectedCategory;
            }));
        }

        // 3. Sorting
        if ($sortBy === 'price-asc') {
            usort($products, fn($a, $b) => $a['raw_price'] <=> $b['raw_price']);
        } elseif ($sortBy === 'price-desc') {
            usort($products, fn($a, $b) => $b['raw_price'] <=> $a['raw_price']);
        } elseif ($sortBy === 'rating') {
            usort($products, fn($a, $b) => $b['rating'] <=> $a['rating']);
        }

        return view('shop.index', compact('products', 'categories', 'selectedCategory', 'sortBy', 'searchQuery'));
    }

    public function apiSearch(Request $request)
    {
        $query = mb_strtolower(trim($request->query('q', '')));
        if (empty($query)) {
            return response()->json(['results' => [], 'count' => 0]);
        }

        $allProducts = self::catalogProducts();
        $results = [];

        foreach ($allProducts as $p) {
            $searchable = mb_strtolower(
                $p['name'] . ' ' .
                $p['subtitle'] . ' ' .
                $p['brand'] . ' ' .
                $p['category_label'] . ' ' .
                $p['description'] . ' ' .
                $p['olfactory']
            );

            if (str_contains($searchable, $query)) {
                $results[] = [
                    'name' => $p['name'],
                    'subtitle' => $p['subtitle'],
                    'slug' => $p['slug'],
                    'url' => route('shop.product', $p['slug']),
                    'image' => $p['image'],
                    'price' => $p['price'] . ' DH',
                    'original_price' => $p['original_price'] . ' DH',
                    'discount' => $p['discount'],
                    'badge' => $p['badge'],
                    'category' => $p['category_label'],
                ];
            }
        }

        return response()->json([
            'query' => $query,
            'count' => count($results),
            'results' => array_slice($results, 0, 6)
        ]);
    }

    public function showProduct($slug)
    {
        $allProducts = self::catalogProducts();
        $product = null;

        foreach ($allProducts as $p) {
            if ($p['slug'] === $slug || $p['id'] === $slug) {
                $product = $p;
                break;
            }
        }

        if (!$product) {
            abort(404, 'Produit introuvable');
        }

        // Related products (same brand first, or others)
        $relatedProducts = array_values(array_filter($allProducts, fn($p) => $p['id'] !== $product['id'] && $p['category'] === $product['category']));
        if (count($relatedProducts) < 3) {
            $otherProducts = array_values(array_filter($allProducts, fn($p) => $p['id'] !== $product['id'] && $p['category'] !== $product['category']));
            $relatedProducts = array_merge($relatedProducts, array_slice($otherProducts, 0, 3 - count($relatedProducts)));
        }

        return view('shop.product', compact('product', 'relatedProducts'));
    }
}
