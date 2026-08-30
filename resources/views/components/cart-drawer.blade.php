<!-- Slide-over Cart Drawer Component -->
<div id="cart-drawer-backdrop" class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 opacity-0 pointer-events-none transition-opacity duration-300" role="dialog" aria-modal="true" aria-labelledby="cart-drawer-title">
    
    <div id="cart-drawer-panel" class="fixed inset-y-0 right-0 w-[400px] max-w-[92vw] bg-white z-50 shadow-2xl flex flex-col justify-between transform translate-x-full transition-transform duration-300 ease-out">
        
        <!-- Cart Header -->
        <div class="p-4 sm:p-5 border-b border-zinc-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="ti ti-shopping-bag text-2xl text-zinc-900"></i>
                <h3 id="cart-drawer-title" class="text-base font-extrabold text-zinc-900 tracking-tight uppercase">Mon Panier</h3>
                <span id="cart-drawer-count" class="px-2 py-0.5 rounded-full bg-pink-100 text-pink-600 text-xs font-black">0</span>
            </div>
            
            <button type="button"
                    id="cart-drawer-close-btn"
                    aria-label="Fermer le panier"
                    class="w-9 h-9 rounded-full bg-zinc-100 hover:bg-zinc-200 text-zinc-700 flex items-center justify-center transition-colors cursor-pointer">
                <i class="ti ti-x text-lg"></i>
            </button>
        </div>

        <!-- Free Delivery Progress Bar (550 DH Threshold) -->
        <div class="px-5 py-3.5 bg-[#f8f9fa] border-b border-zinc-100">
            <div class="flex items-center justify-between text-xs font-bold mb-1.5" id="shipping-progress-text">
                <span class="text-zinc-600 flex items-center gap-1.5">
                    <i class="ti ti-truck-delivery text-pink-600 text-sm"></i>
                    <span id="shipping-status-label">Livraison offerte dès 550 DH</span>
                </span>
                <span id="shipping-remaining-amount" class="text-pink-600 font-extrabold">Plus que 550 DH</span>
            </div>
            <div class="w-full bg-zinc-200 rounded-full h-1.5 overflow-hidden">
                <div id="shipping-progress-bar" class="bg-gradient-to-r from-pink-500 to-rose-500 h-1.5 rounded-full transition-all duration-500 w-0"></div>
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
                <div class="flex justify-between text-zinc-500">
                    <span>Livraison Maroc</span>
                    <span id="cart-drawer-shipping" class="font-bold text-emerald-600">Calculée au paiement</span>
                </div>
                <div class="flex justify-between text-sm font-extrabold text-zinc-900 pt-2 border-t border-zinc-200">
                    <span>Total estimé</span>
                    <span id="cart-drawer-total" class="text-base text-pink-600 font-black">0 DH</span>
                </div>
            </div>

            <!-- WhatsApp Direct Order Button (Popular in Morocco) & Shop Link -->
            <div class="space-y-2 pt-1">
                <a id="cart-whatsapp-btn"
                   href="https://wa.me/212600000000?text=Bonjour%20zizo%20aura%2C%20je%20souhaite%20commander"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="btn-card-pill w-full py-3.5 text-xs font-extrabold uppercase tracking-wider bg-emerald-600 hover:bg-emerald-700 hover:border-emerald-700 hover:shadow-[0_8px_20px_rgba(16,185,129,0.3)]">
                    <i class="ti ti-brand-whatsapp text-lg"></i>
                    <span>Commander via WhatsApp</span>
                </a>

                <button type="button"
                        id="cart-continue-shopping-btn"
                        class="w-full py-2.5 text-center text-xs font-bold text-zinc-500 hover:text-black transition-colors block cursor-pointer">
                    Continuer mes achats &rarr;
                </button>
            </div>

            <p class="text-[10px] text-center text-zinc-400 font-medium">
                Paiement à la livraison partout au Maroc 🇲🇦 &bull; Échantillons offerts
            </p>
        </div>

    </div>
</div>
