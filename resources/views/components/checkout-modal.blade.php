<!-- Online Checkout Modal (Paiement à la livraison partout au Maroc) -->
<div id="checkout-modal"
     class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 md:p-6 bg-black/60 backdrop-blur-xs opacity-0 pointer-events-none transition-all duration-300 overflow-y-auto"
     role="dialog"
     aria-modal="true"
     aria-labelledby="checkout-modal-title">
    
    <div id="checkout-modal-container"
         class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl border border-zinc-100 overflow-hidden transform scale-95 opacity-0 transition-all duration-300 my-auto">
        
        <!-- Modal Header -->
        <div class="px-5 py-4 sm:px-6 sm:py-5 border-b border-zinc-100 flex items-center justify-between bg-[#fafafa]">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-zinc-900 text-white flex items-center justify-center text-base shrink-0">
                    <i class="uil uil-truck"></i>
                </div>
                <div>
                    <h3 id="checkout-modal-title" class="text-sm sm:text-base font-black text-zinc-900 uppercase tracking-tight">
                        Commander en ligne
                    </h3>
                    <p class="text-[11px] text-zinc-400 font-medium">Paiement en espèces à la livraison partout au Maroc</p>
                </div>
            </div>
            
            <button type="button"
                    id="checkout-modal-close-btn"
                    aria-label="Fermer"
                    class="btn-circle-action w-8 h-8 bg-white hover:bg-zinc-200 text-zinc-600 flex items-center justify-center cursor-pointer shadow-2xs">
                <i class="uil uil-multiply text-base"></i>
            </button>
        </div>

        <!-- Form View State -->
        <div id="checkout-form-view" class="p-5 sm:p-6 max-h-[80vh] overflow-y-auto no-scrollbar">
            
            <form id="online-checkout-form" class="space-y-5" onsubmit="return false;">
                <!-- Customer Details Card -->
                <div class="space-y-3.5">
                    <div class="text-xs font-black uppercase tracking-wider text-zinc-900 pb-1.5 border-b border-zinc-100 text-left">
                        1. Coordonnées de livraison
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Full Name -->
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-700 mb-1">
                                Nom complet <span class="text-pink-600 font-black">*</span>
                            </label>
                            <input type="text"
                                   id="checkout-customer-name"
                                   name="customer_name"
                                   required
                                   placeholder="ex: Sara Bennani"
                                   class="w-full !h-11 px-4 text-xs font-semibold rounded-xl bg-zinc-50 border border-zinc-200 hover:border-zinc-300 focus:bg-white focus:border-zinc-900 focus:ring-4 focus:ring-zinc-900/5 outline-none transition-all">
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-700 mb-1">
                                Numéro de téléphone <span class="text-pink-600 font-black">*</span>
                            </label>
                            <input type="tel"
                                   id="checkout-customer-phone"
                                   name="customer_phone"
                                   required
                                   placeholder="06 XX XX XX XX"
                                   class="w-full !h-11 px-4 text-xs font-semibold rounded-xl bg-zinc-50 border border-zinc-200 hover:border-zinc-300 focus:bg-white focus:border-zinc-900 focus:ring-4 focus:ring-zinc-900/5 outline-none transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- City -->
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-700 mb-1">
                                Ville <span class="text-pink-600 font-black">*</span>
                            </label>
                            <input type="text"
                                   id="checkout-city"
                                   name="city"
                                   required
                                   list="morocco-cities-list"
                                   placeholder="ex: Casablanca, Rabat..."
                                   class="w-full !h-11 px-4 text-xs font-semibold rounded-xl bg-zinc-50 border border-zinc-200 hover:border-zinc-300 focus:bg-white focus:border-zinc-900 focus:ring-4 focus:ring-zinc-900/5 outline-none transition-all">
                            <datalist id="morocco-cities-list">
                                <option value="Casablanca">
                                <option value="Rabat">
                                <option value="Marrakech">
                                <option value="Tanger">
                                <option value="Fès">
                                <option value="Agadir">
                                <option value="Salé">
                                <option value="Meknès">
                                <option value="Kénitra">
                                <option value="Oujda">
                                <option value="Tétouan">
                                <option value="Mohammedia">
                                <option value="El Jadida">
                                <option value="Nador">
                                <option value="Safi">
                                <option value="Béni Mellal">
                                <option value="Khouribga">
                                <option value="Témara">
                                <option value="Settat">
                                <option value="Laâyoune">
                            </datalist>
                        </div>

                        <!-- Email (Optional) -->
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-700 mb-1">
                                Adresse e-mail <span class="text-zinc-400 font-normal text-[10px]">(facultatif)</span>
                            </label>
                            <input type="email"
                                   id="checkout-customer-email"
                                   name="customer_email"
                                   placeholder="pour le reçu de commande"
                                   class="w-full !h-11 px-4 text-xs font-semibold rounded-xl bg-zinc-50 border border-zinc-200 hover:border-zinc-300 focus:bg-white focus:border-zinc-900 focus:ring-4 focus:ring-zinc-900/5 outline-none transition-all">
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-700 mb-1">
                            Adresse de livraison exacte <span class="text-pink-600 font-black">*</span>
                        </label>
                        <textarea id="checkout-shipping-address"
                                  name="shipping_address"
                                  required
                                  rows="2"
                                  placeholder="Quartier, Boulevard/Rue, Immeuble, N° Appartement..."
                                  class="w-full px-4 py-2.5 text-xs font-semibold rounded-xl bg-zinc-50 border border-zinc-200 hover:border-zinc-300 focus:bg-white focus:border-zinc-900 focus:ring-4 focus:ring-zinc-900/5 outline-none transition-all resize-none"></textarea>
                    </div>

                    <!-- Delivery Notes (Optional) -->
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-zinc-700 mb-1">
                            Instructions spécifiques <span class="text-zinc-400 font-normal text-[10px]">(facultatif)</span>
                        </label>
                        <input type="text"
                               id="checkout-notes"
                               name="notes"
                               placeholder="ex: Appeler avant la livraison, créneau après 16h..."
                               class="w-full !h-10 px-4 text-xs font-medium rounded-xl bg-zinc-50 border border-zinc-200 hover:border-zinc-300 focus:bg-white focus:border-zinc-900 focus:ring-4 focus:ring-zinc-900/5 outline-none transition-all">
                    </div>
                </div>

                <!-- Order Recap Box -->
                <div class="space-y-3 pt-2">
                    <div class="text-xs font-black uppercase tracking-wider text-zinc-900 pb-1.5 border-b border-zinc-100 text-left">
                        2. Récapitulatif de votre commande
                    </div>

                    <div class="bg-[#f8f9fa] border border-zinc-200 rounded-2xl p-4 space-y-3">
                        <!-- Items Mini List -->
                        <div id="checkout-items-preview" class="divide-y divide-zinc-200/60 max-h-36 overflow-y-auto no-scrollbar pr-1">
                            <!-- Injected by JavaScript -->
                        </div>

                        <!-- Calculation breakdown -->
                        <div class="border-t border-zinc-200 pt-3 space-y-1.5 text-xs">
                            <div class="flex justify-between text-zinc-600">
                                <span>Sous-total articles</span>
                                <span id="checkout-recap-subtotal" class="font-bold text-zinc-900">0 DH</span>
                            </div>
                            <div id="checkout-recap-discount-row" class="hidden flex justify-between text-emerald-600 font-bold">
                                <span id="checkout-recap-discount-label">Remise code promo</span>
                                <span id="checkout-recap-discount-amount">-0 DH</span>
                            </div>
                            <div class="flex justify-between text-zinc-600">
                                <span>Frais de livraison (Maroc)</span>
                                <span class="font-bold text-zinc-900">35 DH</span>
                            </div>
                            <div class="flex justify-between items-baseline text-sm font-black text-zinc-900 pt-2 border-t border-zinc-200">
                                <span>Total net à payer à la livraison</span>
                                <span id="checkout-recap-total" class="text-base sm:text-lg text-pink-600 font-black">0 DH</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trust Guarantee Badges (Neutral Light Grey) -->
                <div class="grid grid-cols-3 gap-2 py-1 text-center">
                    <div class="p-2.5 rounded-xl bg-zinc-100/70 border border-zinc-200/80 text-zinc-700">
                        <i class="uil uil-money-bill text-base block mb-0.5 text-zinc-700"></i>
                        <span class="text-[10px] font-bold block leading-tight">Paiement à la livraison</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-zinc-100/70 border border-zinc-200/80 text-zinc-700">
                        <i class="uil uil-truck text-base block mb-0.5 text-zinc-700"></i>
                        <span class="text-[10px] font-bold block leading-tight">Expédié sous 24-48h</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-zinc-100/70 border border-zinc-200/80 text-zinc-700">
                        <i class="uil uil-gift text-base block mb-0.5 text-zinc-700"></i>
                        <span class="text-[10px] font-bold block leading-tight">Échantillons offerts</span>
                    </div>
                </div>

                <!-- Error Message Banner (if any) -->
                <div id="checkout-error-banner" class="hidden p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold">
                    <div class="flex items-center gap-2">
                        <i class="uil uil-exclamation-triangle text-base text-rose-600"></i>
                        <span id="checkout-error-msg">Veuillez remplir tous les champs obligatoires.</span>
                    </div>
                </div>

                <!-- Action Button (Clean Solid Black, No Pink Glow, No Inner Icon) -->
                <div class="pt-2">
                    <button type="button"
                            id="checkout-submit-btn"
                            class="w-full py-4 px-6 rounded-full bg-zinc-900 hover:bg-black text-white text-xs sm:text-sm font-black uppercase tracking-wider flex items-center justify-center gap-2 shadow-md hover:shadow-lg active:scale-[0.99] transition-all cursor-pointer">
                        <span id="checkout-btn-text">Confirmer la commande (Paiement à la livraison)</span>
                        <div id="checkout-btn-spinner" class="hidden w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                    </button>
                    
                    <p class="text-[10px] text-zinc-400 text-center font-medium mt-2">
                        En confirmant votre commande, vous acceptez d'être contacté par notre service de livraison.
                    </p>
                </div>
            </form>

        </div>

        <!-- Success Confirmation View State (Hidden by default) -->
        <div id="checkout-success-view" class="hidden p-6 sm:p-10 text-center space-y-5">
            <div class="w-20 h-20 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-4xl mx-auto shadow-md ring-8 ring-emerald-50/50">
                <i class="uil uil-check"></i>
            </div>

            <div class="space-y-2">
                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-black uppercase tracking-wider">
                    Commande validée avec succès
                </span>
                <h3 class="text-xl sm:text-2xl font-black text-zinc-900">
                    Merci pour votre commande !
                </h3>
                <p class="text-xs sm:text-sm text-zinc-600 max-w-md mx-auto">
                    Votre numéro de commande est <strong id="success-order-number" class="text-pink-600 font-extrabold">#CMD-0000</strong>. Notre équipe va préparer votre colis avec le plus grand soin.
                </p>
            </div>

            <!-- Summary Details Pill -->
            <div class="p-4 rounded-2xl bg-[#f8f9fa] border border-zinc-200 text-left max-w-md mx-auto space-y-2 text-xs">
                <div class="flex justify-between">
                    <span class="text-zinc-500">Destinataire :</span>
                    <span id="success-customer-name" class="font-bold text-zinc-900">Sara Bennani</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-zinc-500">Téléphone :</span>
                    <span id="success-customer-phone" class="font-bold text-zinc-900">06 00 00 00 00</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-zinc-500">Ville de livraison :</span>
                    <span id="success-city" class="font-bold text-zinc-900">Casablanca</span>
                </div>
                <div class="flex justify-between border-t border-zinc-200 pt-2 font-black text-sm text-zinc-900">
                    <span>Montant total à régler :</span>
                    <span id="success-total" class="text-pink-600">0 DH</span>
                </div>
            </div>

            <div class="p-3 bg-pink-50/60 rounded-xl border border-pink-100 max-w-md mx-auto text-[11px] text-pink-900">
                <i class="uil uil-phone-volume text-sm text-pink-600 mr-1"></i>
                Notre service client vous contactera très rapidement par téléphone pour confirmer l'expédition.
            </div>

            <div class="pt-2">
                <button type="button"
                        id="checkout-success-close-btn"
                        class="btn-card-pill py-3.5 px-8 text-xs font-black uppercase tracking-wider inline-flex items-center gap-2 cursor-pointer shadow-md">
                    <span>Continuer mes achats</span>
                    <i class="uil uil-arrow-right text-sm"></i>
                </button>
            </div>
        </div>

    </div>
</div>
