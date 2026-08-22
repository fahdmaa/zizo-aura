<header class="w-full bg-white border-b border-zinc-100 sticky top-0 z-50 transition-colors">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between">
        
        <!-- Left: Brand Logo -->
        <div class="flex-1 flex items-center justify-start">
            <a href="{{ route('home') }}" class="text-2xl sm:text-[28px] font-extrabold tracking-tight text-black hover:opacity-80 transition-opacity lowercase">
                <span>zizo aura</span>
            </a>
        </div>

        <!-- Center: Navigation Menu in the Middle of Banner -->
        <nav class="hidden md:flex items-center justify-center gap-8 font-bold text-xs uppercase tracking-wider text-zinc-900">
            <!-- Home Link -->
            <a href="{{ route('home') }}" class="hover:text-pink-600 transition-colors {{ (request()->routeIs('home') || request()->is('/')) && !request()->routeIs('shop.*') && !request()->routeIs('contact') ? 'text-pink-600' : '' }}">
                <span>ACCUEIL</span>
            </a>

            <!-- Boutique Dropdown Menu -->
            <div class="relative group py-4">
                <a href="{{ route('shop.index') }}" class="flex items-center gap-1.5 hover:text-pink-600 transition-colors {{ request()->routeIs('shop.*') ? 'text-pink-600' : '' }}">
                    <span>BOUTIQUE</span>
                    <i class="ti ti-chevron-down text-xs group-hover:rotate-180 transition-transform duration-200"></i>
                </a>

                <!-- Custom Dropdown Sub-menu Panel matching Website Theme -->
                <div class="absolute top-full left-1/2 -translate-x-1/2 w-64 bg-white/95 backdrop-blur-md rounded-2xl shadow-[0_20px_45px_rgba(0,0,0,0.12)] border border-zinc-100 p-2 opacity-0 invisible translate-y-1 group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-200 z-50">
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
                    <div id="search-pill-container" class="flex items-center transition-all duration-300 ease-out w-10 h-10 bg-transparent rounded-full border border-transparent overflow-hidden">
                        
                        <!-- Search Icon Button -->
                        <button type="button"
                                id="search-expand-btn"
                                aria-label="Rechercher"
                                class="w-10 h-10 flex items-center justify-center text-zinc-800 hover:text-pink-600 shrink-0 transition-colors cursor-pointer">
                            <i class="ti ti-search text-xl"></i>
                        </button>

                        <!-- Input Field (smooth width expansion) -->
                        <input type="text"
                               id="navbar-search-input"
                               name="q"
                               value="{{ request('q', '') }}"
                               autocomplete="off"
                               placeholder="Rechercher..."
                               class="w-0 opacity-0 bg-transparent text-xs font-semibold text-zinc-900 placeholder:text-zinc-400 focus:outline-none transition-all duration-300 pr-2" />

                        <!-- Clear Icon -->
                        <button type="button"
                                id="navbar-clear-btn"
                                aria-label="Effacer"
                                class="hidden p-1 text-zinc-400 hover:text-black rounded-full transition-colors shrink-0 mr-1.5 cursor-pointer">
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
            <button aria-label="Panier" class="relative p-2 text-zinc-900 hover:text-pink-600 transition-colors shrink-0 cursor-pointer">
                <i class="ti ti-shopping-bag text-2xl"></i>
                <span id="cart-count-badge" class="absolute -top-1 -right-1 w-5 h-5 bg-black text-white text-[10px] font-black rounded-full flex items-center justify-center">
                    0
                </span>
            </button>

        </div>

    </div>
</header>
