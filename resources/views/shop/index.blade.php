@php
    $currentCategory = collect($categories)->firstWhere('slug', $selectedCategory);
    $categorySlug = $selectedCategory ?? 'all';

    if ($categorySlug === 'rituals') {
        $seoTitle = 'Coffrets Rituals Maroc — Coffrets Cadeaux Sakura, Ayurveda & Karma en DH | Zizo Aura';
        $seoHeading = 'Coffrets Cadeaux Rituals Maroc';
        $seoDescription = 'Achetez votre coffret Rituals authentique au Maroc : The Ritual of Sakura, Ayurveda, Karma, Mehr et Jing. Prix en DH (390 DH), livraison express 24-48h et paiement à la livraison (Cash on Delivery).';
        $seoIntro = 'Explorez notre collection exclusive de coffrets cadeaux Rituals au Maroc. Mousse de douche onctueuse, gommages exfoliants et crèmes hydratantes aux senteurs iconiques. 100% Originaux avec livraison 24-48h et paiement à la livraison.';
        $faqItems = [
            [
                'q' => 'Où acheter un coffret Rituals original au Maroc ?',
                'a' => 'Sur la boutique officielle Zizo Aura Maroc, vous retrouvez les coffrets originaux The Ritual of Sakura, Ayurveda, Karma, Mehr et Jing avec livraison sous 24-48h et paiement à la livraison partout au Maroc.'
            ],
            [
                'q' => 'Quel est le prix d\'un coffret Rituals au Maroc ?',
                'a' => 'Les coffrets cadeaux Rituals grand format (mousse de douche, gommage, crème corps, brume d\'oreiller) sont proposés au prix de 390 DH avec échantillons offerts.'
            ],
            [
                'q' => 'Quels sont les délais de livraison pour les coffrets Rituals au Maroc ?',
                'a' => 'Expédition express sous 24h à 48h dans toutes les villes du Maroc : Casablanca, Rabat, Marrakech, Tanger, Fès, Agadir, Meknès, Oujda, Kénitra et autres.'
            ]
        ];
    } elseif ($categorySlug === 'sol-de-janeiro') {
        $seoTitle = 'Sol de Janeiro Maroc — Brazilian Bum Bum Cream, Brumes Cheirosa & Jet Sets | Zizo Aura';
        $seoHeading = 'Sol de Janeiro Maroc';
        $seoDescription = 'Retrouvez toute la gamme Sol de Janeiro au Maroc : Brazilian Bum Bum Cream, brumes Cheirosa 68, 62, 59, 40 et Jet Sets voyage. 100% Originaux, prix en DH et livraison express à domicile avec paiement à la livraison.';
        $seoIntro = 'Sublimez votre peau avec les soins iconiques Sol de Janeiro au Maroc. Crèmes raffermissantes au beurre de Cupuaçu et brumes parfumées gourmandes. Livraison 24-48h partout au Royaume.';
        $faqItems = [
            [
                'q' => 'Où trouver les brumes et crèmes Sol de Janeiro au Maroc ?',
                'a' => 'Zizo Aura propose les soins et brumes Sol de Janeiro 100% originaux au Maroc au meilleur prix en Dirhams avec livraison rapide.'
            ],
            [
                'q' => 'Quel est le prix des coffrets Jet Set Sol de Janeiro au Maroc ?',
                'a' => 'Les coffrets Jet Set (Bum Bum, Beija Flor, Bom Dia Bright, Delícia Drench) sont à 320 DH avec livraison express 24-48h.'
            ]
        ];
    } elseif ($categorySlug === 'victorias-secret') {
        $seoTitle = 'Brumes & Coffrets Victoria\'s Secret Maroc — Bare Vanilla, Pure Seduction (250ml) | Zizo Aura';
        $seoHeading = 'Brumes & Coffrets Victoria\'s Secret Maroc';
        $seoDescription = 'Achetez vos brumes parfumées et packs duos Victoria\'s Secret originaux au Maroc (Bare Vanilla, Pure Seduction, Velvet Petals, Love Spell). Prix en DH & paiement à la livraison.';
        $seoIntro = 'Découvrez les brumes corporelles 250ml et coffrets de luxe Victoria\'s Secret au Maroc. Parfums irrésistibles, formules originales et livraison express 24-48h dans toutes les villes.';
        $faqItems = [
            [
                'q' => 'Combien coûte une brume Victoria\'s Secret originale au Maroc ?',
                'a' => 'Nos brumes standard 250ml Victoria\'s Secret sont proposées à 195 DH au lieu de 280 DH avec garantie d\'authenticité absolue.'
            ],
            [
                'q' => 'Quelles sont les meilleures brumes Victoria\'s Secret au Maroc ?',
                'a' => 'Les best-sellers incontournables sont Bare Vanilla (vanille & cachemire), Pure Seduction (prune rouge & freesia) et Velvet Petals (fleurs douces & amande).'
            ]
        ];
    } elseif ($categorySlug === 'the-ordinary') {
        $seoTitle = 'The Ordinary Maroc — Sérums Niacinamide, Acide Hyaluronique & Peeling Rouge | Zizo Aura';
        $seoHeading = 'Sérums & Soins The Ordinary Maroc';
        $seoDescription = 'Retrouvez les sérums The Ordinary 100% authentiques au Maroc : Niacinamide 10%, Peeling Rouge AHA 30%, Acide Hyaluronique 2%, Acide Glycolique 7%. Prix en DH et livraison rapide.';
        $seoIntro = 'Traitements dermatologiques ultra-ciblés The Ordinary au Maroc. Corrigez les imperfections, sublimez votre grain de peau et hydratez intensément avec nos formules certifiées originales.';
        $faqItems = [
            [
                'q' => 'Comment être sûr que les produits The Ordinary sont originaux au Maroc ?',
                'a' => 'Tous les sérums The Ordinary chez Zizo Aura sont importés des distributeurs officiels certifiés avec packaging intact et numéro de lot vérifié.'
            ],
            [
                'q' => 'Quel est le prix du sérum Niacinamide The Ordinary au Maroc ?',
                'a' => 'Le sérum Niacinamide 10% + Zinc 1% (30ml) est disponible à 140 DH sur Zizo Aura Maroc.'
            ]
        ];
    } else {
        $seoTitle = 'Boutique Cosmétiques & Parfums Maroc — Sol de Janeiro, Rituals, Victoria\'s Secret | Zizo Aura';
        $seoHeading = 'Nos Formules, Coffrets & Soins Solaires';
        $seoDescription = 'Boutique en ligne officielle de soins, brumes et coffrets de luxe au Maroc : Sol de Janeiro, Victoria\'s Secret, Rituals, The Ordinary. 100% Originaux, livraison express 24-48h et paiement à la livraison.';
        $seoIntro = 'Découvrez nos brumes Cheirosa emblématiques, coffrets cadeaux Rituals, crèmes raffermissantes et élixirs botaniques formulés pour sublimer chaque peau.';
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
