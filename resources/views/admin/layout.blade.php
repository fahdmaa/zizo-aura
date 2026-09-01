<!DOCTYPE html>
<html lang="fr" class="h-full bg-[#faf9f6]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration') · Zizo Aura</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/favicon.png">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    
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

        /* Bottom Floating Dock Styles & Color Transition */
        .admin-dock-tab {
            transition: color 0.2s cubic-bezier(0.16, 1, 0.3, 1), transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .admin-dock-tab .tab-icon {
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), color 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .admin-dock-tab .tab-label {
            transition: color 0.2s cubic-bezier(0.16, 1, 0.3, 1), font-weight 0.2s ease;
        }
        .admin-dock-tab.active .tab-icon {
            transform: translateY(-2px) scale(1.1);
            color: #ff1b7a !important;
        }
        .admin-dock-tab.active .tab-label {
            color: #ff1b7a !important;
            font-weight: 700;
        }
    </style>
</head>
<body class="h-full antialiased overflow-x-hidden pb-32">

<div class="min-h-full flex flex-col">

    <!-- Top Simplified Brand Header -->
    <header class="sticky top-0 z-30 bg-[#faf9f6]/90 backdrop-blur-md border-b border-zinc-200/60 px-4 md:px-8 py-3.5 transition-all">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            
            <!-- Brand Logo Left -->
            <a href="#dashboard" class="flex items-center hover:opacity-85 transition-opacity select-none group shrink-0" aria-label="zizo aura - Tableau de bord">
                <img src="/images/logo.png" alt="zizo aura" class="h-7 sm:h-8 w-auto object-contain" />
            </a>

            <!-- Header Actions Right -->
            <div class="flex items-center gap-2.5 shrink-0">
                <button type="button" id="admin-refresh-btn" onclick="if(window.adminApp) window.adminApp.refresh();" class="btn-pill-secondary btn-pill-sm cursor-pointer" title="Rafraîchir les données et messages">
                    <i class="ti ti-refresh text-sm transition-transform"></i>
                    <span class="hidden md:inline">Actualiser</span>
                </button>

                <a href="{{ url('/boutique') }}" target="_blank" class="btn-pill-secondary btn-pill-sm">
                    <span>Boutique</span>
                    <i class="ti ti-external-link text-xs text-zinc-400"></i>
                </a>

                <!-- Admin Profile Dropdown / Logout -->
                <div class="relative group">
                    <button type="button" class="btn-pill-secondary btn-pill-sm cursor-pointer pl-3 pr-2">
                        <span class="text-xs font-bold hidden sm:inline mr-1">Admin</span>
                        <div class="w-5 h-5 rounded-full bg-zinc-900 text-white flex items-center justify-center text-[10px] font-bold shrink-0">
                            A
                        </div>
                    </button>

                    <div class="absolute right-0 top-full mt-2 w-48 bg-white border border-zinc-200/80 rounded-2xl shadow-xl p-2 hidden group-hover:block transition-all z-50 animate-fadeIn">
                        <div class="px-3 py-2 border-b border-zinc-100 mb-1">
                            <div class="text-xs font-bold text-zinc-900">Admin Zizo Aura</div>
                            <div class="text-[10px] text-zinc-400 font-medium">Session sécurisée</div>
                        </div>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="btn-pill-danger btn-pill-sm w-full justify-start text-left cursor-pointer">
                                <i class="ti ti-logout text-sm"></i>
                                <span>Déconnexion</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </header>

    <!-- Main Content Body -->
    <main class="flex-1 p-4 md:p-8 max-w-7xl w-full mx-auto">
        <!-- Root Container for Dynamic SPA Views -->
        <div id="admin-app-root">
            @yield('content')
        </div>
    </main>

    <!-- ═══════════════════════════════════════════════════════════════════════════
         BOTTOM FLOATING NAVBAR (Pill Dock with Clean Color Transitions)
         ═══════════════════════════════════════════════════════════════════════════ -->
    <div class="fixed bottom-4 sm:bottom-6 left-0 right-0 z-40 flex justify-center px-3 pointer-events-none">
        <nav id="admin-bottom-dock" class="pointer-events-auto relative bg-white/95 backdrop-blur-md border border-zinc-200/90 rounded-[32px] shadow-[0_12px_40px_rgba(0,0,0,0.1)] w-full max-w-xl md:max-w-2xl px-3 py-2 flex items-center justify-between">
            
            <!-- 1. Accueil / Dashboard -->
            <a href="#dashboard" data-view="dashboard" class="admin-dock-tab relative flex-1 flex flex-col items-center justify-center py-1 px-1 rounded-2xl group cursor-pointer text-zinc-600 hover:text-zinc-900 select-none">
                <div class="tab-icon text-zinc-700">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                </div>
                <span class="tab-label text-[10px] sm:text-[11px] font-medium tracking-tight mt-1 transition-colors duration-200">Accueil</span>
            </a>

            <!-- 2. Produits -->
            <a href="#products" data-view="products" class="admin-dock-tab relative flex-1 flex flex-col items-center justify-center py-1 px-1 rounded-2xl group cursor-pointer text-zinc-600 hover:text-zinc-900 select-none">
                <div class="tab-icon text-zinc-700">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                </div>
                <span class="tab-label text-[10px] sm:text-[11px] font-medium tracking-tight mt-1 transition-colors duration-200">Produits</span>
            </a>

            <!-- 3. Catégories -->
            <a href="#categories" data-view="categories" class="admin-dock-tab relative flex-1 flex flex-col items-center justify-center py-1 px-1 rounded-2xl group cursor-pointer text-zinc-600 hover:text-zinc-900 select-none">
                <div class="tab-icon text-zinc-700">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                        <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                    </svg>
                </div>
                <span class="tab-label text-[10px] sm:text-[11px] font-medium tracking-tight mt-1 transition-colors duration-200">Catégories</span>
            </a>

            <!-- 4. Remises -->
            <a href="#discounts" data-view="discounts" class="admin-dock-tab relative flex-1 flex flex-col items-center justify-center py-1 px-1 rounded-2xl group cursor-pointer text-zinc-600 hover:text-zinc-900 select-none">
                <div class="tab-icon text-zinc-700">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                        <line x1="7" y1="7" x2="7.01" y2="7"></line>
                    </svg>
                </div>
                <span class="tab-label text-[10px] sm:text-[11px] font-medium tracking-tight mt-1 transition-colors duration-200">Remises</span>
            </a>

            <!-- 5. Commandes -->
            <a href="#orders" data-view="orders" class="admin-dock-tab relative flex-1 flex flex-col items-center justify-center py-1 px-1 rounded-2xl group cursor-pointer text-zinc-600 hover:text-zinc-900 select-none">
                <div class="tab-icon relative text-zinc-700">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    <span id="dock-pending-badge" class="hidden absolute -top-1 -right-2 px-1.5 py-0.2 rounded-full bg-amber-500 text-white font-black text-[9px] shadow-xs">0</span>
                </div>
                <span class="tab-label text-[10px] sm:text-[11px] font-medium tracking-tight mt-1 transition-colors duration-200">Commandes</span>
            </a>

            <!-- 6. Messages -->
            <a href="#messages" data-view="messages" class="admin-dock-tab relative flex-1 flex flex-col items-center justify-center py-1 px-1 rounded-2xl group cursor-pointer text-zinc-600 hover:text-zinc-900 select-none">
                <div class="tab-icon relative text-zinc-700 flex items-center justify-center">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <span id="dock-unread-badge" class="hidden absolute -top-1.5 -right-2.5 min-w-[17px] h-[17px] px-1 rounded-full bg-red-600 text-white font-black text-[9px] leading-none flex items-center justify-center shadow-xs ring-2 ring-white">0</span>
                </div>
                <span class="tab-label text-[10px] sm:text-[11px] font-medium tracking-tight mt-1 transition-colors duration-200">Messages</span>
            </a>

        </nav>
    </div>

</div>

<!-- Modal & Drawer Mount Roots -->
<div id="admin-modal-root"></div>
<div id="admin-drawer-root"></div>
<div id="admin-toast-container" class="fixed bottom-24 sm:bottom-28 left-1/2 -translate-x-1/2 z-50 flex flex-col items-center gap-2 pointer-events-none w-max max-w-[90vw]"></div>

</body>
</html>
