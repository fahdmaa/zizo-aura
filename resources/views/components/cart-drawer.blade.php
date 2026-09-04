<!-- Slide-over Cart Drawer Component -->
<div id="cart-drawer-backdrop" class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 opacity-0 pointer-events-none transition-opacity duration-300" role="dialog" aria-modal="true" aria-labelledby="cart-drawer-title">
    
    <div id="cart-drawer-panel" class="fixed inset-y-0 right-0 w-[400px] max-w-[92vw] bg-white z-50 shadow-2xl flex flex-col justify-between transform translate-x-full transition-transform duration-300 ease-out">
        
        <!-- Cart Header -->
        <div class="p-4 sm:p-5 border-b border-zinc-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="uil uil-shopping-bag text-2xl text-zinc-900"></i>
                <h3 id="cart-drawer-title" class="text-base font-extrabold text-zinc-900 tracking-tight uppercase">Mon Panier</h3>
                <span id="cart-drawer-count" class="px-2 py-0.5 rounded-full bg-pink-100 text-pink-600 text-xs font-black">0</span>
            </div>
            
            <button type="button"
                    id="cart-drawer-close-btn"
                    aria-label="Fermer le panier"
                    class="btn-circle-action w-9 h-9 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 flex items-center justify-center cursor-pointer">
                <i class="uil uil-multiply text-lg"></i>
            </button>
        </div>

        <!-- Discount / Promo Code Card -->
        <div class="px-5 py-3.5 bg-[#f8f9fa] border-b border-zinc-100">
            <!-- Discount / Promo Code Form in Same Card -->
            <div id="cart-coupon-card-section">
                <!-- Input Form when no coupon is applied -->
                <div id="coupon-input-wrapper" class="flex items-center gap-2">
                    <input type="text"
                           id="cart-coupon-input"
                           placeholder="Code promo"
                           autocomplete="off"
                           class="flex-1 !h-10.5 !px-4 text-xs uppercase font-bold !rounded-full bg-white border border-zinc-200 hover:border-pink-300 focus:border-pink-500 focus:ring-4 focus:ring-pink-500/12 outline-none transition-all placeholder:normal-case placeholder:font-normal placeholder:text-zinc-400">
                    <button type="button"
                            id="cart-coupon-apply-btn"
                            class="btn-card-pill !h-10.5 !px-5 text-xs uppercase font-extrabold tracking-wider transition-all cursor-pointer shrink-0">
                        Appliquer
                    </button>
                </div>

                <!-- Active Applied Coupon Pill (hidden by default) -->
                <div id="coupon-applied-badge" class="hidden flex items-center justify-between px-4 py-2 !rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold shadow-2xs">
                    <div class="flex items-center gap-1.5 truncate">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                        <span class="text-xs font-semibold text-emerald-700">Code :</span>
                        <span id="applied-coupon-code" class="tracking-wide uppercase font-black"></span>
                        <span id="applied-coupon-discount" class="text-emerald-600 font-extrabold text-xs"></span>
                    </div>
                    <button type="button"
                            id="coupon-remove-btn"
                            title="Retirer le code promo"
                            aria-label="Retirer le code promo"
                            class="w-6 h-6 rounded-full hover:bg-emerald-200/70 text-emerald-800 flex items-center justify-center cursor-pointer transition-colors shrink-0 ml-1">
                        <i class="uil uil-multiply text-xs font-bold"></i>
                    </button>
                </div>

                <!-- Feedback Message -->
                <p id="coupon-feedback-msg" class="hidden text-[11px] font-semibold mt-1 px-2"></p>
            </div>
        </div>

        <!-- Cart Items List / Empty State -->
        <div id="cart-drawer-items" class="flex-1 overflow-y-auto p-4 sm:p-5 divide-y divide-zinc-100 no-scrollbar">
            <!-- Injected via JavaScript -->
        </div>

        <!-- Cart Footer & Checkout Action -->
        <div id="cart-drawer-footer" class="p-5 border-t border-zinc-100 bg-[#fbfbfc] space-y-3">
            <div class="space-y-1.5 text-xs font-semibold">
                <div class="flex justify-between text-zinc-500">
                    <span>Sous-total</span>
                    <span id="cart-drawer-subtotal" class="font-bold text-zinc-900">0 DH</span>
                </div>
                <!-- Discount Row (hidden by default) -->
                <div id="cart-drawer-discount-row" class="hidden flex justify-between text-emerald-600 font-bold">
                    <span id="cart-drawer-discount-label">Remise code promo</span>
                    <span id="cart-drawer-discount-amount">-0 DH</span>
                </div>
                <div class="flex justify-between text-zinc-500">
                    <span>Livraison</span>
                    <span id="cart-drawer-shipping" class="font-bold text-zinc-900">Calculée au paiement</span>
                </div>
                <div class="flex justify-between text-sm font-extrabold text-zinc-900 pt-2 border-t border-zinc-200">
                    <span>Total estimé</span>
                    <span id="cart-drawer-total" class="text-base text-pink-600 font-black">0 DH</span>
                </div>
            </div>

            <!-- WhatsApp Direct Order Button (Popular in Morocco) & Shop Link -->
            <div class="space-y-2 pt-1">
                <a id="cart-whatsapp-btn"
                   href="https://wa.me/212682787594?text=Bonjour%20zizo%20aura%2C%20je%20souhaite%20commander"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="btn-card-pill w-full py-3.5 text-xs font-extrabold uppercase tracking-wider bg-emerald-600 hover:bg-emerald-700 hover:border-emerald-700 hover:shadow-[0_8px_20px_rgba(16,185,129,0.3)]">
                    <i class="uil uil-whatsapp text-lg"></i>
                    <span>Commander via WhatsApp</span>
                </a>

                <button type="button"
                        id="cart-continue-shopping-btn"
                        class="w-full py-2.5 text-center text-xs font-bold text-zinc-500 hover:text-black transition-colors block cursor-pointer">
                    Continuer mes achats &rarr;
                </button>
            </div>

            <p class="text-[10px] text-center text-zinc-400 font-medium">
                Paiement à la livraison partout au Maroc &bull; Échantillons offerts
            </p>
        </div>

    </div>
</div>
