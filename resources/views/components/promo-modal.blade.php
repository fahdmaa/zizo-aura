<!-- Summer Discount Promo Modal -->
<div id="promo-modal-backdrop" class="fixed inset-0 bg-black/65 backdrop-blur-sm z-50 flex items-center justify-center p-3 sm:p-4 opacity-0 pointer-events-none transition-opacity duration-400 ease-out" role="dialog" aria-modal="true" aria-labelledby="promo-modal-title">
    
    <div id="promo-modal-card" class="bg-white rounded-3xl overflow-hidden shadow-[0_25px_60px_rgba(0,0,0,0.3)] max-w-3xl w-full grid grid-cols-1 md:grid-cols-12 relative transform scale-90 transition-transform duration-400 ease-out border border-zinc-100 max-h-[92vh] overflow-y-auto no-scrollbar">
        
        <!-- Close Button -->
        <button id="close-promo-modal"
                type="button"
                aria-label="Fermer la promotion"
                class="absolute top-3.5 right-3.5 z-30 w-9 h-9 rounded-full bg-white/90 hover:bg-black hover:text-white text-zinc-700 flex items-center justify-center shadow-md transition-all duration-200 hover:scale-110 cursor-pointer">
            <i class="ti ti-x text-lg"></i>
        </button>

        <!-- Left Column: Attached Model Image -->
        <div class="md:col-span-5 relative bg-amber-50 min-h-[260px] md:min-h-[380px] overflow-hidden flex items-center justify-center">
            <img src="/images/popup_summer_sale.png"
                 alt="Offre Été Sol de Janeiro — zizo aura"
                 class="w-full h-full object-cover object-center select-none" />

            <!-- Floating Discount Pill on Image -->
            <div class="absolute bottom-3 left-3 z-20">
                <span class="px-3 py-1 rounded-full bg-amber-400 text-amber-950 text-xs font-black uppercase tracking-wider shadow-md">
                    Jusqu'à -35%
                </span>
            </div>
        </div>

        <!-- Right Column: Promo Content & Call to Action -->
        <div class="md:col-span-7 p-6 sm:p-8 flex flex-col justify-between">
            <div>

                <!-- Headline -->
                <h3 id="promo-modal-title" class="text-2xl sm:text-3xl font-extrabold text-zinc-900 tracking-tight leading-tight mb-2 pt-2 sm:pt-4">
                    Sublimez votre été avec les packs Sol de Janeiro !
                </h3>

                <!-- Description -->
                <p class="text-xs sm:text-sm text-zinc-500 font-medium leading-relaxed mb-5">
                    Craquez pour nos coffrets cultes <em>Bum Bum Cream</em> et brumes <em>Cheirosa</em> avec jusqu'à <strong class="text-zinc-900 font-bold">-35% de réduction</strong> immédiate.
                </p>

                <!-- Promo Code Pill Box -->
                <div class="bg-[#f8f9fa] rounded-2xl p-3.5 border border-zinc-200/80 mb-6 flex items-center justify-between gap-3">
                    <div class="flex flex-col">
                        <span class="text-[10px] uppercase font-bold text-zinc-400 tracking-wider">Code Promo au paiement</span>
                        <span id="promo-code-text" class="text-base font-black text-pink-600 tracking-wider">RIO35</span>
                    </div>

                    <button id="copy-promo-code"
                            class="px-3.5 py-1.5 rounded-xl bg-white hover:bg-black hover:text-white border border-zinc-200 text-zinc-800 text-xs font-bold transition-all duration-200 flex items-center gap-1.5 cursor-pointer shadow-xs">
                        <i class="ti ti-copy text-xs"></i>
                        <span id="copy-text">Copier</span>
                    </button>
                </div>
            </div>

            <!-- CTA Buttons -->
            <div class="space-y-3">
                <a href="{{ route('shop.index', ['category' => 'sol-de-janeiro']) }}"
                   class="btn-card-pill w-full py-3.5 text-sm font-extrabold uppercase tracking-wider">
                    <span>Profiter de l'offre Sol de Janeiro</span>
                    <i class="ti ti-arrow-right text-base"></i>
                </a>

                <p class="text-center text-[10px] text-zinc-400 font-medium">
                    *Livraison offerte dès 550 DH &bull; Expédié sous 24h
                </p>
            </div>
        </div>

    </div>
</div>
