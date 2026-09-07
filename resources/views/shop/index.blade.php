@php
    $currentCategory = collect($categories)->firstWhere('slug', $selectedCategory);
    $categorySlug = $selectedCategory ?? 'all';

    if ($categorySlug === 'rituals') {
        $seoTitle = 'Coffrets Rituals Maroc — Coffrets Cadeaux Ayurveda, Sakura, Karma & Yozakura en DH | Zizo Aura';
        $seoHeading = 'Coffrets Cadeaux Rituals Maroc';
        $seoDescription = 'Achetez votre coffret Rituals authentique au Maroc : The Ritual of Sakura, Ayurveda, Karma et Yozakura (Packs XS, S, M et Mini). Prix en DH (dès 150 DH), livraison express 24-48h et paiement à la livraison (Cash on Delivery).';
        $seoIntro = 'Explorez notre collection exclusive de coffrets cadeaux Rituals au Maroc. Mousse de douche onctueuse, gommages exfoliants et crèmes hydratantes aux senteurs iconiques. 100% Originaux avec livraison 24-48h et paiement à la livraison.';
        $faqItems = [
            [
                'q' => 'Où acheter un coffret Rituals original au Maroc ?',
                'a' => 'Sur la boutique officielle Zizo Aura Maroc, vous retrouvez les coffrets originaux The Ritual of Sakura, Ayurveda, Karma et Yozakura avec livraison sous 24-48h et paiement à la livraison partout au Maroc.'
            ],
            [
                'q' => 'Quel est le prix d\'un coffret Rituals au Maroc ?',
                'a' => 'Les coffrets cadeaux Rituals sont proposés en différents formats : Pack XS à 250 DH, Pack S à 360 DH, Pack M à 520 DH et Mini Coffret Karma à 150 DH.'
            ],
            [
                'q' => 'Quels sont les délais de livraison pour les coffrets Rituals au Maroc ?',
                'a' => 'Expédition express sous 24h à 48h dans toutes les villes du Maroc : Casablanca, Rabat, Marrakech, Tanger, Fès, Agadir, Meknès, Oujda, Kénitra et autres.'
            ]
        ];
    } elseif ($categorySlug === 'sol-de-janeiro') {
        $seoTitle = 'Sol de Janeiro Maroc — Brumes Parfumées Cheirosa 62, 68, 40 & 59 (90ml) | Zizo Aura';
        $seoHeading = 'Sol de Janeiro Maroc';
        $seoDescription = 'Retrouvez toute la gamme de brumes Sol de Janeiro au Maroc : Cheirosa 62, 68, 40 et 59 (90ml). 100% Originaux, prix en DH (340 DH) et livraison express à domicile avec paiement à la livraison.';
        $seoIntro = 'Sublimez votre sillage avec les brumes parfumées iconiques Sol de Janeiro Cheirosa au Maroc. Des notes gourmandes et solaires irrésistibles. 100% Authentiques, livraison 24-48h partout au Royaume.';
        $faqItems = [
            [
                'q' => 'Où trouver les brumes Sol de Janeiro au Maroc ?',
                'a' => 'Zizo Aura propose les brumes Sol de Janeiro 100% originales au Maroc au meilleur prix en Dirhams (Cheirosa 62, 68, 40, 59) avec livraison rapide.'
            ],
            [
                'q' => 'Quel est le prix des brumes Sol de Janeiro au Maroc ?',
                'a' => 'Les brumes parfumées corps & cheveux Sol de Janeiro Cheirosa 90ml sont proposées au prix de 340 DH.'
            ]
        ];
    } elseif ($categorySlug === 'victorias-secret') {
        $seoTitle = 'Brumes Victoria\'s Secret Maroc — Bare Vanilla, Love Spell, Aqua Kiss & Amber Romance | Zizo Aura';
        $seoHeading = 'Brumes Parfumées Victoria\'s Secret Maroc (En Promotion)';
        $seoDescription = 'Achetez vos brumes parfumées Victoria\'s Secret 250ml originales au Maroc en promotion : 240 DH au lieu de 280 DH (Bare Vanilla, Love Spell, Aqua Kiss, Amber Romance). Paiement à la livraison.';
        $seoIntro = 'Profitez de notre offre spéciale sur les brumes corporelles 250ml Victoria\'s Secret au Maroc : 240 DH au lieu de 280 DH. Parfums irrésistibles, formules originales et livraison express 24-48h dans toutes les villes.';
        $faqItems = [
            [
                'q' => 'Combien coûte une brume Victoria\'s Secret originale au Maroc ?',
                'a' => 'Nos brumes 250ml Victoria\'s Secret sont actuellement en promotion à 240 DH au lieu de 280 DH avec garantie d\'authenticité absolue.'
            ],
            [
                'q' => 'Quelles sont les fragrances Victoria\'s Secret disponibles ?',
                'a' => 'Nous proposons les 4 fragrances phares : Bare Vanilla, Love Spell, Aqua Kiss et Amber Romance.'
            ]
        ];
    } elseif ($categorySlug === 'the-ordinary') {
        $seoTitle = 'The Ordinary Maroc — Sérums Niacinamide & Tonique Acide Glycolique | Zizo Aura';
        $seoHeading = 'Sérums & Soins The Ordinary Maroc';
        $seoDescription = 'Retrouvez les soins The Ordinary 100% authentiques au Maroc : Sérum Niacinamide 10% + Zinc 1% (30ml & 60ml) et Tonique Acide Glycolique 7% (100ml & 240ml). Prix en DH et livraison rapide.';
        $seoIntro = 'Traitements dermatologiques ultra-ciblés The Ordinary au Maroc. Corrigez les imperfections et sublimez l\'éclat de votre peau avec nos formules certifiées originales.';
        $faqItems = [
            [
                'q' => 'Comment être sûr que les produits The Ordinary sont originaux au Maroc ?',
                'a' => 'Tous les produits The Ordinary chez Zizo Aura sont importés des distributeurs officiels certifiés avec packaging intact et numéro de lot vérifié.'
            ],
            [
                'q' => 'Quels sont les prix des produits The Ordinary au Maroc ?',
                'a' => 'Le sérum Niacinamide 10% est à 160 DH (30ml) et 240 DH (60ml). Le tonique Acide Glycolique 7% est à 160 DH (100ml) et 220 DH (240ml).'
            ]
        ];
    } elseif ($categorySlug === 'garden-bouquet') {
        $seoTitle = 'Garden Bouquet Maroc — Coffret Cadeau Soins & Parfum | Zizo Aura';
        $seoHeading = 'Coffret Cadeau Garden Bouquet Maroc';
        $seoDescription = 'Découvrez le coffret cadeau Garden Bouquet au Maroc : une sélection délicate de soins floraux au prix doux de 120 DH. Idéal pour offrir ou se faire plaisir.';
        $seoIntro = 'Craquez pour la fraîcheur florale du coffret cadeau Garden Bouquet. Une idée cadeau raffinée et accessible avec livraison express 24-48h partout au Maroc.';
        $faqItems = [
            [
                'q' => 'Quel est le contenu et le prix du coffret Garden Bouquet ?',
                'a' => 'Le coffret cadeau Garden Bouquet est proposé au prix de 120 DH, combinant senteurs florales délicates et soins doux.'
            ],
            [
                'q' => 'Quels sont les délais de livraison pour Garden Bouquet ?',
                'a' => 'Livraison rapide en 24h à 48h partout au Maroc avec paiement en espèces à la réception.'
            ]
        ];
    } else {
        $seoTitle = 'Boutique Cosmétiques & Parfums Maroc — Sol de Janeiro, Rituals, Victoria\'s Secret | Zizo Aura';
        $seoHeading = 'Nos Formules, Coffrets & Brumes d\'Exception';
        $seoDescription = 'Boutique en ligne officielle de soins, brumes et coffrets de luxe au Maroc : Sol de Janeiro, Victoria\'s Secret, Rituals, The Ordinary, Garden Bouquet. 100% Originaux, livraison express 24-48h et paiement à la livraison.';
        $seoIntro = 'Découvrez nos brumes Cheirosa emblématiques, coffrets cadeaux Rituals, brumes Victoria\'s Secret et soins The Ordinary formulés pour sublimer chaque routine.';
        $faqItems = [
            [
                'q' => 'Quelles sont les conditions de livraison partout au Maroc ?',
                'a' => 'Livraison express suivie en 24h à 48h à Casablanca, Rabat, Marrakech, Tanger, Agadir, Fès et toutes les villes du Royaume (35 DH).'
            ],
            [
                'q' => 'Quels sont les moyens de paiement acceptés ?',
                'a' => 'Paiement en espèces à la livraison (Cash on Delivery) après vérification de votre commande.'
            ]
        ];
    }

    $canonicalUrl = $selectedCategory && $selectedCategory !== 'all'
        ? route('shop.category', $selectedCategory)
        : route('shop.index');

    $breadcrumbItems = [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Accueil',
            'item' => url('/'),
        ],
        [
            '@type' => 'ListItem',
            'position' => 2,
            'name' => 'Boutique',
            'item' => route('shop.index'),
        ],
    ];

    if ($currentCategory && $selectedCategory !== 'all') {
        $breadcrumbItems[] = [
            '@type' => 'ListItem',
            'position' => 3,
            'name' => $currentCategory['name'],
            'item' => $canonicalUrl,
        ];
    }

    $faqMainEntity = array_map(function ($faq) {
        return [
            '@type' => 'Question',
            'name' => $faq['q'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $faq['a'],
            ],
        ];
    }, $faqItems);

    $indexSchema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'CollectionPage',
                'name' => $seoTitle,
                'description' => $seoDescription,
                'url' => $canonicalUrl,
                'breadcrumb' => [
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => $breadcrumbItems,
                ],
            ],
            [
                '@type' => 'FAQPage',
                '@id' => $canonicalUrl . '#faq',
                'mainEntity' => $faqMainEntity,
            ],
        ],
    ];
@endphp

@extends('layouts.app')

@section('title', $seoTitle)
@section('meta_description', $seoDescription)
@section('canonical', $canonicalUrl)
@section('og_type', 'website')
@section('og_title', $seoTitle)
@section('og_description', $seoDescription)
@section('og_url', $canonicalUrl)
@if(!empty($products) && isset($products[0]['image']))
@section('og_image', url($products[0]['image']))
@endif

@section('schema')
<script type="application/ld+json">
{!! json_encode($indexSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endsection

@section('content')
<div class="w-full bg-white py-8 sm:py-12">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Shop Header Banner -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-8 border-b border-zinc-100 mb-8">
            <div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-black tracking-tight leading-tight">
                    {{ $seoHeading }}
                </h1>
                <p class="text-sm sm:text-base text-zinc-500 font-normal mt-2 max-w-xl">
                    {{ $seoIntro }}
                </p>
            </div>

            <!-- Total Count & Custom Themed Sort Dropdown -->
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-zinc-400 uppercase tracking-wider whitespace-nowrap">
                    {{ count($products) }} produit{{ count($products) > 1 ? 's' : '' }}
                </span>

                <!-- Custom Theme Dropdown Menu with Touch/Click Support -->
                <div class="relative group" id="custom-sort-dropdown">
                    <button type="button"
                            id="sort-dropdown-btn"
                            class="btn-pill-secondary btn-pill-sm uppercase tracking-wider">
                        <span class="text-zinc-900 font-extrabold">
                            @if($sortBy === 'rating') Mieux notés
                            @elseif($sortBy === 'price-asc') Prix croissant
                            @elseif($sortBy === 'price-desc') Prix décroissant
                            @else Popularité
                            @endif
                        </span>
                        <i id="sort-dropdown-chevron" class="uil uil-angle-down text-xs text-zinc-400 group-hover:rotate-180 transition-transform duration-200"></i>
                    </button>

                    <!-- Custom Floating Dropdown Panel -->
                    <div id="sort-dropdown-panel"
                         class="absolute right-0 top-full mt-2 w-52 bg-white/95 backdrop-blur-md rounded-2xl shadow-[0_15px_35px_rgba(0,0,0,0.12)] border border-zinc-100 p-1.5 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <a href="{{ route('shop.index', ['category' => $selectedCategory, 'sort' => 'popular']) }}"
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ $sortBy === 'popular' ? 'bg-pink-50 text-pink-600' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black' }}">
                            <span>Popularité</span>
                            @if($sortBy === 'popular') <i class="uil uil-check text-xs"></i> @endif
                        </a>
                        <a href="{{ route('shop.index', ['category' => $selectedCategory, 'sort' => 'rating']) }}"
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ $sortBy === 'rating' ? 'bg-pink-50 text-pink-600' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black' }}">
                            <span>Mieux notés</span>
                            @if($sortBy === 'rating') <i class="uil uil-check text-xs"></i> @endif
                        </a>
                        <a href="{{ route('shop.index', ['category' => $selectedCategory, 'sort' => 'price-asc']) }}"
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ $sortBy === 'price-asc' ? 'bg-pink-50 text-pink-600' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black' }}">
                            <span>Prix croissant</span>
                            @if($sortBy === 'price-asc') <i class="uil uil-check text-xs"></i> @endif
                        </a>
                        <a href="{{ route('shop.index', ['category' => $selectedCategory, 'sort' => 'price-desc']) }}"
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ $sortBy === 'price-desc' ? 'bg-pink-50 text-pink-600' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black' }}">
                            <span>Prix décroissant</span>
                            @if($sortBy === 'price-desc') <i class="uil uil-check text-xs"></i> @endif
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Filter Pills Bar -->
        <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-6 no-scrollbar -mx-4 px-4 sm:mx-0 sm:px-0 scroll-smooth">
            @foreach($categories as $cat)
                <a href="{{ route('shop.index', array_filter(['category' => $cat['slug'], 'sort' => $sortBy, 'q' => $searchQuery ?? null])) }}"
                   class="px-5 py-2 rounded-full text-xs font-extrabold uppercase tracking-wider whitespace-nowrap transition-all duration-200 shrink-0 {{ $selectedCategory === $cat['slug'] ? 'bg-black text-white shadow-md' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200 active:scale-95' }}">
                    {{ $cat['name'] }}
                </a>
            @endforeach
        </div>

        <!-- Active Search Filter Banner -->
        @if(!empty($searchQuery))
            <div class="mb-8 p-4 bg-pink-50/70 border border-pink-200/80 rounded-2xl flex flex-wrap items-center justify-between gap-3 shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-pink-600 text-white flex items-center justify-center text-base shrink-0 shadow-xs">
                        <i class="uil uil-search"></i>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm font-bold text-zinc-900">
                            Résultats pour : <span class="text-pink-600 font-extrabold">&laquo; {{ $searchQuery }} &raquo;</span>
                        </p>
                        <p class="text-[11px] text-zinc-500 font-medium">
                            {{ count($products) }} résultat{{ count($products) > 1 ? 's trouvés' : ' trouvé' }}
                        </p>
                    </div>
                </div>

                <a href="{{ route('shop.index', ['category' => $selectedCategory !== 'all' ? $selectedCategory : null]) }}"
                   class="btn-pill-secondary btn-pill-sm">
                    <i class="uil uil-multiply text-xs"></i>
                    <span>Effacer la recherche</span>
                </a>
            </div>
        @endif

        <!-- 4-Column Product Catalog Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            @forelse($products as $product)
                <div class="product-card reveal-on-scroll group flex flex-col bg-white rounded-2xl p-3.5 sm:p-4 border border-zinc-100 shadow-[0_2px_10px_rgba(0,0,0,0.03)] hover:shadow-[0_12px_30px_rgba(0,0,0,0.08)] transition-all duration-300"
                     style="transition-delay: {{ ($loop->index % 4) * 80 }}ms;">
                    
                    <!-- Product Image Box linking to Product Subpage -->
                    <a href="{{ route('shop.product', $product['slug']) }}" class="relative aspect-square w-full bg-[#f8f9fa] rounded-xl overflow-hidden flex items-center justify-center p-6 mb-4 block">
                        
                        <!-- Watermark Typography Discount -->
                        @if(!empty($product['discount']))
                            <div class="absolute inset-0 flex flex-col justify-between p-3 select-none pointer-events-none z-0">
                                <span class="watermark-discount text-zinc-200/70 text-left font-black tracking-tighter">
                                    {{ $product['discount'] }}
                                </span>
                                <span class="watermark-discount text-zinc-200/70 text-right font-black tracking-tighter">
                                    remise
                                </span>
                            </div>
                        @endif

                        <!-- Top Floating Pill Badge -->
                        @if(!empty($product['badge']))
                            <div class="absolute top-2.5 inset-x-0 flex justify-center z-20">
                                <span class="px-3 py-1 rounded-full text-[10px] sm:text-[11px] font-extrabold uppercase tracking-wider shadow-sm {{ $product['badge_color'] }}">
                                    {{ $product['badge'] }}
                                </span>
                            </div>
                        @endif

                        <!-- Product Image Asset -->
                        <img src="{{ $product['image'] }}"
                             alt="{{ $product['name'] }}"
                             class="product-img relative z-10 w-full h-full object-contain max-h-[190px] group-hover:scale-108 transition-transform duration-500 ease-out" />
                    </a>

                    <!-- Product Details -->
                    <div class="flex flex-col items-center text-center flex-1">
                        <!-- Rating Stars -->
                        <div class="flex items-center gap-1 text-amber-400 text-xs mb-1.5">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($product['rating']))
                                        <i class="uis uis-star text-[11px]"></i>
                                    @else
                                        <i class="uil uil-star text-[11px] text-zinc-200"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-[10px] font-bold text-zinc-400">({{ $product['review_count'] }})</span>
                        </div>

                        <!-- Product Title with Link -->
                        <h3 class="text-sm sm:text-[15px] font-bold text-zinc-900 leading-snug mb-1 min-h-[40px] flex items-center justify-center">
                            <a href="{{ route('shop.product', $product['slug']) }}" class="hover:text-pink-600 transition-colors">
                                {{ $product['name'] }}
                            </a>
                        </h3>

                        <!-- Scent / Flavor Name Indicator -->
                        @if(!empty($product['flavors']))
                            <p class="flavor-label text-[11px] font-semibold text-zinc-400 mb-2">
                                {{ $product['flavors'][0]['name'] }}
                            </p>
                        @endif

                        <!-- Price in DH -->
                        <div class="flex flex-col items-center justify-center gap-0.5 mb-4">
                            <span class="text-lg sm:text-xl font-extrabold text-pink-600 tracking-tight">
                                {{ $product['price'] }} DH
                            </span>
                            @if(!empty($product['discount']) && !empty($product['original_price']) && $product['original_price'] !== $product['price'])
                                <span class="text-xs font-semibold text-zinc-400 line-through">
                                    {{ $product['original_price'] }} DH
                                </span>
                            @endif
                        </div>

                        @if($product['is_active'] ?? true)
                            <!-- Black Pill "Ajouter au panier" Button -->
                            <button class="btn-card-pill w-full mt-auto cursor-pointer"
                                    data-add-to-cart
                                    data-product-name="{{ $product['name'] }}"
                                    data-product-price="{{ $product['price'] }}"
                                    data-product-image="{{ $product['image'] }}"
                                    data-product-slug="{{ $product['slug'] }}">
                                <i class="uil uil-shopping-bag text-base"></i>
                                <span>Ajouter au panier</span>
                            </button>
                        @else
                            <!-- Black Pill "Précommander" Button -->
                            <button class="btn-card-pill w-full mt-auto !bg-zinc-900 hover:!bg-black text-white cursor-pointer"
                                    data-preorder-product
                                    data-product-id="{{ $product['id'] ?? '' }}"
                                    data-product-name="{{ $product['name'] }}"
                                    data-product-price="{{ $product['price'] }}"
                                    data-product-image="{{ $product['image'] }}"
                                    data-product-slug="{{ $product['slug'] }}">
                                <i class="uil uil-clock text-base text-amber-400"></i>
                                <span>Précommander</span>
                            </button>
                        @endif
                    </div>

                </div>
            @empty
                <div class="col-span-full py-16 text-center text-zinc-400">
                    <i class="uil uil-box text-4xl mb-3 block"></i>
                    <p class="text-sm font-semibold">Aucun produit trouvé dans cette catégorie.</p>
                </div>
            @endforelse
        </div>

        <!-- SEO Content & Moroccan FAQ Accordion Section -->
        @if(!empty($faqItems))
            <div class="mt-20 pt-12 border-t border-zinc-100 max-w-4xl mx-auto">
                <div class="text-center mb-10">
                    <span class="px-3.5 py-1 rounded-full bg-pink-50 text-pink-600 text-xs font-black uppercase tracking-wider inline-block mb-3">
                        Guide d'achat &amp; FAQ Maroc
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-zinc-900 tracking-tight">
                        Questions fréquentes sur nos commandes au Maroc
                    </h2>
                    <p class="text-xs sm:text-sm text-zinc-500 mt-2">
                        Tout ce que vous devez savoir sur l'authenticité de nos produits, nos prix en Dirhams et la livraison express.
                    </p>
                </div>

                <div class="space-y-4">
                    @foreach($faqItems as $idx => $item)
                        <details class="group bg-[#f8f9fa] rounded-2xl p-5 sm:p-6 border border-zinc-100 transition-all duration-200 open:bg-white open:shadow-md open:border-pink-200">
                            <summary class="flex justify-between items-center cursor-pointer font-bold text-sm sm:text-base text-zinc-900 list-none">
                                <span>{{ $item['q'] }}</span>
                                <span class="w-8 h-8 rounded-full bg-zinc-100 group-open:bg-pink-50 group-open:text-pink-600 flex items-center justify-center text-xs shrink-0 transition-all">
                                    <i class="uil uil-plus group-open:hidden"></i>
                                    <i class="uil uil-minus hidden group-open:block"></i>
                                </span>
                            </summary>
                            <div class="pt-4 text-xs sm:text-sm text-zinc-600 leading-relaxed border-t border-zinc-100/80 mt-3 font-normal">
                                {{ $item['a'] }}
                            </div>
                        </details>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
