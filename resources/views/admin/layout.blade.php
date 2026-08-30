<!DOCTYPE html>
<html lang="fr" class="h-full bg-[#faf9f6]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration') · Zizo Aura</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tabler Icons Webfont & Tailwind Vite Asset -->
    @vite(['resources/css/app.css', 'resources/js/admin.js'])
    
    <style>
        :root {
            --brand-pink: #ff1b7a;
            --brand-pink-hover: #e01569;
            --brand-pink-soft: #fff0f5;
        }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #faf9f6;
            color: #0f172a;
        }
        .btn-primary-pink {
            background-color: var(--brand-pink);
            color: #ffffff;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .btn-primary-pink:hover {
            background-color: var(--brand-pink-hover);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(255, 27, 122, 0.25);
        }
        .btn-primary-pink:active {
            transform: scale(0.98);
        }
        /* Custom scrollbars */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="h-full antialiased overflow-x-hidden">

<div class="min-h-full flex">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div id="admin-sidebar-backdrop" class="fixed inset-0 z-40 bg-zinc-950/50 backdrop-blur-xs transition-opacity duration-300 lg:hidden opacity-0 pointer-events-none"></div>

    <!-- Sidebar Navigation -->
    <aside id="admin-sidebar" class="fixed top-0 bottom-0 left-0 z-50 w-64 bg-white border-r border-zinc-200/80 flex flex-col transition-transform duration-300 ease-out -translate-x-full lg:translate-x-0">
        <!-- Logo Brand Header -->
        <div class="px-6 py-5 border-b border-zinc-100 flex items-center justify-between">
            <a href="#dashboard" class="flex items-center gap-2.5 group">
                <div class="w-8 h-8 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center font-black text-sm group-hover:scale-105 transition-transform shadow-xs">
                    ZA
                </div>
                <div>
                    <span class="font-black text-lg tracking-tight text-zinc-900 group-hover:text-pink-600 transition-colors">zizo aura</span>
                    <span class="block text-[10px] uppercase font-extrabold tracking-widest text-pink-600">Admin Suite</span>
                </div>
            </a>
            <button type="button" id="admin-sidebar-close" class="lg:hidden p-1 text-zinc-400 hover:text-zinc-700 cursor-pointer">
                <i class="ti ti-x text-lg"></i>
            </button>
        </div>

        <!-- Main Navigation Links -->
        <nav class="flex-1 px-3 py-5 space-y-1.5 overflow-y-auto">
            <div class="px-3 pb-2 text-[10px] font-extrabold uppercase tracking-wider text-zinc-400">Navigation Principale</div>
            
            <a href="#dashboard" data-view="dashboard" class="admin-nav-item flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-semibold text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 transition group">
                <div class="flex items-center gap-3">
                    <i class="ti ti-smart-home text-base text-zinc-400 group-hover:text-pink-600 transition-colors"></i>
                    <span>Tableau de bord</span>
                </div>
                <span class="nav-active-indicator w-1.5 h-1.5 rounded-full bg-pink-600 hidden"></span>
            </a>

            <a href="#products" data-view="products" class="admin-nav-item flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-semibold text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 transition group">
                <div class="flex items-center gap-3">
                    <i class="ti ti-sparkles text-base text-zinc-400 group-hover:text-pink-600 transition-colors"></i>
                    <span>Produits & Catalogue</span>
                </div>
                <span class="nav-active-indicator w-1.5 h-1.5 rounded-full bg-pink-600 hidden"></span>
            </a>

            <a href="#categories" data-view="categories" class="admin-nav-item flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-semibold text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 transition group">
                <div class="flex items-center gap-3">
                    <i class="ti ti-tags text-base text-zinc-400 group-hover:text-pink-600 transition-colors"></i>
                    <span>Catégories</span>
                </div>
                <span class="nav-active-indicator w-1.5 h-1.5 rounded-full bg-pink-600 hidden"></span>
            </a>

            <a href="#discounts" data-view="discounts" class="admin-nav-item flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-semibold text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 transition group">
                <div class="flex items-center gap-3">
                    <i class="ti ti-ticket text-base text-zinc-400 group-hover:text-pink-600 transition-colors"></i>
                    <span>Remises & Codes Promo</span>
                </div>
                <span class="nav-active-indicator w-1.5 h-1.5 rounded-full bg-pink-600 hidden"></span>
            </a>

            <a href="#orders" data-view="orders" class="admin-nav-item flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-semibold text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 transition group">
                <div class="flex items-center gap-3">
                    <i class="ti ti-shopping-bag text-base text-zinc-400 group-hover:text-pink-600 transition-colors"></i>
                    <span>Commandes</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span id="sidebar-pending-badge" class="hidden px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 font-extrabold text-[10px]">0</span>
                    <span class="nav-active-indicator w-1.5 h-1.5 rounded-full bg-pink-600 hidden"></span>
                </div>
            </a>

            <a href="#messages" data-view="messages" class="admin-nav-item flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-semibold text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 transition group">
                <div class="flex items-center gap-3">
                    <i class="ti ti-mail text-base text-zinc-400 group-hover:text-pink-600 transition-colors"></i>
                    <span>Boîte de réception</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span id="sidebar-unread-badge" class="hidden px-2 py-0.5 rounded-full bg-pink-100 text-pink-700 font-extrabold text-[10px]">0</span>
                    <span class="nav-active-indicator w-1.5 h-1.5 rounded-full bg-pink-600 hidden"></span>
                </div>
            </a>

            <div class="pt-4 pb-2 px-3 text-[10px] font-extrabold uppercase tracking-wider text-zinc-400 border-t border-zinc-100 mt-4">Liens Externes</div>

            <a href="{{ url('/boutique') }}" target="_blank" class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-semibold text-zinc-500 hover:bg-zinc-50 hover:text-zinc-800 transition">
                <div class="flex items-center gap-3">
                    <i class="ti ti-external-link text-base text-zinc-400"></i>
                    <span>Voir la boutique</span>
                </div>
                <i class="ti ti-arrow-up-right text-zinc-300 text-xs"></i>
            </a>
        </nav>

        <!-- Sidebar Footer / Logout -->
        <div class="p-4 border-t border-zinc-100 bg-zinc-50/50">
            <div class="flex items-center justify-between mb-3 px-1">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-zinc-900 text-white flex items-center justify-center text-xs font-bold">
                        A
                    </div>
                    <div class="leading-tight">
                        <div class="text-xs font-bold text-zinc-900">Admin Zizo</div>
                        <div class="text-[10px] text-zinc-400 font-medium">Session sécurisée</div>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="w-full py-2 px-3 rounded-xl border border-zinc-200 hover:border-red-200 hover:bg-red-50 text-zinc-600 hover:text-red-600 text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer">
                    <i class="ti ti-logout text-sm"></i>
                    <span>Déconnexion</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 lg:pl-64 flex flex-col min-w-0">
        
        <!-- Top App Bar -->
        <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-zinc-200/80 px-4 md:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button type="button" id="admin-mobile-toggle" class="lg:hidden p-2 rounded-xl border border-zinc-200 text-zinc-700 hover:bg-zinc-50 cursor-pointer">
                    <i class="ti ti-menu-2 text-lg"></i>
                </button>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="text-xs font-bold text-zinc-700">Panneau d'Administration en direct</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" onclick="if(window.adminApp) window.adminApp.handleHashChange();" class="p-2 rounded-xl border border-zinc-200 text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50 text-xs font-semibold flex items-center gap-1.5 transition cursor-pointer" title="Rafraîchir les données">
                    <i class="ti ti-refresh text-sm"></i>
                    <span class="hidden sm:inline">Actualiser</span>
                </button>

                <a href="{{ url('/') }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-zinc-900 hover:bg-pink-600 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                    <span>Boutique</span>
                    <i class="ti ti-arrow-up-right text-xs"></i>
                </a>
            </div>
        </header>

        <!-- Main Dynamic App Body -->
        <main class="flex-1 p-4 md:p-8 max-w-7xl w-full mx-auto">
            <!-- Root Container for Dynamic SPA Views -->
            <div id="admin-app-root">
                @yield('content')
            </div>
        </main>
    </div>

</div>

<!-- Modal & Drawer Mount Roots -->
<div id="admin-modal-root"></div>
<div id="admin-drawer-root"></div>
<div id="admin-toast-container"></div>

</body>
</html>
