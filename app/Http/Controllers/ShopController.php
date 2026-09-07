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
            // ==================== RITUALS ====================
            [
                'id' => 'rituals-pack-xs',
                'slug' => 'rituals-pack-xs',
                'brand' => 'Rituals',
                'name' => 'Coffret Rituals Pack XS',
                'subtitle' => 'Format Découverte XS (Disponible en 4 Rituels)',
                'discount' => null,
                'badge' => 'Découverte',
                'badge_color' => 'bg-pink-500 text-white',
                'price' => '250',
                'original_price' => '250',
                'raw_price' => 250,
                'image' => '/images/rituals_pack_xs.png',
                'gallery' => ['/images/rituals_pack_xs.png', '/images/rituals_ayurveda.png', '/images/rituals_sakura.png', '/images/rituals_jing.png'],
                'category' => 'rituals',
                'category_label' => 'Rituals',
                'rating' => 4.9,
                'review_count' => 850,
                'sizes' => ['Format XS (Découverte)'],
                'description' => 'Le coffret découverte format XS de Rituals. Un rituel de bien-être complet dans un magnifique coffret origami prêt à offrir. Choisissez votre fragrance préférée parmi les 4 rituels iconiques.',
                'ingredients' => 'Formules enrichies en extraits botaniques naturels bienfaisants, sans parabènes, testées sous contrôle dermatologique.',
                'olfactory' => 'Disponible en 4 rituels au choix : Rouge (Ayurveda), Rose (Sakura), Vert (Jing/Karma), Violet (Yozakura).',
                'usage' => 'Idéal pour une routine bien-être quotidienne ou comme cadeau raffiné.',
                'flavors' => [
                    ['name' => 'Rouge (The Ritual of Ayurveda)', 'color' => '#dc2626'],
                    ['name' => 'Rose (The Ritual of Sakura)', 'color' => '#f472b6'],
                    ['name' => 'Vert (The Ritual of Jing / Karma)', 'color' => '#059669'],
                    ['name' => 'Violet (The Ritual of Yozakura)', 'color' => '#9333ea'],
                ]
            ],
            [
                'id' => 'rituals-pack-s',
                'slug' => 'rituals-pack-s',
                'brand' => 'Rituals',
                'name' => 'Coffret Rituals Pack S',
                'subtitle' => 'Coffret Cadeau Bien-Être Format S (Disponible en 4 Rituels)',
                'discount' => null,
                'badge' => 'Coup de Cœur',
                'badge_color' => 'bg-rose-500 text-white',
                'price' => '360',
                'original_price' => '360',
                'raw_price' => 360,
                'image' => '/images/rituals_pack_s.png',
                'gallery' => ['/images/rituals_pack_s.png', '/images/rituals_ayurveda.png', '/images/rituals_sakura.png', '/images/rituals_jing.png'],
                'category' => 'rituals',
                'category_label' => 'Rituals',
                'rating' => 4.9,
                'review_count' => 1420,
                'sizes' => ['Format S (Standard)'],
                'description' => 'Le coffret cadeau format Small de Rituals composé de soins essentiels pour transformer chaque journée en moment de pure détente. Présenté dans un emballage origami de luxe avec ruban.',
                'ingredients' => 'Mousse de douche onctueuse, gommage corps exfoliant aux sels minéraux et crème hydratante soyeuse.',
                'olfactory' => '4 rituels au choix : Rouge (Ayurveda), Rose (Sakura), Vert (Jing/Karma), Violet (Yozakura).',
                'usage' => 'Appliquez la mousse de douche sur peau humide, exfoliez en douceur avec le gommage puis hydratez avec la crème corps.',
                'flavors' => [
                    ['name' => 'Rouge (The Ritual of Ayurveda)', 'color' => '#dc2626'],
                    ['name' => 'Rose (The Ritual of Sakura)', 'color' => '#f472b6'],
                    ['name' => 'Vert (The Ritual of Jing / Karma)', 'color' => '#059669'],
                    ['name' => 'Violet (The Ritual of Yozakura)', 'color' => '#9333ea'],
                ]
            ],
            [
                'id' => 'rituals-pack-m',
                'slug' => 'rituals-pack-m',
                'brand' => 'Rituals',
                'name' => 'Coffret Rituals Pack M',
                'subtitle' => 'Coffret Cadeau Prestige Format M (Disponible en 4 Rituels)',
                'discount' => null,
                'badge' => 'Prestige Deluxe',
                'badge_color' => 'bg-amber-600 text-white',
                'price' => '520',
                'original_price' => '520',
                'raw_price' => 520,
                'image' => '/images/rituals_pack_m.png',
                'gallery' => ['/images/rituals_pack_m.png', '/images/rituals_ayurveda.png', '/images/rituals_sakura.png', '/images/rituals_jing.png'],
                'category' => 'rituals',
                'category_label' => 'Rituals',
                'rating' => 5.0,
                'review_count' => 980,
                'sizes' => ['Format M (Prestige Deluxe)'],
                'description' => 'Le grand coffret prestige Medium de Rituals. Une expérience sensorielle complète réunissant les formats généreux des soins corps iconiques et une bougie d\'ambiance parfumée.',
                'ingredients' => 'Mousse de douche grand format, gommage corps aux cristaux précieux, crème corps veloutée et bougie parfumée d\'intérieur.',
                'olfactory' => '4 univers olfactifs au choix : Rouge (Ayurveda), Rose (Sakura), Vert (Jing/Karma), Violet (Yozakura).',
                'usage' => 'Le rituel complet pour une parenthèse spa luxueuse chez soi.',
                'flavors' => [
                    ['name' => 'Rouge (The Ritual of Ayurveda)', 'color' => '#dc2626'],
                    ['name' => 'Rose (The Ritual of Sakura)', 'color' => '#f472b6'],
                    ['name' => 'Vert (The Ritual of Jing / Karma)', 'color' => '#059669'],
                    ['name' => 'Violet (The Ritual of Yozakura)', 'color' => '#9333ea'],
                ]
            ],
            [
                'id' => 'rituals-mini-karma',
                'slug' => 'rituals-mini-the-ritual-of-karma',
                'brand' => 'Rituals',
                'name' => 'The Ritual of Karma — Summer Essentials On The Go Set',
                'subtitle' => 'Set Voyage 3 Pièces Mini (Mousse de Douche, Lotion & Huile Scintillante)',
                'discount' => null,
                'badge' => 'Édition Nomade',
                'badge_color' => 'bg-teal-600 text-white',
                'price' => '150',
                'original_price' => '150',
                'raw_price' => 150,
                'image' => '/images/rituals_mini_karma.png',
                'gallery' => ['/images/rituals_mini_karma.png', '/images/rituals_karma.png'],
                'category' => 'rituals',
                'category_label' => 'Rituals',
                'rating' => 4.8,
                'review_count' => 620,
                'sizes' => ['Format Voyage (3 pièces)'],
                'description' => 'Le set nomade 3 pièces The Ritual of Karma. Conçu pour prolonger la sensation de l\'été où que vous soyez. Infusé au Lotus Blanc sacré et au Thé Blanc biologique protecteur.',
                'ingredients' => 'Mousse de douche 50ml, Lotion corps hydratante 70ml, Huile scintillante corps 30ml.',
                'olfactory' => 'Notes florales délicates de Lotus Blanc et fraîcheur revitalisante de Thé Blanc.',
                'usage' => 'Formats voyages idéaux à glisser dans votre sac à main ou valise pour rester fraîche toute la journée.',
                'flavors' => [
                    ['name' => 'The Ritual of Karma', 'color' => '#0d9488']
                ]
            ],

            // ==================== SOL DE JANEIRO ====================
            [
                'id' => 'sdj-mist-62',
                'slug' => 'sol-de-janeiro-cheirosa-62-brume-parfumee',
                'brand' => 'Sol de Janeiro',
                'name' => 'Cheirosa 62 Brume Parfumée (Jaune)',
                'subtitle' => 'Pistache Gourmande & Caramel Salé Solaire (Flacon 90ml)',
                'discount' => null,
                'badge' => 'Best-Seller Iconique',
                'badge_color' => 'bg-amber-500 text-white',
                'price' => '340',
                'original_price' => '340',
                'raw_price' => 340,
                'image' => '/images/sdj_cheirosa_62.png',
                'gallery' => ['/images/sdj_cheirosa_62.png', '/images/sdj_mists_set.png', '/images/sdj_cheirosa_68.png'],
                'category' => 'sol-de-janeiro',
                'category_label' => 'Sol de Janeiro',
                'rating' => 4.9,
                'review_count' => 2450,
                'sizes' => ['Flacon 90 ml'],
                'description' => 'La brume parfumée emblématique de Sol de Janeiro. Cheirosa 62 vous transporte immédiatement sur les plages chaudes de Rio de Janeiro avec son accord irrésistible de pistache grillée et caramel salé.',
                'ingredients' => 'Alcohol Denat., Aqua (Water, Eau), Parfum (Fragrance), Benzyl Alcohol, Benzyl Salicylate, Coumarin, Limonene.',
                'olfactory' => 'Notes de tête : Pistache, Amande. Notes de cœur : Héliotrope, Pétales de Jasmin. Notes de fond : Vanille, Caramel Salé, Bois de Santal.',
                'usage' => 'Vaporisez sur le corps, les cheveux et les vêtements du matin au soir.',
                'flavors' => [
                    ['name' => 'Jaune (Cheirosa 62)', 'color' => '#f59e0b']
                ]
            ],
            [
                'id' => 'sdj-mist-68',
                'slug' => 'sol-de-janeiro-cheirosa-68-beija-flor-brume-parfumee',
                'brand' => 'Sol de Janeiro',
                'name' => 'Cheirosa 68 Beija Flor Brume Parfumée (Rose)',
                'subtitle' => 'Fruit du Dragon Rose & Jasmin du Brésil Solaire (Flacon 90ml)',
                'discount' => null,
                'badge' => 'Floral Fruité',
                'badge_color' => 'bg-pink-500 text-white',
                'price' => '340',
                'original_price' => '340',
                'raw_price' => 340,
                'image' => '/images/sdj_cheirosa_68.png',
                'gallery' => ['/images/sdj_cheirosa_68.png', '/images/sdj_mists_set.png', '/images/sdj_cheirosa_62.png'],
                'category' => 'sol-de-janeiro',
                'category_label' => 'Sol de Janeiro',
                'rating' => 4.9,
                'review_count' => 1890,
                'sizes' => ['Flacon 90 ml'],
                'description' => 'Une brume florale fruitée étincelante et aérienne. Cheirosa 68 célèbre le jardin luxuriant de Rio avec des notes de fruit du dragon rose, de litchi juteux et de jasmin du Brésil.',
                'ingredients' => 'Alcohol Denat., Aqua (Water, Eau), Parfum (Fragrance), Benzyl Salicylate, Hydroxycitronellal, Limonene, Linalool.',
                'olfactory' => 'Notes de tête : Fruit du Dragon Rose, Essence de Litchi. Notes de cœur : Jasmin du Brésil, Brise Océanique, Hibiscus. Notes de fond : Vanille Translucide, Musc Solaire.',
                'usage' => 'Vaporisez de la tête aux pieds pour une aura florale fraîche et vivifiante.',
                'flavors' => [
                    ['name' => 'Rose (Cheirosa 68)', 'color' => '#ec4899']
                ]
            ],
            [
                'id' => 'sdj-mist-40',
                'slug' => 'sol-de-janeiro-cheirosa-40-bom-dia-bright-brume-parfumee',
                'brand' => 'Sol de Janeiro',
                'name' => 'Cheirosa 40 Bom Dia Bright Brume Parfumée (Rouge)',
                'subtitle' => 'Prune Noire Ambrée & Bois de Vanille Sensuelle (Flacon 90ml)',
                'discount' => null,
                'badge' => 'Sensuel & Ambré',
                'badge_color' => 'bg-red-500 text-white',
                'price' => '340',
                'original_price' => '340',
                'raw_price' => 340,
                'image' => '/images/sdj_cheirosa_40.png',
                'gallery' => ['/images/sdj_cheirosa_40.png', '/images/sdj_mists_set.png', '/images/sdj_cheirosa_59.png'],
                'category' => 'sol-de-janeiro',
                'category_label' => 'Sol de Janeiro',
                'rating' => 4.8,
                'review_count' => 1540,
                'sizes' => ['Flacon 90 ml'],
                'description' => 'Une fragrance enivrante, sophistiquée et chaleureuse. Cheirosa 40 mêle la prune noire ambrée veloutée aux fleurs de jasmin et au bois de vanille richement texturé.',
                'ingredients' => 'Alcohol Denat., Aqua (Water, Eau), Parfum (Fragrance), Benzyl Salicylate, Coumarin, Limonene, Linalool.',
                'olfactory' => 'Notes de tête : Prune Noire Ambrée, Crème de Cassis. Notes de cœur : Fleurs de Jasmin, Orchidée du Brésil. Notes de fond : Bois de Vanille, Musc Chaud.',
                'usage' => 'Vaporisez généreusement sur la peau et les vêtements pour un sillage captivant.',
                'flavors' => [
                    ['name' => 'Rouge (Cheirosa 40)', 'color' => '#ef4444']
                ]
            ],
            [
                'id' => 'sdj-mist-59',
                'slug' => 'sol-de-janeiro-cheirosa-59-delicia-drench-brume-parfumee',
                'brand' => 'Sol de Janeiro',
                'name' => 'Cheirosa 59 Delícia Drench Brume Parfumée (Violet)',
                'subtitle' => 'Orchidée Vanille Veloutée & Santal Réconfortant (Flacon 90ml)',
                'discount' => null,
                'badge' => 'Nouveauté Culte',
                'badge_color' => 'bg-purple-600 text-white',
                'price' => '340',
                'original_price' => '340',
                'raw_price' => 340,
                'image' => '/images/sdj_cheirosa_59.png',
                'gallery' => ['/images/sdj_cheirosa_59.png', '/images/sdj_mists_set.png', '/images/sdj_cheirosa_62.png'],
                'category' => 'sol-de-janeiro',
                'category_label' => 'Sol de Janeiro',
                'rating' => 4.9,
                'review_count' => 1720,
                'sizes' => ['Flacon 90 ml'],
                'description' => 'Une fragrance boisée gourmande réconfortante et addictive. Cheirosa 59 enveloppe l\'esprit d\'un nuage apaisant d\'orchidée vanille, de violette sucrée et de bois de santal crémeux.',
                'ingredients' => 'Alcohol Denat., Aqua (Water, Eau), Parfum (Fragrance), Alpha-Isomethyl Ionone, Hydroxycitronellal.',
                'olfactory' => 'Notes de tête : Prune Veloutée, Violette Sucrée. Notes de cœur : Orchidée Vanille, Ambre Blanc. Notes de fond : Bois de Santal Éthéré, Vétiver Frais.',
                'usage' => 'Vaporisez sur les cheveux et le corps pour une sensation de cocon parfumé.',
                'flavors' => [
                    ['name' => 'Violet (Cheirosa 59)', 'color' => '#a855f7']
                ]
            ],

            // ==================== GARDEN BOUQUET ====================
            [
                'id' => 'garden-bouquet-coffret',
                'slug' => 'coffret-garden-bouquet-soins-bain-6-pieces',
                'brand' => 'Garden Bouquet',
                'name' => 'Coffret Garden Bouquet — Bain & Soins Corps (6 Pièces)',
                'subtitle' => 'Shower Gel 100ml, Body Lotion 95ml, Bath Cream 100ml, Body Scrub 95ml, Bath Crystals 100g & Savon 50g',
                'discount' => null,
                'badge' => 'Coffret Cadeau 6 Pièces',
                'badge_color' => 'bg-emerald-600 text-white',
                'price' => '120',
                'original_price' => '120',
                'raw_price' => 120,
                'image' => '/images/garden_bouquet_coffret.png',
                'gallery' => ['/images/garden_bouquet_coffret.png'],
                'category' => 'garden-bouquet',
                'category_label' => 'Garden Bouquet',
                'rating' => 4.8,
                'review_count' => 530,
                'sizes' => ['Coffret Complet 6 Pièces'],
                'description' => 'Un coffret cadeau complet et généreux de 6 soins pour le bain et le corps Garden Bouquet. Présenté dans une sublime boîte florale aux finitions dorées, il comprend un gel douche 100ml, une lotion corps 95ml, une crème de bain 100ml, un gommage 95ml, des cristaux de bain 100g et un savon 50g.',
                'ingredients' => 'Formules douces enrichies en glycérine hydratante et extraits floraux naturels.',
                'olfactory' => 'Bouquet floral raffiné mêlant pivoines délicates, roses fraîches et fleurs printanières.',
                'usage' => 'Le rituel de bain complet pour se détendre et parfumer délicatement la peau.',
                'flavors' => [
                    ['name' => 'Garden Bouquet Floral', 'color' => '#ec4899']
                ]
            ],

            // ==================== THE ORDINARY ====================
            [
                'id' => 'ordinary-glycolic-100ml',
                'slug' => 'the-ordinary-glycolic-acid-7-100ml',
                'brand' => 'The Ordinary',
                'name' => 'The Ordinary — Glycolic Acid 7% Exfoliating Toner (100ml)',
                'subtitle' => 'Solution Tonifiante Exfoliante à l\'Acide Glycolique 7% (Format 100ml)',
                'discount' => null,
                'badge' => 'Éclat & Teint Lisse',
                'badge_color' => 'bg-zinc-800 text-white',
                'price' => '160',
                'original_price' => '160',
                'raw_price' => 160,
                'image' => '/images/ordinary_glycolic_100ml.png',
                'gallery' => ['/images/ordinary_glycolic_100ml.png', '/images/ordinary_glycolic_240ml.png'],
                'category' => 'the-ordinary',
                'category_label' => 'The Ordinary',
                'rating' => 4.9,
                'review_count' => 1450,
                'sizes' => ['Format 100 ml'],
                'description' => 'Solution tonifiante à base d\'acide glycolique 7% conçue pour améliorer l\'éclat de la peau, unifier le teint et lisser la texture cutanée en douceur.',
                'ingredients' => 'Aqua (Water), Glycolic Acid, Rosa Damascena Flower Water, Centaurea Cyanus Flower Water, Aloe Barbadensis Leaf Water, Propanediol, Glycerin, Tasmannia Lanceolata Extract.',
                'olfactory' => 'Formule pure sans parfum ajouté.',
                'usage' => 'Appliquer idéalement le soir après le nettoyage, à l\'aide d\'un coton sur le visage et le cou. Ne pas rincer.',
                'flavors' => [
                    ['name' => 'Acide Glycolique 7%', 'color' => '#f59e0b']
                ]
            ],
            [
                'id' => 'ordinary-glycolic-240ml',
                'slug' => 'the-ordinary-glycolic-acid-7-240ml',
                'brand' => 'The Ordinary',
                'name' => 'The Ordinary — Glycolic Acid 7% Exfoliating Toner (240ml)',
                'subtitle' => 'Solution Tonifiante Exfoliante à l\'Acide Glycolique 7% (Grand Format 240ml)',
                'discount' => null,
                'badge' => 'Grand Format Éco',
                'badge_color' => 'bg-zinc-800 text-white',
                'price' => '220',
                'original_price' => '220',
                'raw_price' => 220,
                'image' => '/images/ordinary_glycolic_240ml.png',
                'gallery' => ['/images/ordinary_glycolic_240ml.png', '/images/ordinary_glycolic_100ml.png'],
                'category' => 'the-ordinary',
                'category_label' => 'The Ordinary',
                'rating' => 4.9,
                'review_count' => 2890,
                'sizes' => ['Grand Format 240 ml'],
                'description' => 'Le grand format économique 240ml de la célèbre solution tonifiante exfoliante The Ordinary. Révèle l\'éclat naturel de la peau et affine le grain de peau.',
                'ingredients' => 'Aqua (Water), Glycolic Acid, Rosa Damascena Flower Water, Centaurea Cyanus Flower Water, Aloe Barbadensis Leaf Water, Propanediol, Glycerin, Tasmannia Lanceolata Extract.',
                'olfactory' => 'Formule pure sans parfum.',
                'usage' => 'Utiliser le soir, une fois par jour. Éviter le contour des yeux.',
                'flavors' => [
                    ['name' => 'Acide Glycolique 7%', 'color' => '#f59e0b']
                ]
            ],
            [
                'id' => 'ordinary-niacinamide-30ml',
                'slug' => 'the-ordinary-niacinamide-10-zinc-1-30ml',
                'brand' => 'The Ordinary',
                'name' => 'The Ordinary — Niacinamide 10% + Zinc 1% (30ml)',
                'subtitle' => 'Sérum Anti-Imperfections & Régulateur de Sébum (Flacon 30ml)',
                'discount' => null,
                'badge' => 'N°1 Anti-Imperfections',
                'badge_color' => 'bg-zinc-800 text-white',
                'price' => '160',
                'original_price' => '160',
                'raw_price' => 160,
                'image' => '/images/ordinary_niacinamide_30ml.png',
                'gallery' => ['/images/ordinary_niacinamide_30ml.png', '/images/ordinary_niacinamide_60ml.png'],
                'category' => 'the-ordinary',
                'category_label' => 'The Ordinary',
                'rating' => 4.9,
                'review_count' => 3420,
                'sizes' => ['Format 30 ml'],
                'description' => 'Formule minérale concentrée en vitamines haute puissance. Réduit visiblement l\'apparence des imperfections, resserre les pores dilatés et équilibre l\'excès de sébum.',
                'ingredients' => 'Aqua (Water), Niacinamide 10%, Pentylene Glycol, Zinc PCA 1%, Dimethyl Isosorbide, Tamarindus Indica Seed Gum, Xanthan Gum.',
                'olfactory' => 'Sans parfum, texture sérum fluide ultralégère.',
                'usage' => 'Appliquer quelques gouttes matin et soir sur l\'ensemble du visage avant les crèmes hydratantes.',
                'flavors' => [
                    ['name' => 'Niacinamide 10% + Zinc 1%', 'color' => '#64748b']
                ]
            ],
            [
                'id' => 'ordinary-niacinamide-60ml',
                'slug' => 'the-ordinary-niacinamide-10-zinc-1-60ml',
                'brand' => 'The Ordinary',
                'name' => 'The Ordinary — Niacinamide 10% + Zinc 1% (60ml)',
                'subtitle' => 'Sérum Anti-Imperfections & Régulateur de Sébum (Grand Format 60ml)',
                'discount' => null,
                'badge' => 'Grand Format Éco',
                'badge_color' => 'bg-zinc-800 text-white',
                'price' => '240',
                'original_price' => '240',
                'raw_price' => 240,
                'image' => '/images/ordinary_niacinamide_60ml.png',
                'gallery' => ['/images/ordinary_niacinamide_60ml.png', '/images/ordinary_niacinamide_30ml.png'],
                'category' => 'the-ordinary',
                'category_label' => 'The Ordinary',
                'rating' => 4.9,
                'review_count' => 1980,
                'sizes' => ['Grand Format 60 ml'],
                'description' => 'Le double format économique 60ml du sérum culte Niacinamide 10% + Zinc 1%. Régule le sébum et affine le grain de peau durablement.',
                'ingredients' => 'Aqua (Water), Niacinamide 10%, Pentylene Glycol, Zinc PCA 1%, Dimethyl Isosorbide, Tamarindus Indica Seed Gum, Xanthan Gum.',
                'olfactory' => 'Sans parfum, texture sérum fluide agréable.',
                'usage' => 'Appliquer quelques gouttes matin et soir sur peau propre.',
                'flavors' => [
                    ['name' => 'Niacinamide 10% + Zinc 1%', 'color' => '#64748b']
                ]
            ],

            // ==================== VICTORIA'S SECRET — BRUMES PARFUMÉES (PROMO) ====================
            [
                'id' => 'vs-mist-love-spell',
                'slug' => 'victorias-secret-love-spell-brume-parfumee',
                'brand' => 'Victoria\'s Secret',
                'name' => 'Brume Parfumée Love Spell',
                'subtitle' => 'Fleur de Cerisier & Pêche Gorgée de Soleil (Flacon 250ml)',
                'discount' => '-14%',
                'badge' => 'Offre Promo',
                'badge_color' => 'bg-purple-600 text-white',
                'price' => '240',
                'original_price' => '280',
                'raw_price' => 240,
                'image' => '/images/vs_mist_love_spell.png',
                'gallery' => ['/images/vs_mist_love_spell.png', '/images/vs_mist_bare_vanilla.png', '/images/vs_mist_aqua_kiss.png', '/images/vs_mist_amber_romance.png'],
                'category' => 'victorias-secret',
                'category_label' => 'Victoria\'s Secret',
                'rating' => 4.9,
                'review_count' => 3120,
                'sizes' => ['Format Standard (250 ml)'],
                'description' => 'Le philtre d\'amour iconique de Victoria\'s Secret. Love Spell marie l\'éclat vibrant de la pêche de vigne juteuse et la délicatesse poétique de la fleur de cerisier japonais. Une fragrance fraîche, féminine et intemporelle.',
                'ingredients' => 'Alcohol Denat., Aqua, Fragrance (Parfum), Cherry Blossom Extract, Peach Juice Extract, Aloe Vera, Chamomile Extract.',
                'olfactory' => 'Notes olfactives : Fleur de cerisier japonais, Pêche mûre juteuse, Jasmin blanc.',
                'usage' => 'Vaporisez généreusement sur les points de pulsation et les longueurs pour une fraîcheur florale persistante.',
                'flavors' => [
                    ['name' => 'Love Spell Classic', 'color' => '#9333ea']
                ]
            ],
            [
                'id' => 'vs-mist-aqua-kiss',
                'slug' => 'victorias-secret-aqua-kiss-brume-parfumee',
                'brand' => 'Victoria\'s Secret',
                'name' => 'Brume Parfumée Aqua Kiss',
                'subtitle' => 'Eaux Fraîches & Marguerites Éclatantes (Flacon 250ml)',
                'discount' => '-14%',
                'badge' => 'Offre Promo',
                'badge_color' => 'bg-cyan-600 text-white',
                'price' => '240',
                'original_price' => '280',
                'raw_price' => 240,
                'image' => '/images/vs_mist_aqua_kiss.png',
                'gallery' => ['/images/vs_mist_aqua_kiss.png', '/images/vs_mist_bare_vanilla.png', '/images/vs_mist_love_spell.png', '/images/vs_mist_amber_romance.png'],
                'category' => 'victorias-secret',
                'category_label' => 'Victoria\'s Secret',
                'rating' => 4.8,
                'review_count' => 2240,
                'sizes' => ['Format Standard (250 ml)'],
                'description' => 'Une bouffée de fraîcheur pure et aquatique. Aqua Kiss combine des notes d\'eaux vives et de marguerites éclatantes pour un parfum frais, vivifiant et subtilement sensuel.',
                'ingredients' => 'Alcohol Denat., Aqua/Water, Fragrance (Parfum), Daisy Flower Extract, Rain Water Accord, Glycerin, Aloe Barbadensis Leaf Extract.',
                'olfactory' => 'Notes olfactives : Eaux fraîches tonifiantes, Marguerite sauvage, Musc blanc propre.',
                'usage' => 'Vaporisez sur l\'ensemble du corps après la douche pour une fraîcheur éclatante.',
                'flavors' => [
                    ['name' => 'Aqua Kiss Classic', 'color' => '#0891b2']
                ]
            ],
            [
                'id' => 'vs-mist-bare-vanilla',
                'slug' => 'victorias-secret-bare-vanilla-brume-parfumee',
                'brand' => 'Victoria\'s Secret',
                'name' => 'Brume Parfumée Bare Vanilla',
                'subtitle' => 'Vanille Fouettée & Cachemire Doux (Flacon 250ml)',
                'discount' => '-14%',
                'badge' => 'N°1 des Ventes',
                'badge_color' => 'bg-amber-600 text-white',
                'price' => '240',
                'original_price' => '280',
                'raw_price' => 240,
                'image' => '/images/vs_mist_bare_vanilla.png',
                'gallery' => ['/images/vs_mist_bare_vanilla.png', '/images/vs_mist_love_spell.png', '/images/vs_mist_aqua_kiss.png', '/images/vs_mist_amber_romance.png'],
                'category' => 'victorias-secret',
                'category_label' => 'Victoria\'s Secret',
                'rating' => 4.9,
                'review_count' => 3840,
                'sizes' => ['Format Standard (250 ml)'],
                'description' => 'La brume corporelle la plus vendue au monde. Bare Vanilla enveloppe le corps d\'un nuage gourmand et chaleureux mêlant la vanille fouettée crémeuse et le cachemire soyeux.',
                'ingredients' => 'Alcohol Denat., Aqua/Water/Eau, Fragrance (Parfum), Vanilla Tahitensis Fruit Extract, Glycerin, Propylene Glycol, PPG-26-Buteth-26, Aloe Extract.',
                'olfactory' => 'Notes olfactives : Vanille fouettée gourmande, Cachemire doux velouté, Peau chaude sensuelle.',
                'usage' => 'Vaporisez généreusement sur le corps, le cou et les vêtements.',
                'flavors' => [
                    ['name' => 'Bare Vanilla Classic', 'color' => '#d97706']
                ]
            ],
            [
                'id' => 'vs-mist-amber-romance',
                'slug' => 'victorias-secret-amber-romance-brume-parfumee',
                'brand' => 'Victoria\'s Secret',
                'name' => 'Brume Parfumée Amber Romance',
                'subtitle' => 'Ambre Profond & Baisers Sucrés (Flacon 250ml)',
                'discount' => '-14%',
                'badge' => 'Offre Promo',
                'badge_color' => 'bg-amber-700 text-white',
                'price' => '240',
                'original_price' => '280',
                'raw_price' => 240,
                'image' => '/images/vs_mist_amber_romance.png',
                'gallery' => ['/images/vs_mist_amber_romance.png', '/images/vs_mist_bare_vanilla.png', '/images/vs_mist_love_spell.png', '/images/vs_mist_aqua_kiss.png'],
                'category' => 'victorias-secret',
                'category_label' => 'Victoria\'s Secret',
                'rating' => 4.8,
                'review_count' => 1850,
                'sizes' => ['Format Standard (250 ml)'],
                'description' => 'Une fragrance sensuelle, ambrée et chaleureuse. Amber Romance marie l\'ambre doré somptueux et la douceur réconfortante de la crème anglaise.',
                'ingredients' => 'Alcohol Denat., Aqua, Fragrance (Parfum), Amber Resin Extract, Vanilla Bean Accord, Aloe Vera, Chamomile Extract.',
                'olfactory' => 'Notes olfactives : Ambre doré profond, Crème anglaise gourmande, Bois de santal soyeux.',
                'usage' => 'Vaporisez sur les points de pulsation pour un sillage chaud et envoûtant.',
                'flavors' => [
                    ['name' => 'Amber Romance Classic', 'color' => '#b45309']
                ]
            ],
        ];
    }

    public static function getCategories()
    {
        return [
            ['slug' => 'all', 'name' => 'Tous les packs & produits', 'count' => 17],
            ['slug' => 'rituals', 'name' => 'Rituals', 'count' => 4],
            ['slug' => 'sol-de-janeiro', 'name' => 'Sol de Janeiro', 'count' => 4],
            ['slug' => 'the-ordinary', 'name' => 'The Ordinary', 'count' => 4],
            ['slug' => 'victorias-secret', 'name' => 'Victoria\'s Secret', 'count' => 4],
            ['slug' => 'garden-bouquet', 'name' => 'Garden Bouquet', 'count' => 1],
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
            ->withCount(['products as count' => fn ($query) => $query->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();

        if ($categories->isEmpty()) {
            return self::getCategories();
        }

        $totalCount = Product::where('is_active', true)
            ->whereHas('category', fn ($query) => $query->where('is_active', true))
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
