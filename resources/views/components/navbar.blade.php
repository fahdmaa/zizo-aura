<header class="w-full bg-white border-b border-zinc-100 sticky top-0 z-50 transition-colors">
    <!-- Top Announcement Scrolling Band -->
    <div class="w-full bg-black text-white text-[10px] sm:text-[11px] font-extrabold uppercase tracking-widest py-2 sm:py-2.5 overflow-hidden border-b border-zinc-900 select-none relative z-50">
        <div class="animate-marquee-top flex items-center whitespace-nowrap">
            @for($i = 0; $i < 6; $i++)
                <div class="flex items-center gap-5 sm:gap-6 mx-3 sm:mx-4 shrink-0">
                    <span class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-pink-500 animate-pulse"></span>
                        <span class="text-white">LIVRAISON OFFERTE DÈS 550 DH DE COMMANDE</span>
                    </span>
                    <span class="text-zinc-600 font-normal">&bull;</span>
                    <span class="text-zinc-300 font-semibold tracking-wider">PARTOUT AU MAROC</span>
                    <span class="text-zinc-600 font-normal">&bull;</span>
                    <span class="text-pink-400 font-bold">ÉCHANTILLONS OFFERTS</span>
                    <span class="text-zinc-600 font-normal">&bull;</span>
                    <span class="text-zinc-300 font-semibold">EXPÉDITION EXPRESS 24/48H</span>
                    <span class="text-zinc-600 font-normal">&bull;</span>
                </div>
            @endfor
        </div>
    </div>

    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between">
        
        <!-- Left: Hamburger Button (Mobile) + Brand Logo -->
        <div class="flex-1 flex items-center justify-start gap-2">
            <button type="button"
                    id="mobile-menu-open-btn"
                    aria-label="Ouvrir le menu de navigation"
                    aria-expanded="false"
                    aria-controls="mobile-menu-drawer"
                    class="md:hidden p-2 -ml-1 text-zinc-900 hover:text-pink-600 focus:outline-none transition-colors cursor-pointer">
                <i class="ti ti-menu-2 text-2xl"></i>
            </button>

            <a href="{{ route('home') }}" class="text-2xl sm:text-[28px] font-extrabold tracking-tight text-black hover:opacity-80 transition-opacity lowercase">
                <span>zizo aura</span>
            </a>
        </div>

        <!-- Center: Navigation Menu in the Middle of Banner (Desktop) -->
        <nav class="hidden md:flex items-center justify-center gap-8 font-bold text-xs uppercase tracking-wider text-zinc-900">
            <!-- Home Link -->
            <a href="{{ route('home') }}" class="hover:text-pink-600 transition-colors {{ (request()->routeIs('home') || request()->is('/')) && !request()->routeIs('shop.*') && !request()->routeIs('contact') ? 'text-pink-600' : '' }}">
                <span>ACCUEIL</span>
            </a>

            <!-- Boutique Dropdown Menu with Touch & Click Support -->
            <div class="relative group py-4" id="nav-boutique-wrapper">
                <a href="{{ route('shop.index') }}"
                   id="nav-boutique-link"
                   aria-haspopup="true"
                   aria-expanded="false"
                   class="flex items-center gap-1.5 hover:text-pink-600 transition-colors {{ request()->routeIs('shop.*') ? 'text-pink-600' : '' }}">
                    <span>BOUTIQUE</span>
                    <i class="ti ti-chevron-down text-xs group-hover:rotate-180 transition-transform duration-200" id="nav-boutique-chevron"></i>
                </a>

                <!-- Custom Dropdown Sub-menu Panel matching Website Theme -->
                <div id="nav-boutique-dropdown"
                     class="absolute top-full left-1/2 -translate-x-1/2 w-64 bg-white/95 backdrop-blur-md rounded-2xl shadow-[0_20px_45px_rgba(0,0,0,0.12)] border border-zinc-100 p-2 opacity-0 invisible translate-y-1 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-200 z-50">
                    <a href="{{ route('shop.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold text-zinc-800 hover:bg-pink-50 hover:text-pink-600 transition-colors group/item">
                        <span>Tous les packs &amp; produits</span>
                        <i class="ti ti-chevron-right text-xs opacity-0 group-hover/item:opacity-100 -translate-x-1 group-hover/item:translate-x-0 transition-all text-pink-600"></i>
                    </a>
                    <a href="{{ route('shop.index', ['category' => 'sol-de-janeiro']) }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold text-zinc-800 hover:bg-pink-50 hover:text-pink-600 transition-colors group/item">
                        <span>Sol de Janeiro Packs</span>
                        <i class="ti ti-chevron-right text-xs opacity-0 group-hover/item:opacity-100 -translate-x-1 group-hover/item:translate-x-0 transition-all text-pink-600"></i>
                    </a>
                    <a href="{{ route('shop.index', ['category' => 'victorias-secret']) }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold text-zinc-800 hover:bg-pink-50 hover:text-pink-600 transition-colors group/item">
                        <span>Victoria's Secret Duos</span>
                        <i class="ti ti-chevron-right text-xs opacity-0 group-hover/item:opacity-100 -translate-x-1 group-hover/item:translate-x-0 transition-all text-pink-600"></i>
                    </a>
                    <a href="{{ route('shop.index', ['category' => 'rituals']) }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold text-zinc-800 hover:bg-pink-50 hover:text-pink-600 transition-colors group/item">
                        <span>Rituals Coffrets</span>
                        <i class="ti ti-chevron-right text-xs opacity-0 group-hover/item:opacity-100 -translate-x-1 group-hover/item:translate-x-0 transition-all text-pink-600"></i>
                    </a>
                </div>
            </div>

            <a href="{{ route('contact') }}" class="hover:text-pink-600 transition-colors {{ request()->routeIs('contact') ? 'text-pink-600' : '' }}">
                <span>CONTACT</span>
            </a>
        </nav>

        <!-- Right: Minimalist Expandable Search + Cart -->
        <div class="flex-1 flex items-center justify-end gap-1.5 sm:gap-2 relative">
            
            <!-- Inline Expandable Minimalist Search Bar -->
            <div id="search-wrapper" class="relative flex items-center">
                <form id="navbar-search-form" method="GET" action="{{ route('shop.index') }}" class="relative flex items-center">
                    
                    <!-- Search Pill (Grows inside banner without overlapping menu) -->
                    <div id="search-pill-container" class="search-pill is-collapsed">
                        
                        <!-- Search Icon Button -->
                        <button type="button"
                                id="search-expand-btn"
                                aria-label="Rechercher"
                                aria-expanded="false"
                                aria-controls="navbar-search-input"
                                class="search-btn-icon">
                            <i class="ti ti-search text-lg"></i>
                        </button>

                        <!-- Input Field (smooth width expansion) -->
                        <input type="text"
                               id="navbar-search-input"
                               name="q"
                               aria-label="Rechercher un produit ou rituel"
                               value="{{ request('q', '') }}"
                               autocomplete="off"
                               placeholder="Rechercher un produit..."
                               class="search-input-field" />

                        <!-- Clear Icon -->
                        <button type="button"
                                id="navbar-clear-btn"
                                aria-label="Effacer la recherche"
                                class="search-clear-btn hidden">
                            <i class="ti ti-x text-xs"></i>
                        </button>
                    </div>
                </form>

                <!-- Floating Suggestions Box -->
                <div id="navbar-suggestions-box" class="absolute right-0 top-full mt-2.5 w-72 sm:w-80 bg-white/95 backdrop-blur-md rounded-2xl shadow-[0_20px_45px_rgba(0,0,0,0.12)] border border-zinc-100 p-2 hidden z-50">
                    <div id="navbar-results-list" class="divide-y divide-zinc-100 max-h-[340px] overflow-y-auto">
                        <!-- Live suggestions injected here via JS -->
                    </div>
                </div>
            </div>

            <!-- Cart Bag Button -->
            <button type="button"
                    id="navbar-cart-btn"
                    aria-label="Voir mon panier"
                    class="relative p-2 text-zinc-900 hover:text-pink-600 transition-colors shrink-0 cursor-pointer">
                <i class="ti ti-shopping-bag text-2xl"></i>
                <span id="cart-count-badge" class="absolute -top-1 -right-1 w-5 h-5 bg-black text-white text-[10px] font-black rounded-full flex items-center justify-center">
                    0
                </span>
            </button>

        </div>

    </div>

    <!-- Mobile Slide-over Navigation Drawer -->
    <div id="mobile-menu-backdrop" class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 opacity-0 pointer-events-none transition-opacity duration-300">
        
        <div id="mobile-menu-drawer" role="dialog" aria-modal="true" aria-label="Menu de navigation mobile" class="fixed inset-y-0 left-0 w-[310px] max-w-[85vw] bg-white z-50 shadow-2xl flex flex-col justify-between transform -translate-x-full transition-transform duration-300 ease-out">
            
            <!-- Mobile Menu Header -->
            <div>
                <div class="p-5 border-b border-zinc-100 flex items-center justify-between">
                    <a href="{{ route('home') }}" class="text-2xl font-extrabold tracking-tight text-black lowercase">
                        <span>zizo aura</span>
                    </a>
                    
                    <button type="button"
                            id="mobile-menu-close-btn"
                            aria-label="Fermer le menu"
                    class="btn-circle-action w-9 h-9 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 flex items-center justify-center cursor-pointer">
                        <i class="ti ti-x text-lg"></i>
                    </button>
                </div>

                <!-- Navigation Links List -->
                <div class="p-4 space-y-1">
                    <a href="{{ route('home') }}" class="flex items-center justify-between px-4 py-3 rounded-2xl text-xs font-extrabold uppercase tracking-wider transition-colors {{ (request()->routeIs('home') || request()->is('/')) && !request()->routeIs('shop.*') && !request()->routeIs('contact') ? 'bg-pink-50 text-pink-600' : 'text-zinc-900 hover:bg-zinc-50' }}">
                        <span>Accueil</span>
                        <i class="ti ti-home text-base"></i>
                    </a>

                    <!-- Boutique Accordion / Submenu -->
                    <div class="rounded-2xl {{ request()->routeIs('shop.*') ? 'bg-zinc-50/80' : '' }}">
                        <button type="button"
                                id="mobile-boutique-toggle"
                                aria-expanded="false"
                                aria-controls="mobile-boutique-sublinks"
                                class="w-full flex items-center justify-between px-4 py-3 text-xs font-extrabold uppercase tracking-wider text-zinc-900 hover:text-pink-600 transition-colors cursor-pointer">
                            <span class="flex items-center gap-2">
                                <span>Boutique</span>
                                <span class="px-1.5 py-0.5 rounded-full bg-pink-100 text-pink-600 text-[10px] font-black">16</span>
                            </span>
                            <i id="mobile-boutique-chevron" class="ti ti-chevron-down text-xs transition-transform duration-200"></i>
                        </button>

                        <div id="mobile-boutique-sublinks" class="space-y-1 px-3 pb-2">
                            <a href="{{ route('shop.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold text-zinc-700 hover:text-pink-600 hover:bg-pink-50/60 transition-colors">
                                <span>Tous les packs &amp; produits</span>
                                <i class="ti ti-chevron-right text-xs text-pink-600"></i>
                            </a>
                            <a href="{{ route('shop.index', ['category' => 'sol-de-janeiro']) }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold text-zinc-700 hover:text-pink-600 hover:bg-pink-50/60 transition-colors">
                                <span>Sol de Janeiro Packs</span>
                                <i class="ti ti-chevron-right text-xs text-pink-600"></i>
                            </a>
                            <a href="{{ route('shop.index', ['category' => 'victorias-secret']) }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold text-zinc-700 hover:text-pink-600 hover:bg-pink-50/60 transition-colors">
                                <span>Victoria's Secret Duos</span>
                                <i class="ti ti-chevron-right text-xs text-pink-600"></i>
                            </a>
                            <a href="{{ route('shop.index', ['category' => 'rituals']) }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold text-zinc-700 hover:text-pink-600 hover:bg-pink-50/60 transition-colors">
                                <span>Rituals Coffrets</span>
                                <i class="ti ti-chevron-right text-xs text-pink-600"></i>
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('contact') }}" class="flex items-center justify-between px-4 py-3 rounded-2xl text-xs font-extrabold uppercase tracking-wider transition-colors {{ request()->routeIs('contact') ? 'bg-pink-50 text-pink-600' : 'text-zinc-900 hover:bg-zinc-50' }}">
                        <span>Contact &amp; Support</span>
                        <i class="ti ti-mail text-base"></i>
                    </a>
                </div>
            </div>

            <!-- Mobile Menu Footer -->
            <div class="p-5 border-t border-zinc-100 bg-[#fbfbfc] space-y-3">
                <div class="p-3 bg-pink-50 rounded-2xl border border-pink-100 text-center">
                    <p class="text-xs font-black text-pink-600 uppercase tracking-wider flex items-center justify-center gap-1.5">
                        <i class="ti ti-truck-delivery text-base"></i>
                        <span>Livraison Offerte dès 550 DH</span>
                    </p>
                    <p class="text-[10px] text-zinc-500 font-semibold mt-0.5">Partout au Maroc sous 24/48h</p>
                </div>

                <div class="flex items-center justify-center gap-4 text-zinc-600 pt-1">
                    <a href="https://www.instagram.com/zizo_aura_/" target="_blank" rel="noopener noreferrer" aria-label="Instagram @zizo_aura_" class="btn-circle-action w-9 h-9 bg-white border border-zinc-200 hover:border-pink-500 hover:text-pink-600 flex items-center justify-center text-base shadow-2xs">
                        <i class="ti ti-brand-instagram"></i>
                    </a>
                    <a href="https://www.tiktok.com/@zizo_aura_" target="_blank" rel="noopener noreferrer" aria-label="TikTok @zizo_aura_" class="btn-circle-action w-9 h-9 bg-white border border-zinc-200 hover:border-pink-500 hover:text-pink-600 flex items-center justify-center text-base shadow-2xs">
                        <i class="ti ti-brand-tiktok"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</header>
