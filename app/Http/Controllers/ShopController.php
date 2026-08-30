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
            // ==================== VICTORIA'S SECRET ====================
            [
                'id' => 'vs-1',
                'slug' => 'victorias-secret-bare-vanilla-pack-duo',
                'brand' => 'Victoria\'s Secret',
                'name' => 'Victoria\'s Secret — Pack Duo Bare Vanilla',
                'subtitle' => 'Brume Parfumée (250ml) & Lait Corps Nourrissant (236ml)',
                'discount' => '-30%',
                'badge' => 'Culte Victoria\'s Secret',
                'badge_color' => 'bg-amber-500 text-white',
                'price' => '350',
                'original_price' => '490',
                'raw_price' => 350,
                'image' => '/images/vs_bare_vanilla.jpg',
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
                'name' => 'Victoria\'s Secret — Pack Duo Pure Seduction',
                'subtitle' => 'Prune Juteuse & Freesia Écrasé (Brume 250ml & Lotion 236ml)',
                'discount' => '-30%',
                'badge' => 'Best-Seller',
                'badge_color' => 'bg-rose-500 text-white',
                'price' => '350',
                'original_price' => '490',
                'raw_price' => 350,
                'image' => '/images/vs_pure_seduction.jpg',
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
                'name' => 'Victoria\'s Secret — Pack Duo Velvet Petals',
                'subtitle' => 'Fleurs Luxuriantes & Glaze d\'Amande Douce (Brume + Lait)',
                'discount' => '-30%',
                'badge' => 'Coup de Cœur',
                'badge_color' => 'bg-pink-400 text-pink-950',
                'price' => '350',
                'original_price' => '490',
                'raw_price' => 350,
                'image' => '/images/vs_velvet_petals.jpg',
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
                'name' => 'Victoria\'s Secret — Pack Duo Love Spell',
                'subtitle' => 'Fleur de Cerisier & Pêche Fraîche (Brume 250ml & Lotion 236ml)',
                'discount' => '-30%',
                'badge' => 'Icône Mythique',
                'badge_color' => 'bg-purple-500 text-white',
                'price' => '350',
                'original_price' => '490',
                'raw_price' => 350,
                'image' => '/images/vs_love_spell.jpg',
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
                'name' => 'Victoria\'s Secret — Pack Duo Coconut Passion',
                'subtitle' => 'Noix de Coco Insulaire & Sable Chaud Vanillé (Duo Culte)',
                'discount' => '-30%',
                'badge' => 'Solaire Tropical',
                'badge_color' => 'bg-amber-400 text-amber-950',
                'price' => '350',
                'original_price' => '490',
                'raw_price' => 350,
                'image' => '/images/vs_coconut_passion.jpg',
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
                'name' => 'Victoria\'s Secret — Coffret Bombshell Prestige',
                'subtitle' => 'Eau de Parfum Flacon Cristal & Crème Parfumée Velours',
                'discount' => '-25%',
                'badge' => 'N°1 des Ventes Parfums',
                'badge_color' => 'bg-fuchsia-600 text-white',
                'price' => '590',
                'original_price' => '790',
                'raw_price' => 590,
                'image' => '/images/vs_bombshell.jpg',
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

            // ==================== RITUALS ====================
            [
                'id' => 'rit-1',
                'slug' => 'rituals-the-ritual-of-sakura-gift-set',
                'brand' => 'Rituals',
                'name' => 'Rituals — The Ritual of Sakura Coffret Cadeau',
                'subtitle' => 'Mousse de Douche, Gommage Corps & Crème Veloutée au Lait de Riz',
                'discount' => '-20%',
                'badge' => 'Best-Seller Mondial',
                'badge_color' => 'bg-pink-400 text-pink-950',
                'price' => '390',
                'original_price' => '490',
                'raw_price' => 390,
                'image' => '/images/rituals_sakura.jpg',
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
                'name' => 'Rituals — The Ritual of Ayurveda Coffret Équilibre',
                'subtitle' => 'Rose Indienne & Huile d\'Amande Douce Rééquilibrante',
                'discount' => '-20%',
                'badge' => 'Harmonie & Sérénité',
                'badge_color' => 'bg-rose-600 text-white',
                'price' => '390',
                'original_price' => '490',
                'raw_price' => 390,
                'image' => '/images/rituals_ayurveda.jpg',
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
                'name' => 'Rituals — The Ritual of Karma Coffret Solaire',
                'subtitle' => 'Lotus Sacré & Thé Blanc Biologique avec Huile Scintillante',
                'discount' => '-20%',
                'badge' => 'Ondes Positives & Éclat',
                'badge_color' => 'bg-teal-500 text-white',
                'price' => '390',
                'original_price' => '490',
                'raw_price' => 390,
                'image' => '/images/rituals_karma.jpg',
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
                'name' => 'Rituals — The Ritual of Mehr Coffret Énergisant',
                'subtitle' => 'Orange Douce Stimulante & Bois de Cèdre Réchauffant',
                'discount' => '-20%',
                'badge' => 'Coup d\'Éclat Solaire',
                'badge_color' => 'bg-amber-500 text-white',
                'price' => '390',
                'original_price' => '490',
                'raw_price' => 390,
                'image' => '/images/rituals_mehr.jpg',
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
                'name' => 'Rituals — The Ritual of Jing Coffret Sommeil & Sérénité',
                'subtitle' => 'Lotus Sacré & Jujube Apaisant avec Brume d\'Oreiller',
                'discount' => '-20%',
                'badge' => 'Relaxation Profonde',
                'badge_color' => 'bg-emerald-600 text-white',
                'price' => '390',
                'original_price' => '490',
                'raw_price' => 390,
                'image' => '/images/rituals_jing.jpg',
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
                'name' => 'Sol de Janeiro — Brazilian Bum Bum Jet Set',
                'subtitle' => 'Trio culte voyage : Crème Bum Bum, Brume 62 & Gel douche 4 Play',
                'discount' => '-25%',
                'badge' => 'Best-Seller Absolu',
                'badge_color' => 'bg-amber-400 text-amber-950',
                'price' => '320',
                'original_price' => '420',
                'raw_price' => 320,
                'image' => '/images/sdj_bum_bum_set.jpg',
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
                'name' => 'Sol de Janeiro — Beija Flor Jet Set',
                'subtitle' => 'Crème Elasti-Cream Collagène, Brume Cheirosa 68 & Gel Douche',
                'discount' => '-25%',
                'badge' => 'Collagène Végétal',
                'badge_color' => 'bg-pink-500 text-white',
                'price' => '320',
                'original_price' => '420',
                'raw_price' => 320,
                'image' => '/images/sdj_beija_flor.jpg',
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
                'id' => 'sdj-3',
                'slug' => 'sol-de-janeiro-brazilian-bum-bum-cream-240ml',
                'brand' => 'Sol de Janeiro',
                'name' => 'Sol de Janeiro — Brazilian Bum Bum Cream (Grand Format)',
                'subtitle' => 'Crème Corps Raffermissante Iconique (Pot 240ml)',
                'discount' => '-20%',
                'badge' => 'Produit Star',
                'badge_color' => 'bg-amber-400 text-amber-950',
                'price' => '390',
                'original_price' => '490',
                'raw_price' => 390,
                'image' => '/images/bum_bum_cream.jpg',
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
                'name' => 'Sol de Janeiro — Cheirosa 62 Brume Parfumée (Grand Format)',
                'subtitle' => 'Brume Cheveux & Corps Solaire et Gourmande (Flacon 240ml)',
                'discount' => '-20%',
                'badge' => 'Fragrance Mythique',
                'badge_color' => 'bg-amber-500 text-white',
                'price' => '360',
                'original_price' => '450',
                'raw_price' => 360,
                'image' => '/images/cheirosa_mist.jpg',
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
                'name' => 'Sol de Janeiro — Coffret Prestige Summer Glow & Mist',
                'subtitle' => 'Pack Complet Soin, Brume Solaire & Shimmer Body Elixir',
                'discount' => '-35%',
                'badge' => 'Édition Prestige Limitée',
                'badge_color' => 'bg-rose-500 text-white',
                'price' => '490',
                'original_price' => '740',
                'raw_price' => 490,
                'image' => '/images/ultimate_bundle.jpg',
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
        ];
    }

    public static function getCategories()
    {
        return [
            ['slug' => 'all', 'name' => 'Tous les packs & produits', 'count' => 16],
            ['slug' => 'sol-de-janeiro', 'name' => 'Sol de Janeiro Packs', 'count' => 5],
            ['slug' => 'victorias-secret', 'name' => 'Victoria\'s Secret Duos', 'count' => 6],
            ['slug' => 'rituals', 'name' => 'Rituals Coffrets', 'count' => 5],
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
            ->active()
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
            ->withCount(['activeProducts as count'])
            ->orderBy('sort_order')
            ->get();

        if ($categories->isEmpty()) {
            return self::getCategories();
        }

        return array_merge([['slug' => 'all', 'name' => 'Tous les packs & produits', 'count' => Product::active()->count()]], $categories
            ->map(fn (Category $category) => ['slug' => $category->slug, 'name' => $category->name, 'count' => $category->count])
            ->all());
    }

    public function index(Request $request, $category = null)
    {
        $allProducts = self::catalogProducts();
        $categories = self::catalogCategories();

        $selectedCategory = $category ?? $request->query('category', 'all');
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
