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

        /* Middle Navbar Notch & Floating Indicator Animation */
        .admin-nav-pill-wrapper {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        #navbar-notch-wrapper {
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        #mobile-notch-wrapper {
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
    </style>
</head>
<body class="h-full antialiased overflow-x-hidden pb-24 md:pb-12">

<div class="min-h-full flex flex-col">

    <!-- Top Floating App Header with Centered Middle-Style Pill Navigation -->
    <header class="sticky top-0 z-40 bg-[#faf9f6]/85 backdrop-blur-md border-b border-zinc-200/60 px-4 md:px-8 py-3 transition-all">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            
            <!-- Brand Logo Left -->
            <a href="#dashboard" class="flex items-center gap-2.5 group shrink-0">
                <div class="w-9 h-9 rounded-2xl bg-white border border-pink-100 text-pink-600 flex items-center justify-center font-black text-sm group-hover:scale-105 transition-transform shadow-xs">
                    ZA
                </div>
                <div class="hidden sm:block">
                    <span class="font-black text-lg tracking-tight text-zinc-900 group-hover:text-pink-600 transition-colors">zizo aura</span>
                    <span class="block text-[9px] uppercase font-extrabold tracking-widest text-pink-600">Admin Suite</span>
                </div>
            </a>

            <!-- CENTER: The Redesigned Floating Pill Navbar (Middle Version from Reference) -->
            <div class="hidden md:flex items-center justify-center flex-1 max-w-2xl px-2">
                <nav id="admin-pill-nav" class="relative bg-white border border-zinc-200/80 rounded-[30px] px-2 py-1.5 shadow-lg shadow-zinc-200/40 flex items-center justify-between w-full">
                    
                    <!-- Dynamic Sliding Curved Notch with Floating Indicator Dot -->
                    <div id="navbar-notch-wrapper" class="absolute -top-[1px] left-0 pointer-events-none z-20 flex flex-col items-center" style="width: 72px; transform: translateX(0px);">
                        <!-- Floating Circular Dot nestled right inside the scoop notch -->
                        <div class="w-2.5 h-2.5 rounded-full bg-[#ff1b7a] shadow-[0_2px_8px_rgba(255,27,122,0.55)] border border-white -translate-y-1"></div>
                        <!-- Scooped Cutout Mask SVG cutting gracefully into the white navbar container -->
                        <svg class="w-[72px] h-[9px] text-[#faf9f6] -mt-[1px]" viewBox="0 0 72 9" fill="currentColor">
                            <path d="M0,0 C20,0 22,9 36,9 C50,9 52,0 72,0 L72,0 Z" />
                        </svg>
                    </div>

                    <!-- 1. Accueil / Dashboard -->
                    <a href="#dashboard" data-view="dashboard" class="admin-pill-tab relative flex-1 flex flex-col items-center justify-center py-1.5 px-2 rounded-2xl group cursor-pointer transition-colors duration-200 text-zinc-600 hover:text-zinc-900">
                        <div class="tab-icon-wrap relative transition-transform duration-200 group-hover:scale-110 text-zinc-700">
                            <svg class="w-[20px] h-[20px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                        </div>
                        <span class="tab-label text-[10.5px] font-medium tracking-tight mt-0.5 transition-colors duration-200">Accueil</span>
                    </a>

                    <!-- 2. Produits -->
                    <a href="#products" data-view="products" class="admin-pill-tab relative flex-1 flex flex-col items-center justify-center py-1.5 px-2 rounded-2xl group cursor-pointer transition-colors duration-200 text-zinc-600 hover:text-zinc-900">
                        <div class="tab-icon-wrap relative transition-transform duration-200 group-hover:scale-110 text-zinc-700">
                            <svg class="w-[20px] h-[20px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                        </div>
                        <span class="tab-label text-[10.5px] font-medium tracking-tight mt-0.5 transition-colors duration-200">Produits</span>
                    </a>

                    <!-- 3. Catégories -->
                    <a href="#categories" data-view="categories" class="admin-pill-tab relative flex-1 flex flex-col items-center justify-center py-1.5 px-2 rounded-2xl group cursor-pointer transition-colors duration-200 text-zinc-600 hover:text-zinc-900">
                        <div class="tab-icon-wrap relative transition-transform duration-200 group-hover:scale-110 text-zinc-700">
                            <svg class="w-[20px] h-[20px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                                <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                                <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                                <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                            </svg>
                        </div>
                        <span class="tab-label text-[10.5px] font-medium tracking-tight mt-0.5 transition-colors duration-200">Catégories</span>
                    </a>

                    <!-- 4. Remises -->
                    <a href="#discounts" data-view="discounts" class="admin-pill-tab relative flex-1 flex flex-col items-center justify-center py-1.5 px-2 rounded-2xl group cursor-pointer transition-colors duration-200 text-zinc-600 hover:text-zinc-900">
                        <div class="tab-icon-wrap relative transition-transform duration-200 group-hover:scale-110 text-zinc-700">
                            <svg class="w-[20px] h-[20px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                <line x1="7" y1="7" x2="7.01" y2="7"></line>
                            </svg>
                        </div>
                        <span class="tab-label text-[10.5px] font-medium tracking-tight mt-0.5 transition-colors duration-200">Remises</span>
                    </a>

                    <!-- 5. Commandes -->
                    <a href="#orders" data-view="orders" class="admin-pill-tab relative flex-1 flex flex-col items-center justify-center py-1.5 px-2 rounded-2xl group cursor-pointer transition-colors duration-200 text-zinc-600 hover:text-zinc-900">
                        <div class="tab-icon-wrap relative transition-transform duration-200 group-hover:scale-110 text-zinc-700">
                            <svg class="w-[20px] h-[20px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                            <span id="pill-pending-badge" class="hidden absolute -top-1 -right-2 px-1.5 py-0.2 rounded-full bg-amber-500 text-white font-black text-[9px] shadow-xs">0</span>
                        </div>
                        <span class="tab-label text-[10.5px] font-medium tracking-tight mt-0.5 transition-colors duration-200">Commandes</span>
                    </a>

                    <!-- 6. Messages -->
                    <a href="#messages" data-view="messages" class="admin-pill-tab relative flex-1 flex flex-col items-center justify-center py-1.5 px-2 rounded-2xl group cursor-pointer transition-colors duration-200 text-zinc-600 hover:text-zinc-900">
                        <div class="tab-icon-wrap relative transition-transform duration-200 group-hover:scale-110 text-zinc-700">
                            <svg class="w-[20px] h-[20px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                            </svg>
                            <span id="pill-unread-badge" class="hidden absolute -top-1 -right-2 px-1.5 py-0.2 rounded-full bg-pink-600 text-white font-black text-[9px] shadow-xs">0</span>
                        </div>
                        <span class="tab-label text-[10.5px] font-medium tracking-tight mt-0.5 transition-colors duration-200">Messages</span>
                    </a>

                </nav>
            </div>

            <!-- Header Actions Right -->
            <div class="flex items-center gap-2.5 shrink-0">
                <button type="button" onclick="if(window.adminApp) window.adminApp.handleHashChange();" class="p-2 rounded-xl bg-white border border-zinc-200/80 text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50 text-xs font-semibold flex items-center gap-1.5 transition shadow-xs cursor-pointer" title="Rafraîchir les données">
                    <i class="ti ti-refresh text-sm"></i>
                    <span class="hidden lg:inline">Actualiser</span>
                </button>

                <a href="{{ url('/boutique') }}" target="_blank" class="px-3 py-2 rounded-xl bg-white border border-zinc-200/80 hover:border-pink-300 text-zinc-800 hover:text-pink-600 text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                    <span>Boutique</span>
                    <i class="ti ti-external-link text-xs text-zinc-400"></i>
                </a>

                <!-- Admin Profile Dropdown / Logout -->
                <div class="relative group">
                    <button type="button" class="flex items-center gap-2 p-1 pl-2.5 pr-2 rounded-2xl bg-white border border-zinc-200/80 hover:border-zinc-300 transition shadow-xs cursor-pointer">
                        <span class="text-xs font-bold text-zinc-800 hidden sm:inline">Admin</span>
                        <div class="w-7 h-7 rounded-xl bg-zinc-900 text-white flex items-center justify-center text-xs font-bold">
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
                            <button type="submit" class="w-full py-2 px-3 rounded-xl hover:bg-red-50 text-zinc-600 hover:text-red-600 text-xs font-bold transition flex items-center gap-2 cursor-pointer text-left">
                                <i class="ti ti-logout text-sm"></i>
                                <span>Déconnexion</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </header>

    <!-- Mobile Floating Bottom Navbar (Middle Version from Reference) -->
    <div class="md:hidden fixed bottom-3 left-3 right-3 z-50 max-w-lg mx-auto">
        <nav id="mobile-pill-nav" class="relative bg-white/95 backdrop-blur-md border border-zinc-200/80 rounded-[30px] px-2 py-1.5 shadow-2xl shadow-zinc-900/10 flex items-center justify-between w-full">
            
            <!-- Dynamic Sliding Curved Notch for Mobile -->
            <div id="mobile-notch-wrapper" class="absolute -top-[1px] left-0 pointer-events-none z-20 flex flex-col items-center" style="width: 58px; transform: translateX(0px);">
                <!-- Floating Circular Dot -->
                <div class="w-2.5 h-2.5 rounded-full bg-[#ff1b7a] shadow-[0_2px_8px_rgba(255,27,122,0.55)] border border-white -translate-y-1"></div>
                <!-- Scooped Cutout Mask SVG -->
                <svg class="w-[58px] h-[8px] text-[#faf9f6] -mt-[1px]" viewBox="0 0 58 8" fill="currentColor">
                    <path d="M0,0 C16,0 18,8 29,8 C40,8 42,0 58,0 L58,0 Z" />
                </svg>
            </div>

            <!-- Mobile Tabs -->
            <a href="#dashboard" data-view="dashboard" class="mobile-pill-tab relative flex-1 flex flex-col items-center justify-center py-1 rounded-xl text-zinc-600">
                <div class="tab-icon-wrap text-zinc-700">
                    <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                </div>
                <span class="tab-label text-[9.5px] font-medium mt-0.5">Accueil</span>
            </a>

            <a href="#products" data-view="products" class="mobile-pill-tab relative flex-1 flex flex-col items-center justify-center py-1 rounded-xl text-zinc-600">
                <div class="tab-icon-wrap text-zinc-700">
                    <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                </div>
                <span class="tab-label text-[9.5px] font-medium mt-0.5">Produits</span>
            </a>

            <a href="#categories" data-view="categories" class="mobile-pill-tab relative flex-1 flex flex-col items-center justify-center py-1 rounded-xl text-zinc-600">
                <div class="tab-icon-wrap text-zinc-700">
                    <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                        <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                    </svg>
                </div>
                <span class="tab-label text-[9.5px] font-medium mt-0.5">Catégories</span>
            </a>

            <a href="#discounts" data-view="discounts" class="mobile-pill-tab relative flex-1 flex flex-col items-center justify-center py-1 rounded-xl text-zinc-600">
                <div class="tab-icon-wrap text-zinc-700">
                    <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                        <line x1="7" y1="7" x2="7.01" y2="7"></line>
                    </svg>
                </div>
                <span class="tab-label text-[9.5px] font-medium mt-0.5">Remises</span>
            </a>

            <a href="#orders" data-view="orders" class="mobile-pill-tab relative flex-1 flex flex-col items-center justify-center py-1 rounded-xl text-zinc-600">
                <div class="tab-icon-wrap relative text-zinc-700">
                    <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                </div>
                <span class="tab-label text-[9.5px] font-medium mt-0.5">Commandes</span>
            </a>

            <a href="#messages" data-view="messages" class="mobile-pill-tab relative flex-1 flex flex-col items-center justify-center py-1 rounded-xl text-zinc-600">
                <div class="tab-icon-wrap relative text-zinc-700">
                    <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                </div>
                <span class="tab-label text-[9.5px] font-medium mt-0.5">Messages</span>
            </a>

        </nav>
    </div>

    <!-- Main Content Body -->
    <main class="flex-1 p-4 md:p-8 max-w-7xl w-full mx-auto">
        <!-- Root Container for Dynamic SPA Views -->
        <div id="admin-app-root">
            @yield('content')
        </div>
    </main>

</div>

<!-- Modal & Drawer Mount Roots -->
<div id="admin-modal-root"></div>
<div id="admin-drawer-root"></div>
<div id="admin-toast-container"></div>

</body>
</html>
