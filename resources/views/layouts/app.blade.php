<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="format-detection" content="telephone=no">
    <meta name="robots" content="@yield('robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1')">
    <meta name="description" content="@yield('meta_description', "Zizo Aura Maroc — Boutique en ligne officielle de soins & brumes parfumées de luxe : Sol de Janeiro, Victoria's Secret, Rituals, The Ordinary. 100% Originaux, livraison express partout au Maroc et paiement à la livraison.")">
    <meta name="theme-color" content="#ffffff">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="@yield('canonical', url()->current())">

    <!-- Morocco Geo & Local Targeting Meta Tags -->
    <meta name="geo.region" content="MA">
    <meta name="geo.placename" content="Morocco">
    <meta name="geo.position" content="31.7917;-7.0926">
    <meta name="ICBM" content="31.7917, -7.0926">

    <!-- Open Graph (WhatsApp, Facebook, Instagram) -->
    <meta property="og:site_name" content="Zizo Aura">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('og_title', "Zizo Aura Maroc — Sol de Janeiro, Victoria's Secret & Rituals Officiel")">
    <meta property="og:description" content="@yield('og_description', "Offrez à votre peau des formules solaires & duos d'exception au Maroc : Victoria's Secret, Rituals, Sol de Janeiro, The Ordinary. Livraison rapide partout au Maroc & Paiement à la livraison.")">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:image" content="@yield('og_image', url('/images/popup_summer_sale.png'))">
    <meta property="og:locale" content="fr_MA">
    <meta property="og:locale:alternate" content="ar_MA">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', 'Zizo Aura Maroc')">
    <meta name="twitter:description" content="@yield('og_description', 'Boutique officielle de cosmétiques & brumes de luxe au Maroc. Livraison rapide partout au Maroc & paiement à la livraison.')">
    <meta name="twitter:image" content="@yield('og_image', url('/images/popup_summer_sale.png'))">

    @yield('extra_head')

    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/favicon.png">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <title>@yield('title', "Zizo Aura Maroc — Sol de Janeiro, Victoria's Secret & Rituals Officiel")</title>

    <!-- Global JSON-LD Structured Data Schema.org (Google Morocco Rich Snippets) -->
    <script type="application/ld+json">
    {
        "{{ '@context' }}": "https://schema.org",
        "{{ '@graph' }}": [
            {
                "{{ '@type' }}": "OnlineStore",
                "{{ '@id' }}": "{{ url('/') }}#store",
                "name": "Zizo Aura",
                "url": "{{ url('/') }}",
                "logo": "{{ url('/images/logo.png') }}",
                "image": "{{ url('/images/popup_summer_sale.png') }}",
                "description": "Boutique en ligne officielle de soins et cosmétiques authentiques au Maroc.",
                "telephone": "+212682787594",
                "priceRange": "150 DH - 700 DH",
                "currenciesAccepted": "MAD",
                "paymentAccepted": "Cash on Delivery",
                "areaServed": {
                    "{{ '@type' }}": "Country",
                    "name": "Morocco"
                },
                "address": {
                    "{{ '@type' }}": "PostalAddress",
                    "addressCountry": "MA"
                }
            },
            {
                "{{ '@type' }}": "WebSite",
                "{{ '@id' }}": "{{ url('/') }}#website",
                "url": "{{ url('/') }}",
                "name": "Zizo Aura",
                "potentialAction": {
                    "{{ '@type' }}": "SearchAction",
                    "target": "{{ url('/boutique') }}?q={search_term_string}",
                    "query-input": "required name=search_term_string"
                }
            }
        ]
    }
    </script>
    @yield('schema')

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,700;1,800&display=swap" rel="stylesheet">

    <!-- IconScout Unicons (Line & Solid luxury distribution) -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/solid.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-zinc-950 font-sans antialiased selection:bg-pink-500 selection:text-white flex flex-col min-h-screen">
    <x-navbar />

    <main class="flex-1 page-transition-enter">
        @yield('content')
    </main>

    <!-- Footer with Social Media Accounts -->
    <x-footer />

    <!-- Promo Popup Modal (Landing Page Only) -->
    @if(request()->routeIs('home') || request()->routeIs('brand.*') || request()->is('/') || request()->is('marques*'))
        <x-promo-modal />
    @endif

    <!-- Slide-over Cart Drawer -->
    <x-cart-drawer />

    <!-- Online Order Checkout Modal -->
    <x-checkout-modal />

    <!-- Toast Notification with IconScout Unicons -->
    <div id="app-toast" class="app-toast toast-hismile" role="status" aria-live="polite">
        <i class="uis uis-check-circle text-emerald-400 text-lg"></i>
        <span id="toast-message">Ajouté au panier !</span>
    </div>
</body>
</html>
