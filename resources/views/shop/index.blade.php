@php
    $currentCategory = collect($categories)->firstWhere('slug', $selectedCategory);
    $categorySlug = $selectedCategory ?? 'all';

    if ($categorySlug === 'rituals') {
        $seoTitle = 'Coffrets Rituals Maroc — Sakura, Ayurveda & Karma en DH';
        $seoHeading = 'Coffrets Cadeaux Rituals Maroc';
        $seoDescription = 'Coffrets Rituals 100% authentiques au Maroc dès 150 DH : The Ritual of Sakura, Ayurveda, Karma et Mehr. Livraison rapide 24-48h et paiement à la livraison.';
        $seoIntro = 'Explorez notre collection exclusive de coffrets cadeaux Rituals au Maroc. Mousse de douche onctueuse, gommages exfoliants et crèmes hydratantes aux senteurs iconiques. 100% Originaux avec livraison 24-48h et paiement à la livraison.';
        $faqItems = [
            [
                'q' => 'Où acheter un coffret Rituals original au Maroc ?',
                'a' => 'Sur la boutique officielle Zizo Aura Maroc, vous retrouvez les coffrets originaux The Ritual of Sakura, Ayurveda, Karma et Mehr avec livraison sous 24-48h et paiement à la livraison partout au Maroc.'
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
        $seoTitle = 'Sol de Janeiro Maroc — Brumes Cheirosa 62, 68 & 59';
        $seoHeading = 'Brumes & Soins Sol de Janeiro Maroc';
        $seoDescription = 'Brumes Sol de Janeiro Cheirosa et soins Bum Bum au Maroc (340 DH). Produits 100% authentiques, livraison express 24-48h et paiement sécurisé à la livraison.';
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
        $seoTitle = 'Brumes Victoria\'s Secret Maroc — Bare Vanilla (240 DH)';
        $seoHeading = 'Brumes Parfumées Victoria\'s Secret Maroc (En Promotion)';
        $seoDescription = 'Brumes Victoria\'s Secret 250ml originales en promo à 240 DH : Bare Vanilla, Velvet Petals, Aqua Kiss, Amber Romance. Livraison express partout au Maroc.';
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
        $seoTitle = 'The Ordinary Maroc — Sérum Niacinamide & Acide Glycolique';
        $seoHeading = 'Sérums & Soins The Ordinary Maroc (100% Authentiques)';
        $seoDescription = 'Soins The Ordinary 100% authentiques au Maroc dès 160 DH : Niacinamide 10% et Acide Glycolique 7%. Livraison express 24-48h et paiement cash à la livraison.';
        $seoIntro = 'Traitements dermatologiques ultra-ciblés The Ordinary au Maroc. Corrigez les imperfections et sublimez l\'éclat de votre peau avec nos formules certifiées 100% originales importées.';
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
        $seoTitle = 'Coffret Garden Bouquet Maroc — Soins & Bain (120 DH)';
        $seoHeading = 'Coffrets Cadeaux Garden Bouquet Maroc';
        $seoDescription = 'Coffret cadeau Garden Bouquet au Maroc à 120 DH : soins floraux doux pour le corps et le bain. Idée cadeau idéale, livraison express 24-48h et paiement COD.';
        $seoIntro = 'Craquez pour la fraîcheur florale des coffrets cadeaux Garden Bouquet. Une idée cadeau raffinée et accessible avec livraison express 24-48h partout au Maroc.';
        $faqItems = [
            [
                'q' => 'Quel est le contenu et le prix du coffret Garden Bouquet ?',
                'a' => 'Le coffret cadeau Garden Bouquet est proposé à partir de 120 DH, combinant senteurs florales délicates et soins doux pour le bain et le corps.'
            ],
            [
                'q' => 'Quels sont les délais de livraison pour Garden Bouquet ?',
                'a' => 'Livraison rapide en 24h à 48h partout au Maroc avec paiement en espèces à la réception.'
            ]
        ];
    } else {
        $seoTitle = 'Boutique Cosmétiques & Parfums Maroc | Zizo Aura';
        $seoHeading = 'Nos Formules, Coffrets & Brumes d\'Exception';
        $seoDescription = 'Boutique officielle au Maroc : soins, brumes et coffrets Sol de Janeiro, Victoria\'s Secret, Rituals, The Ordinary. Livraison 24-48h et paiement à la livraison.';
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
                <h1 id="shop-seo-heading" class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-black tracking-tight leading-tight">
                    {{ $seoHeading }}
                </h1>
                <p id="shop-seo-intro" class="text-sm sm:text-base text-zinc-500 font-normal mt-2 max-w-xl">
                    {{ $seoIntro }}
                </p>
            </div>

            <!-- Total Count & Custom Themed Sort Dropdown -->
            <div class="flex items-center gap-3">
                <span id="shop-product-count" class="text-xs font-bold text-zinc-400 uppercase tracking-wider whitespace-nowrap">
                    {{ count($products) }} produit{{ count($products) > 1 ? 's' : '' }}
                </span>

                <!-- Custom Theme Dropdown Menu with Touch/Click Support -->
                <div class="relative group" id="custom-sort-dropdown">
                    <button type="button"
                            id="sort-dropdown-btn"
                            class="btn-pill-secondary btn-pill-sm uppercase tracking-wider">
                        <span id="sort-current-label" class="text-zinc-900 font-extrabold">
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
                        @php
                            $isCleanCat = ($selectedCategory && $selectedCategory !== 'all');
                            $sortBaseRoute = $isCleanCat ? 'shop.category' : 'shop.index';
                            $sortCatParam = $isCleanCat ? ['category' => $selectedCategory] : [];
                        @endphp
                        <a href="{{ route($sortBaseRoute, array_merge($sortCatParam, array_filter(['sort' => 'popular', 'q' => $searchQuery ?: null]))) }}"
                           data-sort="popular"
                           class="shop-sort-item flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ $sortBy === 'popular' ? 'bg-pink-50 text-pink-600' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black' }}">
                            <span>Popularité</span>
                            <i class="uil uil-check text-xs sort-check-icon {{ $sortBy === 'popular' ? '' : 'hidden' }}"></i>
                        </a>
                        <a href="{{ route($sortBaseRoute, array_merge($sortCatParam, array_filter(['sort' => 'rating', 'q' => $searchQuery ?: null]))) }}"
                           data-sort="rating"
                           class="shop-sort-item flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ $sortBy === 'rating' ? 'bg-pink-50 text-pink-600' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black' }}">
                            <span>Mieux notés</span>
                            <i class="uil uil-check text-xs sort-check-icon {{ $sortBy === 'rating' ? '' : 'hidden' }}"></i>
                        </a>
                        <a href="{{ route($sortBaseRoute, array_merge($sortCatParam, array_filter(['sort' => 'price-asc', 'q' => $searchQuery ?: null]))) }}"
                           data-sort="price-asc"
                           class="shop-sort-item flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ $sortBy === 'price-asc' ? 'bg-pink-50 text-pink-600' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black' }}">
                            <span>Prix croissant</span>
                            <i class="uil uil-check text-xs sort-check-icon {{ $sortBy === 'price-asc' ? '' : 'hidden' }}"></i>
                        </a>
                        <a href="{{ route($sortBaseRoute, array_merge($sortCatParam, array_filter(['sort' => 'price-desc', 'q' => $searchQuery ?: null]))) }}"
                           data-sort="price-desc"
                           class="shop-sort-item flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ $sortBy === 'price-desc' ? 'bg-pink-50 text-pink-600' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black' }}">
                            <span>Prix décroissant</span>
                            <i class="uil uil-check text-xs sort-check-icon {{ $sortBy === 'price-desc' ? '' : 'hidden' }}"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Filter Pills Bar -->
        <div id="shop-category-pills" class="flex items-center gap-2 overflow-x-auto pb-4 mb-6 no-scrollbar -mx-4 px-4 sm:mx-0 sm:px-0 scroll-smooth">
            @foreach($categories as $cat)
                @php
                    $isCatAll = empty($cat['slug']) || $cat['slug'] === 'all';
                    $pillUrl = $isCatAll
                        ? route('shop.index', array_filter(['sort' => $sortBy !== 'popular' ? $sortBy : null, 'q' => $searchQuery ?: null]))
                        : route('shop.category', array_merge(['category' => $cat['slug']], array_filter(['sort' => $sortBy !== 'popular' ? $sortBy : null, 'q' => $searchQuery ?: null])));
                @endphp
                <a href="{{ $pillUrl }}"
                   data-category="{{ $cat['slug'] }}"
                   class="shop-category-pill px-5 py-2 rounded-full text-xs font-extrabold uppercase tracking-wider whitespace-nowrap transition-all duration-200 shrink-0 {{ $selectedCategory === $cat['slug'] ? 'bg-black text-white shadow-md' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200 active:scale-95' }}">
                    {{ $cat['name'] }}
                </a>
            @endforeach
        </div>

        <!-- Active Search Filter Banner -->
        @if(!empty($searchQuery))
            <div id="shop-search-banner" class="mb-8 p-4 bg-pink-50/70 border border-pink-200/80 rounded-2xl flex flex-wrap items-center justify-between gap-3 shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-pink-600 text-white flex items-center justify-center text-base shrink-0 shadow-xs">
                        <i class="uil uil-search"></i>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm font-bold text-zinc-900">
                            Résultats pour : <span class="text-pink-600 font-extrabold">&laquo; {{ $searchQuery }} &raquo;</span>
                        </p>
                        <p id="shop-search-count" class="text-[11px] text-zinc-500 font-medium">
                            {{ count($products) }} résultat{{ count($products) > 1 ? 's trouvés' : ' trouvé' }}
                        </p>
                    </div>
                </div>

                <a href="{{ ($selectedCategory && $selectedCategory !== 'all') ? route('shop.category', $selectedCategory) : route('shop.index') }}"
                   class="shop-clear-search-btn btn-pill-secondary btn-pill-sm">
                    <i class="uil uil-multiply text-xs"></i>
                    <span>Effacer la recherche</span>
                </a>
            </div>
        @endif

        <!-- Catalog Container with Grid -->
        <div id="shop-catalog-wrapper" class="relative min-h-[350px]">
            <div id="shop-grid-container">
                @include('shop.partials.product-grid')
            </div>
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
