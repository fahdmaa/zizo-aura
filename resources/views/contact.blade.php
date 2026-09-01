@extends('layouts.app')

@section('title', 'Contactez-nous — zizo aura')

@section('content')
<div class="w-full bg-white py-12 sm:py-20">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-16">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-black tracking-tight leading-tight mb-4">
                Nous sommes à votre écoute
            </h1>
            <p class="text-sm sm:text-base text-zinc-500 font-normal leading-relaxed">
                Une question sur nos formules, votre commande ou nos routines de soin ? Notre équipe zizo aura vous répond avec plaisir sous 24 heures.
            </p>
        </div>

        <!-- Success Alert Flash Message -->
        @if(session('success'))
            <div class="max-w-2xl mx-auto mb-8 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-emerald-800 text-sm font-semibold">
                <i class="ti ti-circle-check text-emerald-500 text-xl shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Validation Errors Alert -->
        @if($errors->any())
            <div class="max-w-2xl mx-auto mb-8 p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-start gap-3 text-rose-800 text-sm font-semibold">
                <i class="ti ti-alert-circle text-rose-500 text-xl shrink-0 mt-0.5"></i>
                <div>
                    <p class="font-bold mb-1">Veuillez corriger les erreurs suivantes :</p>
                    <ul class="list-disc list-inside text-xs space-y-0.5 font-normal">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-start">
            
            <!-- Contact Form Card Container -->
            <div id="contact-form-card" class="lg:col-span-7 bg-[#f8f9fa] rounded-3xl p-6 sm:p-10 border border-zinc-100 shadow-sm relative overflow-hidden transition-all duration-300">
                
                <!-- Success Confirmation State (Theme-styled luxury card) -->
                <div id="contact-success-state" class="{{ session('success') ? '' : 'hidden' }} text-center py-8 sm:py-12 px-2 sm:px-6 animate-fadeIn">
                    <div class="relative w-20 h-20 rounded-3xl bg-pink-50 border border-pink-200 text-pink-600 flex items-center justify-center text-4xl mx-auto mb-5 shadow-xs">
                        <i class="ti ti-mail-check"></i>
                        <span class="absolute -top-1.5 -right-1.5 w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs shadow-sm ring-2 ring-white">
                            <i class="ti ti-check"></i>
                        </span>
                    </div>

                    <div class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-extrabold mb-3">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Message transmis avec succès</span>
                    </div>

                    <h3 class="text-2xl sm:text-3xl font-black text-zinc-900 tracking-tight mb-2">
                        Merci pour votre message !
                    </h3>

                    <p id="contact-success-desc" class="text-xs sm:text-sm text-zinc-500 max-w-md mx-auto leading-relaxed mb-6 font-medium">
                        {{ session('success') ?? 'Votre demande a bien été envoyée à notre équipe zizo aura. Nous vous répondrons avec grand plaisir sous 24h ouvrées.' }}
                    </p>

                    <div class="p-4 rounded-2xl bg-white border border-zinc-200/80 max-w-sm mx-auto mb-8 text-left flex items-start gap-3 shadow-2xs">
                        <div class="w-9 h-9 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="ti ti-clock-hour-4 text-lg"></i>
                        </div>
                        <div class="text-xs">
                            <p class="font-bold text-zinc-900">Délai moyen de réponse</p>
                            <p class="text-zinc-500 font-medium mt-0.5">Moins de 24h (Du Lundi au Samedi, 9h à 19h30)</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        <button type="button" id="contact-new-message-btn" class="btn-card-pill py-3.5 px-6 text-xs uppercase font-extrabold cursor-pointer w-full sm:w-auto">
                            <i class="ti ti-plus text-sm"></i>
                            <span>Envoyer un autre message</span>
                        </button>
                        <a href="{{ route('shop.index') }}" class="btn-pill-secondary py-3.5 px-6 text-xs font-extrabold w-full sm:w-auto text-center">
                            <span>Explorer la boutique</span>
                            <i class="ti ti-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- Contact Form -->
                <form id="store-contact-form" action="{{ route('contact.submit') }}" method="POST" class="space-y-6 {{ session('success') ? 'hidden' : '' }}">
                    @csrf

                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-zinc-900 mb-2">
                            Nom complet *
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               required
                               placeholder="Ex: Sarah Martin"
                               class="input-luxury w-full py-3.5">
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-zinc-900 mb-2">
                            Adresse e-mail *
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               required
                               placeholder="votre-email@exemple.com"
                               class="input-luxury w-full py-3.5">
                    </div>

                    <!-- Custom Themed Subject Dropdown -->
                    <div class="relative" id="contact-subject-wrapper">
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-900 mb-2">
                            Objet du message *
                        </label>
                        
                        <input type="hidden" id="contact-subject-input" name="subject" value="Conseil produit &amp; Routine de soin" required>

                        <!-- Custom Trigger Button -->
                        <button type="button"
                                id="contact-subject-btn"
                                class="w-full px-4 py-3.5 bg-white border-1.5 border-zinc-200 hover:border-pink-300 rounded-2xl text-sm font-semibold text-zinc-900 flex items-center justify-between cursor-pointer focus:outline-none focus:border-pink-500 focus:ring-4 focus:ring-pink-500/12 transition-all shadow-2xs">
                            <span id="contact-subject-label" class="text-zinc-900 font-bold">Conseil produit &amp; Routine de soin</span>
                            <i id="contact-subject-chevron" class="ti ti-chevron-down text-zinc-400 transition-transform duration-200"></i>
                        </button>

                        <!-- Custom Floating Dropdown Menu Panel -->
                        <div id="contact-subject-panel"
                             class="absolute top-full left-0 right-0 mt-2 bg-white/95 backdrop-blur-md rounded-2xl shadow-[0_15px_35px_rgba(0,0,0,0.12)] border border-zinc-100 p-2 z-50 transition-all duration-200 hidden">
                            <div class="space-y-1">
                                <button type="button"
                                        class="contact-subject-option w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-bold bg-pink-50 text-pink-600 transition-all cursor-pointer"
                                        data-value="Conseil produit &amp; Routine de soin">
                                    <span>Conseil produit &amp; Routine de soin</span>
                                    <i class="ti ti-check text-xs subject-check"></i>
                                </button>
                                <button type="button"
                                        class="contact-subject-option w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-zinc-700 hover:bg-zinc-50 hover:text-black transition-all cursor-pointer"
                                        data-value="Suivi de commande &amp; Livraison">
                                    <span>Suivi de commande &amp; Livraison</span>
                                    <i class="ti ti-check text-xs subject-check hidden"></i>
                                </button>
                                <button type="button"
                                        class="contact-subject-option w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-zinc-700 hover:bg-zinc-50 hover:text-black transition-all cursor-pointer"
                                        data-value="Retours &amp; Remboursements">
                                    <span>Retours &amp; Remboursements</span>
                                    <i class="ti ti-check text-xs subject-check hidden"></i>
                                </button>
                                <button type="button"
                                        class="contact-subject-option w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-zinc-700 hover:bg-zinc-50 hover:text-black transition-all cursor-pointer"
                                        data-value="Partenariat &amp; Presse">
                                    <span>Partenariat &amp; Presse</span>
                                    <i class="ti ti-check text-xs subject-check hidden"></i>
                                </button>
                                <button type="button"
                                        class="contact-subject-option w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-zinc-700 hover:bg-zinc-50 hover:text-black transition-all cursor-pointer"
                                        data-value="Autre demande">
                                    <span>Autre demande</span>
                                    <i class="ti ti-check text-xs subject-check hidden"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Message Field -->
                    <div>
                        <label for="message" class="block text-xs font-bold uppercase tracking-wider text-zinc-900 mb-2">
                            Votre message *
                        </label>
                        <textarea id="message"
                                  name="message"
                                  rows="5"
                                  required
                                  placeholder="Dites-nous comment nous pouvons vous aider..."
                                  class="textarea-luxury w-full py-3.5"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="contact-submit-btn" class="btn-card-pill w-full py-4 text-sm uppercase tracking-wider font-bold cursor-pointer">
                        <i class="ti ti-send text-base"></i>
                        <span>Envoyer le message</span>
                    </button>
                </form>
            </div>

            <!-- Contact Information Cards with Unified Luxury Style -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Email Card -->
                <div class="p-6 bg-white rounded-2xl border border-zinc-100 shadow-sm flex items-start gap-4 transition-all duration-300 hover:-translate-y-1 hover:scale-[1.02] hover:shadow-[0_12px_28px_rgba(255,27,122,0.12)] hover:border-pink-200 group">
                    <div class="w-12 h-12 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center shrink-0 group-hover:bg-pink-100 group-hover:scale-110 transition-all">
                        <i class="ti ti-mail text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-zinc-900 mb-1 group-hover:text-pink-600 transition-colors">E-mail</h3>
                        <p class="text-xs text-zinc-500 mb-2">Notre équipe vous répond sous 24h ouvrées.</p>
                        <a href="mailto:contact@zizoaura.com" class="text-sm font-bold text-pink-600 hover:underline">
                            contact@zizoaura.com
                        </a>
                    </div>
                </div>

                <!-- Phone & WhatsApp Card -->
                <div class="p-6 bg-white rounded-2xl border border-zinc-100 shadow-sm flex items-start gap-4 transition-all duration-300 hover:-translate-y-1 hover:scale-[1.02] hover:shadow-[0_12px_28px_rgba(255,27,122,0.12)] hover:border-pink-200 group">
                    <div class="w-12 h-12 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center shrink-0 group-hover:bg-pink-100 group-hover:scale-110 transition-all">
                        <i class="ti ti-phone-call text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-zinc-900 mb-1 group-hover:text-pink-600 transition-colors">Téléphone &amp; WhatsApp</h3>
                        <p class="text-xs text-zinc-500 mb-2">Du Lundi au Samedi : 9h00 – 19h30 (Maroc)</p>
                        <a href="tel:+212522000000" class="text-sm font-bold text-zinc-900 hover:text-pink-600 transition-colors">
                            +212 (0) 5 22 40 50 60
                        </a>
                    </div>
                </div>

                <!-- Free Delivery Card -->
                <div class="p-6 bg-white rounded-2xl border border-zinc-100 shadow-sm flex items-start gap-4 transition-all duration-300 hover:-translate-y-1 hover:scale-[1.02] hover:shadow-[0_12px_28px_rgba(255,27,122,0.12)] hover:border-pink-200 group">
                    <div class="w-12 h-12 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center shrink-0 group-hover:bg-pink-100 group-hover:scale-110 transition-all">
                        <i class="ti ti-truck-delivery text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-zinc-900 mb-1 group-hover:text-pink-600 transition-colors">Livraison offerte dès 550 DH</h3>
                        <p class="text-xs text-zinc-500 leading-relaxed">Expédition express suivie sous 24h avec échantillons offerts dans chaque commande.</p>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
