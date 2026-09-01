<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="zizo aura — Offrez à votre peau des formules solaires & duos d'exception : Victoria's Secret, Rituals, Sol de Janeiro.">
    <meta name="theme-color" content="#ffffff">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <title>@yield('title', 'zizo aura — Sol de Janeiro, Victoria\'s Secret & Rituals')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,700;1,800&display=swap" rel="stylesheet">

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

    <!-- Toast Notification with Tabler Icon -->
    <div id="app-toast" class="app-toast toast-hismile" role="status" aria-live="polite">
        <i class="ti ti-circle-check text-emerald-400 text-lg"></i>
        <span id="toast-message">Ajouté au panier !</span>
    </div>
</body>
</html>
