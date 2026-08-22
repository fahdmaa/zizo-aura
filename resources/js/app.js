import '../css/app.css';

document.addEventListener('DOMContentLoaded', () => {
    // =========================================================================
    // 1. Toast Notification System
    // =========================================================================
    const toast = document.getElementById('app-toast');
    const toastMessage = document.getElementById('toast-message');

    const showToast = (message) => {
        if (!toast || !toastMessage) return;
        toastMessage.textContent = message;
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
        }, 2600);
    };

    // =========================================================================
    // 2. Persistent Cart Management & Slide-over Drawer
    // =========================================================================
    const CART_STORAGE_KEY = 'zizo_aura_cart_v1';
    const FREE_SHIPPING_THRESHOLD = 550; // In Moroccan Dirhams (DH)

    const getCart = () => {
        try {
            return JSON.parse(localStorage.getItem(CART_STORAGE_KEY)) || [];
        } catch {
            return [];
        }
    };

    const saveCart = (cart) => {
        try {
            localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
        } catch (e) {
            console.error('Failed to save cart to localStorage', e);
        }
        renderCartUI();
    };

    const cartBadge = document.getElementById('cart-count-badge');
    const cartDrawerCount = document.getElementById('cart-drawer-count');
    const cartDrawerBackdrop = document.getElementById('cart-drawer-backdrop');
    const cartDrawerPanel = document.getElementById('cart-drawer-panel');
    const cartDrawerCloseBtn = document.getElementById('cart-drawer-close-btn');
    const cartDrawerItems = document.getElementById('cart-drawer-items');
    const cartDrawerSubtotal = document.getElementById('cart-drawer-subtotal');
    const cartDrawerShipping = document.getElementById('cart-drawer-shipping');
    const cartDrawerTotal = document.getElementById('cart-drawer-total');
    const shippingProgressBar = document.getElementById('shipping-progress-bar');
    const shippingStatusLabel = document.getElementById('shipping-status-label');
    const shippingRemainingAmount = document.getElementById('shipping-remaining-amount');
    const cartWhatsappBtn = document.getElementById('cart-whatsapp-btn');
    const navbarCartBtn = document.getElementById('navbar-cart-btn');
    const cartContinueShoppingBtn = document.getElementById('cart-continue-shopping-btn');

    const openCartDrawer = () => {
        if (!cartDrawerBackdrop || !cartDrawerPanel) return;
        renderCartUI();
        cartDrawerBackdrop.classList.remove('opacity-0', 'pointer-events-none');
        cartDrawerBackdrop.classList.add('opacity-100', 'pointer-events-auto');
        cartDrawerPanel.classList.remove('translate-x-full');
        cartDrawerPanel.classList.add('translate-x-0');
        document.body.classList.add('overflow-hidden');
    };

    const closeCartDrawer = () => {
        if (!cartDrawerBackdrop || !cartDrawerPanel) return;
        cartDrawerBackdrop.classList.remove('opacity-100', 'pointer-events-auto');
        cartDrawerBackdrop.classList.add('opacity-0', 'pointer-events-none');
        cartDrawerPanel.classList.remove('translate-x-0');
        cartDrawerPanel.classList.add('translate-x-full');
        document.body.classList.remove('overflow-hidden');
    };

    const renderCartUI = () => {
        const cart = getCart();
        const totalItemsCount = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
        const subtotal = cart.reduce((sum, item) => sum + ((parseFloat(item.price) || 0) * (item.quantity || 1)), 0);

        // Update count badges
        if (cartBadge) {
            cartBadge.textContent = totalItemsCount;
            cartBadge.classList.add('scale-125');
            setTimeout(() => cartBadge.classList.remove('scale-125'), 250);
        }
        if (cartDrawerCount) {
            cartDrawerCount.textContent = totalItemsCount;
        }

        // Update shipping progress bar
        if (shippingProgressBar && shippingStatusLabel && shippingRemainingAmount) {
            if (subtotal >= FREE_SHIPPING_THRESHOLD) {
                shippingProgressBar.style.width = '100%';
                shippingProgressBar.classList.remove('from-pink-500', 'to-rose-500');
                shippingProgressBar.classList.add('bg-emerald-500');
                shippingStatusLabel.textContent = '🎉 Livraison offerte activée !';
                shippingRemainingAmount.textContent = 'Offerte 🇲🇦';
                shippingRemainingAmount.className = 'text-emerald-600 font-extrabold';
            } else {
                const percent = Math.min(100, Math.round((subtotal / FREE_SHIPPING_THRESHOLD) * 100));
                const remaining = FREE_SHIPPING_THRESHOLD - subtotal;
                shippingProgressBar.style.width = `${percent}%`;
                shippingProgressBar.classList.add('from-pink-500', 'to-rose-500');
                shippingProgressBar.classList.remove('bg-emerald-500');
                shippingStatusLabel.textContent = 'Livraison offerte dès 550 DH';
                shippingRemainingAmount.textContent = `Plus que ${remaining} DH`;
                shippingRemainingAmount.className = 'text-pink-600 font-extrabold';
            }
        }

        // Update Totals
        if (cartDrawerSubtotal) cartDrawerSubtotal.textContent = `${subtotal} DH`;
        if (cartDrawerShipping) {
            if (subtotal === 0) {
                cartDrawerShipping.textContent = 'Calculée au paiement';
                cartDrawerShipping.className = 'font-bold text-zinc-500';
            } else if (subtotal >= FREE_SHIPPING_THRESHOLD) {
                cartDrawerShipping.textContent = 'GRATUITE (0 DH)';
                cartDrawerShipping.className = 'font-bold text-emerald-600';
            } else {
                cartDrawerShipping.textContent = '35 DH (Standard Maroc)';
                cartDrawerShipping.className = 'font-bold text-zinc-700';
            }
        }
        if (cartDrawerTotal) {
            const finalTotal = subtotal === 0 ? 0 : (subtotal >= FREE_SHIPPING_THRESHOLD ? subtotal : subtotal + 35);
            cartDrawerTotal.textContent = `${finalTotal} DH`;
        }

        // Update WhatsApp message link
        if (cartWhatsappBtn) {
            if (cart.length > 0) {
                let msg = `Bonjour *zizo aura*, je souhaite commander les articles suivants :\n\n`;
                cart.forEach((item, i) => {
                    const variant = [item.flavor, item.size].filter(Boolean).join(' • ');
                    msg += `${i + 1}. *${item.name}* ${variant ? '(' + variant + ')' : ''} x${item.quantity} = ${item.price * item.quantity} DH\n`;
                });
                const shippingCost = subtotal >= FREE_SHIPPING_THRESHOLD ? 'Gratuite (Offerte)' : '35 DH';
                const finalTotal = subtotal >= FREE_SHIPPING_THRESHOLD ? subtotal : subtotal + 35;
                msg += `\n*Sous-total :* ${subtotal} DH\n*Livraison :* ${shippingCost}\n*Total à payer :* ${finalTotal} DH\n\nMerci de me confirmer la commande !`;
                cartWhatsappBtn.href = `https://wa.me/212600000000?text=${encodeURIComponent(msg)}`;
                cartWhatsappBtn.classList.remove('opacity-50', 'pointer-events-none');
            } else {
                cartWhatsappBtn.href = '#';
                cartWhatsappBtn.classList.add('opacity-50', 'pointer-events-none');
            }
        }

        // Render Item List
        if (!cartDrawerItems) return;

        if (cart.length === 0) {
            cartDrawerItems.innerHTML = `
                <div class="h-full flex flex-col items-center justify-center text-center py-12 px-4">
                    <div class="w-16 h-16 rounded-full bg-pink-50 text-pink-600 flex items-center justify-center text-3xl mb-4 shadow-xs">
                        <i class="ti ti-shopping-bag"></i>
                    </div>
                    <h4 class="text-base font-extrabold text-zinc-900 mb-1">Votre panier est vide</h4>
                    <p class="text-xs text-zinc-400 max-w-xs mb-6 font-medium">
                        Découvrez nos formules solaires et coffrets exclusifs pour composer votre rituel beauté.
                    </p>
                    <a href="/boutique" id="empty-cart-shop-btn" class="btn-card-pill py-3 px-6 text-xs uppercase font-extrabold">
                        <span>Explorer la boutique</span>
                        <i class="ti ti-arrow-right text-sm"></i>
                    </a>
                </div>
            `;
            const emptyShopBtn = document.getElementById('empty-cart-shop-btn');
            if (emptyShopBtn) {
                emptyShopBtn.addEventListener('click', closeCartDrawer);
            }
        } else {
            let html = '';
            cart.forEach((item, index) => {
                const variantInfo = [item.flavor, item.size].filter(Boolean).join(' • ');
                html += `
                    <div class="py-3.5 flex items-center gap-3.5 group">
                        <div class="w-16 h-16 rounded-xl bg-[#f8f9fa] border border-zinc-100 p-1 flex items-center justify-center shrink-0 overflow-hidden">
                            <img src="${item.image || '/images/sdj_bum_bum_set.jpg'}" alt="${item.name}" class="w-full h-full object-contain" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-xs font-bold text-zinc-900 truncate mb-0.5 leading-snug">
                                ${item.name}
                            </h4>
                            ${variantInfo ? `<p class="text-[10px] text-zinc-400 font-semibold truncate mb-1">${variantInfo}</p>` : ''}
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-xs font-black text-pink-600">${item.price} DH</span>
                                
                                <!-- Quantity Buttons -->
                                <div class="flex items-center gap-1.5 bg-zinc-100 border border-zinc-200 rounded-full px-1 py-0.5">
                                    <button type="button" data-cart-action="dec" data-index="${index}" class="w-5 h-5 rounded-full bg-white hover:bg-zinc-200 text-zinc-800 flex items-center justify-center text-[10px] font-bold cursor-pointer transition-colors shadow-2xs">
                                        <i class="ti ti-minus text-[9px]"></i>
                                    </button>
                                    <span class="w-5 text-center text-xs font-black text-zinc-900">${item.quantity}</span>
                                    <button type="button" data-cart-action="inc" data-index="${index}" class="w-5 h-5 rounded-full bg-white hover:bg-zinc-200 text-zinc-800 flex items-center justify-center text-[10px] font-bold cursor-pointer transition-colors shadow-2xs">
                                        <i class="ti ti-plus text-[9px]"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" data-cart-action="remove" data-index="${index}" class="p-1.5 text-zinc-300 hover:text-rose-500 rounded-lg transition-colors cursor-pointer" aria-label="Supprimer du panier">
                            <i class="ti ti-trash text-base"></i>
                        </button>
                    </div>
                `;
            });
            cartDrawerItems.innerHTML = html;
        }
    };

    // Global Cart Item Controls (Delegate Event Listener)
    if (cartDrawerItems) {
        cartDrawerItems.addEventListener('click', (e) => {
            const btn = e.target.closest('button[data-cart-action]');
            if (!btn) return;
            const action = btn.dataset.cartAction;
            const index = parseInt(btn.dataset.index, 10);
            const cart = getCart();

            if (action === 'inc' && cart[index]) {
                cart[index].quantity = (cart[index].quantity || 1) + 1;
                saveCart(cart);
            } else if (action === 'dec' && cart[index]) {
                if (cart[index].quantity > 1) {
                    cart[index].quantity -= 1;
                    saveCart(cart);
                } else {
                    cart.splice(index, 1);
                    saveCart(cart);
                }
            } else if (action === 'remove' && cart[index]) {
                const removedName = cart[index].name;
                cart.splice(index, 1);
                saveCart(cart);
                showToast(`${removedName} retiré du panier`);
            }
        });
    }

    // Connect Navbar Cart Button to open drawer
    if (navbarCartBtn) {
        navbarCartBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openCartDrawer();
        });
    }

    if (cartDrawerCloseBtn) {
        cartDrawerCloseBtn.addEventListener('click', closeCartDrawer);
    }

    if (cartDrawerBackdrop) {
        cartDrawerBackdrop.addEventListener('click', (e) => {
            if (e.target === cartDrawerBackdrop) closeCartDrawer();
        });
    }

    if (cartContinueShoppingBtn) {
        cartContinueShoppingBtn.addEventListener('click', (e) => {
            closeCartDrawer();
        });
    }

    // Helper: Add Item to Cart Array
    const addItemToCart = ({ name, price, image, slug, flavor, size, quantity = 1 }) => {
        const cart = getCart();
        const numericPrice = parseFloat(price) || 350;
        const cleanQuantity = parseInt(quantity, 10) || 1;

        // Check for existing item with identical variant
        const existingIdx = cart.findIndex(it => 
            it.name === name && 
            (it.flavor || '') === (flavor || '') && 
            (it.size || '') === (size || '')
        );

        if (existingIdx > -1) {
            cart[existingIdx].quantity += cleanQuantity;
        } else {
            cart.push({
                name,
                price: numericPrice,
                image: image || '/images/sdj_bum_bum_set.jpg',
                slug: slug || '',
                flavor: flavor || '',
                size: size || '',
                quantity: cleanQuantity
            });
        }

        saveCart(cart);
        showToast(`${cleanQuantity}x ${name} ajouté${cleanQuantity > 1 ? 's' : ''} au panier !`);
    };

    // Generic Add-to-Cart Buttons across Catalog, Marquee, Home
    document.querySelectorAll('.btn-card-pill').forEach(button => {
        button.addEventListener('click', (e) => {
            if (button.tagName.toLowerCase() === 'a') return; // Don't intercept anchor links
            if (button.id === 'product-add-cart-btn') return; // Handled by dedicated product view listener
            if (button.id === 'cart-whatsapp-btn') return;
            e.preventDefault();

            const name = button.dataset.productName || 'Produit';
            const price = parseFloat(button.dataset.productPrice) || 350;
            const image = button.dataset.productImage || '';
            const slug = button.dataset.productSlug || '';

            addItemToCart({ name, price, image, slug, quantity: 1 });

            // Button Feedback Animation
            const origHtml = button.innerHTML;
            button.style.backgroundColor = '#10b981';
            button.innerHTML = `<i class="ti ti-check text-base"></i><span>Ajouté ✓</span>`;
            button.disabled = true;

            setTimeout(() => {
                button.style.backgroundColor = '';
                button.innerHTML = origHtml;
                button.disabled = false;
            }, 1400);
        });
    });

    // =========================================================================
    // 3. Product Detail Page: Quantity, Fragrance & Size Selectors
    // =========================================================================
    const qtyMinusBtn = document.getElementById('qty-minus-btn');
    const qtyPlusBtn = document.getElementById('qty-plus-btn');
    const qtyInput = document.getElementById('product-quantity-input');
    const qtyDisplay = document.getElementById('product-quantity-display');
    const productAddCartBtn = document.getElementById('product-add-cart-btn');
    const btnCartText = document.getElementById('btn-cart-text');
    const selectedFlavorLabel = document.getElementById('selected-flavor-label');
    const selectedSizeLabel = document.getElementById('selected-size-label');

    let currentFlavor = selectedFlavorLabel ? selectedFlavorLabel.textContent.trim() : '';
    let currentSize = selectedSizeLabel ? selectedSizeLabel.textContent.trim() : '';

    // Fragrance Swatches Switcher on Product Page
    document.querySelectorAll('.flavor-swatch-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            document.querySelectorAll('.flavor-swatch-btn').forEach(b => {
                b.classList.remove('border-black', 'bg-zinc-900', 'text-white', 'ring-2', 'ring-black/10');
                b.classList.add('border-zinc-200', 'bg-white', 'text-zinc-700');
            });
            btn.classList.remove('border-zinc-200', 'bg-white', 'text-zinc-700');
            btn.classList.add('border-black', 'bg-zinc-900', 'text-white', 'ring-2', 'ring-black/10');

            const name = btn.dataset.flavorName || '';
            currentFlavor = name;
            if (selectedFlavorLabel) {
                selectedFlavorLabel.textContent = name;
                selectedFlavorLabel.classList.add('text-pink-600');
            }
        });
    });

    // Format / Size Selector on Product Page
    document.querySelectorAll('.size-option-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            document.querySelectorAll('.size-option-btn').forEach(b => {
                b.classList.remove('border-black', 'bg-zinc-50', 'text-zinc-900', 'ring-2', 'ring-black', 'shadow-xs');
                b.classList.add('border-zinc-200', 'text-zinc-600', 'bg-white');
            });
            btn.classList.remove('border-zinc-200', 'text-zinc-600', 'bg-white');
            btn.classList.add('border-black', 'bg-zinc-50', 'text-zinc-900', 'ring-2', 'ring-black', 'shadow-xs');

            const size = btn.dataset.sizeName || '';
            currentSize = size;
            if (selectedSizeLabel) {
                selectedSizeLabel.textContent = size;
            }
        });
    });

    // Quantity Selector Controller
    if (qtyMinusBtn && qtyPlusBtn && productAddCartBtn) {
        const unitPrice = parseFloat(productAddCartBtn.dataset.unitPrice) || 350;

        const updateQtyUI = (qty) => {
            if (qtyInput) qtyInput.value = qty;
            if (qtyDisplay) qtyDisplay.textContent = qty;
            qtyMinusBtn.disabled = (qty <= 1);
            const totalPrice = Math.round(unitPrice * qty);
            if (btnCartText) {
                btnCartText.textContent = `Ajouter au panier • ${totalPrice} DH`;
            }
        };

        qtyMinusBtn.addEventListener('click', () => {
            let current = parseInt(qtyInput ? qtyInput.value : qtyDisplay.textContent, 10) || 1;
            if (current > 1) updateQtyUI(current - 1);
        });

        qtyPlusBtn.addEventListener('click', () => {
            let current = parseInt(qtyInput ? qtyInput.value : qtyDisplay.textContent, 10) || 1;
            if (current < 20) updateQtyUI(current + 1);
        });

        productAddCartBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const qty = parseInt(qtyInput ? qtyInput.value : qtyDisplay.textContent, 10) || 1;
            const name = productAddCartBtn.dataset.productName || 'Produit';
            const price = parseFloat(productAddCartBtn.dataset.unitPrice) || 350;
            const image = productAddCartBtn.dataset.productImage || '';
            const slug = productAddCartBtn.dataset.productSlug || '';

            addItemToCart({
                name,
                price,
                image,
                slug,
                flavor: currentFlavor,
                size: currentSize,
                quantity: qty
            });

            // Button Feedback
            const origContent = productAddCartBtn.innerHTML;
            productAddCartBtn.style.backgroundColor = '#10b981';
            productAddCartBtn.innerHTML = `<i class="ti ti-check text-base sm:text-lg"></i><span>${qty} Ajouté${qty > 1 ? 's' : ''} ✓</span>`;
            productAddCartBtn.disabled = true;

            setTimeout(() => {
                productAddCartBtn.style.backgroundColor = '';
                productAddCartBtn.innerHTML = origContent;
                productAddCartBtn.disabled = false;
                openCartDrawer();
            }, 1000);
        });
    }

    // =========================================================================
    // 4. Mobile Navigation Drawer & Accordions
    // =========================================================================
    const mobileMenuOpenBtn = document.getElementById('mobile-menu-open-btn');
    const mobileMenuCloseBtn = document.getElementById('mobile-menu-close-btn');
    const mobileMenuBackdrop = document.getElementById('mobile-menu-backdrop');
    const mobileMenuDrawer = document.getElementById('mobile-menu-drawer');
    const mobileBoutiqueToggle = document.getElementById('mobile-boutique-toggle');
    const mobileBoutiqueSublinks = document.getElementById('mobile-boutique-sublinks');
    const mobileBoutiqueChevron = document.getElementById('mobile-boutique-chevron');

    const openMobileMenu = () => {
        if (!mobileMenuBackdrop || !mobileMenuDrawer) return;
        mobileMenuBackdrop.classList.remove('opacity-0', 'pointer-events-none');
        mobileMenuBackdrop.classList.add('opacity-100', 'pointer-events-auto');
        mobileMenuDrawer.classList.remove('-translate-x-full');
        mobileMenuDrawer.classList.add('translate-x-0');
        document.body.classList.add('overflow-hidden');
    };

    const closeMobileMenu = () => {
        if (!mobileMenuBackdrop || !mobileMenuDrawer) return;
        mobileMenuBackdrop.classList.remove('opacity-100', 'pointer-events-auto');
        mobileMenuBackdrop.classList.add('opacity-0', 'pointer-events-none');
        mobileMenuDrawer.classList.remove('translate-x-0');
        mobileMenuDrawer.classList.add('-translate-x-full');
        document.body.classList.remove('overflow-hidden');
    };

    if (mobileMenuOpenBtn) mobileMenuOpenBtn.addEventListener('click', openMobileMenu);
    if (mobileMenuCloseBtn) mobileMenuCloseBtn.addEventListener('click', closeMobileMenu);
    if (mobileMenuBackdrop) {
        mobileMenuBackdrop.addEventListener('click', (e) => {
            if (e.target === mobileMenuBackdrop) closeMobileMenu();
        });
    }

    if (mobileBoutiqueToggle && mobileBoutiqueSublinks && mobileBoutiqueChevron) {
        mobileBoutiqueToggle.addEventListener('click', (e) => {
            e.preventDefault();
            const isHidden = mobileBoutiqueSublinks.classList.contains('hidden');
            if (isHidden) {
                mobileBoutiqueSublinks.classList.remove('hidden');
                mobileBoutiqueChevron.classList.add('rotate-180');
            } else {
                mobileBoutiqueSublinks.classList.add('hidden');
                mobileBoutiqueChevron.classList.remove('rotate-180');
            }
        });
    }

    // =========================================================================
    // 5. Desktop & Touch Dropdowns (Boutique & Catalog Sort)
    // =========================================================================
    // Desktop Boutique Dropdown
    const navBoutiqueWrapper = document.getElementById('nav-boutique-wrapper');
    const navBoutiqueLink = document.getElementById('nav-boutique-link');
    const navBoutiqueDropdown = document.getElementById('nav-boutique-dropdown');

    if (navBoutiqueWrapper && navBoutiqueLink && navBoutiqueDropdown) {
        navBoutiqueLink.addEventListener('click', (e) => {
            // If on touch device, toggle visibility
            if (window.innerWidth >= 768 && ('ontouchstart' in window || navigator.maxTouchPoints > 0)) {
                e.preventDefault();
                const isVisible = navBoutiqueDropdown.classList.contains('opacity-100');
                if (isVisible) {
                    navBoutiqueDropdown.classList.remove('opacity-100', 'visible', 'translate-y-0');
                    navBoutiqueDropdown.classList.add('opacity-0', 'invisible', 'translate-y-1');
                } else {
                    navBoutiqueDropdown.classList.remove('opacity-0', 'invisible', 'translate-y-1');
                    navBoutiqueDropdown.classList.add('opacity-100', 'visible', 'translate-y-0');
                }
            }
        });
    }

    // Catalog Sort Dropdown (shop.index)
    const customSortDropdown = document.getElementById('custom-sort-dropdown');
    const sortDropdownBtn = document.getElementById('sort-dropdown-btn');
    const sortDropdownPanel = document.getElementById('sort-dropdown-panel');
    const sortDropdownChevron = document.getElementById('sort-dropdown-chevron');

    if (customSortDropdown && sortDropdownBtn && sortDropdownPanel) {
        sortDropdownBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const isHidden = sortDropdownPanel.classList.contains('invisible');
            if (isHidden) {
                sortDropdownPanel.classList.remove('opacity-0', 'invisible');
                sortDropdownPanel.classList.add('opacity-100', 'visible');
                if (sortDropdownChevron) sortDropdownChevron.classList.add('rotate-180');
            } else {
                sortDropdownPanel.classList.remove('opacity-100', 'visible');
                sortDropdownPanel.classList.add('opacity-0', 'invisible');
                if (sortDropdownChevron) sortDropdownChevron.classList.remove('rotate-180');
            }
        });

        document.addEventListener('click', (e) => {
            if (!customSortDropdown.contains(e.target)) {
                sortDropdownPanel.classList.remove('opacity-100', 'visible');
                sortDropdownPanel.classList.add('opacity-0', 'invisible');
                if (sortDropdownChevron) sortDropdownChevron.classList.remove('rotate-180');
            }
        });
    }

    // =========================================================================
    // 6. Summer Discount Promo Popup Modal
    // =========================================================================
    const promoBackdrop = document.getElementById('promo-modal-backdrop');
    const promoCard = document.getElementById('promo-modal-card');
    const closePromoBtn = document.getElementById('close-promo-modal');
    const copyPromoBtn = document.getElementById('copy-promo-code');
    const promoCodeText = document.getElementById('promo-code-text');
    const copyText = document.getElementById('copy-text');

    const openPromoModal = () => {
        if (!promoBackdrop || !promoCard) return;
        promoBackdrop.classList.remove('opacity-0', 'pointer-events-none');
        promoBackdrop.classList.add('opacity-100', 'pointer-events-auto');
        promoCard.classList.remove('scale-90');
        promoCard.classList.add('scale-100');
    };

    const closePromoModal = () => {
        if (!promoBackdrop || !promoCard) return;
        promoBackdrop.classList.remove('opacity-100', 'pointer-events-auto');
        promoBackdrop.classList.add('opacity-0', 'pointer-events-none');
        promoCard.classList.remove('scale-100');
        promoCard.classList.add('scale-90');
    };

    if (promoBackdrop) {
        setTimeout(() => {
            openPromoModal();
        }, 600);

        if (closePromoBtn) {
            closePromoBtn.addEventListener('click', closePromoModal);
        }

        promoBackdrop.addEventListener('click', (e) => {
            if (e.target === promoBackdrop) closePromoModal();
        });
    }

    if (copyPromoBtn && promoCodeText) {
        copyPromoBtn.addEventListener('click', () => {
            const code = promoCodeText.textContent.trim();
            navigator.clipboard.writeText(code).then(() => {
                if (copyText) copyText.textContent = 'Copié ✓';
                copyPromoBtn.classList.add('bg-emerald-500', 'text-white', 'border-emerald-500');
                showToast(`Code promo ${code} copié dans le presse-papier !`);

                setTimeout(() => {
                    if (copyText) copyText.textContent = 'Copier';
                    copyPromoBtn.classList.remove('bg-emerald-500', 'text-white', 'border-emerald-500');
                }, 2200);
            });
        });
    }

    // =========================================================================
    // 7. Customer Reviews Carousel Slider Navigation
    // =========================================================================
    const reviewsSlider = document.getElementById('reviews-slider');
    const prevBtn = document.getElementById('review-prev');
    const nextBtn = document.getElementById('review-next');

    if (reviewsSlider && prevBtn && nextBtn) {
        const getScrollStep = () => {
            const firstCard = reviewsSlider.querySelector('.review-slide-card');
            return firstCard ? firstCard.offsetWidth + 24 : 360;
        };

        prevBtn.addEventListener('click', () => {
            reviewsSlider.scrollBy({ left: -getScrollStep(), behavior: 'smooth' });
        });

        nextBtn.addEventListener('click', () => {
            reviewsSlider.scrollBy({ left: getScrollStep(), behavior: 'smooth' });
        });
    }

    // =========================================================================
    // 8. Search Bar & Live Autocomplete Suggestions
    // =========================================================================
    const searchWrapper = document.getElementById('search-wrapper');
    const searchForm = document.getElementById('navbar-search-form');
    const searchPill = document.getElementById('search-pill-container');
    const expandBtn = document.getElementById('search-expand-btn');
    const navbarInput = document.getElementById('navbar-search-input');
    const clearBtn = document.getElementById('navbar-clear-btn');
    const suggestionsBox = document.getElementById('navbar-suggestions-box');
    const resultsList = document.getElementById('navbar-results-list');

    let searchTimer = null;

    const expandSearchBar = () => {
        if (!searchPill || !navbarInput) return;
        searchPill.classList.remove('w-10', 'bg-transparent', 'border-transparent');
        searchPill.classList.add('w-36', 'xs:w-44', 'sm:w-56', 'md:w-60', 'bg-zinc-100', 'border-zinc-200', 'shadow-2xs');
        navbarInput.classList.remove('w-0', 'opacity-0');
        navbarInput.classList.add('w-full', 'opacity-100');
        navbarInput.focus();
    };

    const collapseSearchBar = () => {
        if (!searchPill || !navbarInput) return;
        if (navbarInput.value.trim().length === 0) {
            searchPill.classList.remove('w-36', 'xs:w-44', 'sm:w-56', 'md:w-60', 'bg-zinc-100', 'border-zinc-200', 'shadow-2xs');
            searchPill.classList.add('w-10', 'bg-transparent', 'border-transparent');
            navbarInput.classList.remove('w-full', 'opacity-100');
            navbarInput.classList.add('w-0', 'opacity-0');
            if (clearBtn) clearBtn.classList.add('hidden');
        }
        if (suggestionsBox) suggestionsBox.classList.add('hidden');
    };

    if (expandBtn) {
        expandBtn.addEventListener('click', (e) => {
            const isCollapsed = searchPill.classList.contains('w-10');
            if (isCollapsed) {
                e.preventDefault();
                expandSearchBar();
            } else if (navbarInput && navbarInput.value.trim().length > 0) {
                searchForm.submit();
            } else {
                navbarInput.focus();
            }
        });
    }

    if (navbarInput && navbarInput.value.trim().length > 0) {
        expandSearchBar();
        if (clearBtn) clearBtn.classList.remove('hidden');
    }

    const fetchLiveSuggestions = (query) => {
        if (!query || query.trim().length === 0) {
            if (suggestionsBox) suggestionsBox.classList.add('hidden');
            if (clearBtn) clearBtn.classList.add('hidden');
            return;
        }

        if (clearBtn) clearBtn.classList.remove('hidden');

        fetch(`/api/search?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                if (!suggestionsBox || !resultsList) return;

                if (data.results && data.results.length > 0) {
                    let html = '';
                    data.results.forEach(prod => {
                        html += `
                            <a href="${prod.url}" class="flex items-center justify-between gap-2.5 p-2 hover:bg-pink-50/70 rounded-xl transition-all group">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-9 h-9 rounded-lg bg-zinc-50 p-1 flex items-center justify-center border border-zinc-100 shrink-0">
                                        <img src="${prod.image}" alt="${prod.name}" class="w-full h-full object-contain" />
                                    </div>
                                    <div class="truncate">
                                        <h4 class="text-xs font-bold text-zinc-900 group-hover:text-pink-600 transition-colors truncate">
                                            ${prod.name}
                                        </h4>
                                        <p class="text-[10px] text-zinc-400 font-medium truncate">
                                            ${prod.category}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-xs font-black text-pink-600">${prod.price}</span>
                                </div>
                            </a>
                        `;
                    });

                    html += `
                        <div class="pt-2 mt-1 text-center border-t border-zinc-100">
                            <a href="/boutique?q=${encodeURIComponent(query)}" class="text-[11px] font-bold text-pink-600 hover:text-pink-700 flex items-center justify-center gap-1">
                                <span>Voir tous les résultats (${data.count})</span>
                                <i class="ti ti-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    `;

                    resultsList.innerHTML = html;
                    suggestionsBox.classList.remove('hidden');
                } else {
                    resultsList.innerHTML = `
                        <div class="py-4 text-center text-zinc-400">
                            <p class="text-xs font-medium">Aucun produit trouvé</p>
                        </div>
                    `;
                    suggestionsBox.classList.remove('hidden');
                }
            })
            .catch(() => {
                if (suggestionsBox) suggestionsBox.classList.add('hidden');
            });
    };

    if (navbarInput) {
        navbarInput.addEventListener('input', (e) => {
            clearTimeout(searchTimer);
            const val = e.target.value;
            searchTimer = setTimeout(() => {
                fetchLiveSuggestions(val);
            }, 180);
        });

        navbarInput.addEventListener('focus', () => {
            if (navbarInput.value.trim().length > 0) {
                fetchLiveSuggestions(navbarInput.value);
            }
        });
    }

    if (clearBtn && navbarInput) {
        clearBtn.addEventListener('click', () => {
            navbarInput.value = '';
            clearBtn.classList.add('hidden');
            if (suggestionsBox) suggestionsBox.classList.add('hidden');
            navbarInput.focus();
        });
    }

    document.addEventListener('click', (e) => {
        if (searchWrapper && !searchWrapper.contains(e.target)) {
            collapseSearchBar();
        }
    });

    // =========================================================================
    // 9. Contact Page Subject Dropdown
    // =========================================================================
    const subjectWrapper = document.getElementById('contact-subject-wrapper');
    const subjectBtn = document.getElementById('contact-subject-btn');
    const subjectPanel = document.getElementById('contact-subject-panel');
    const subjectLabel = document.getElementById('contact-subject-label');
    const subjectInput = document.getElementById('contact-subject-input');
    const subjectChevron = document.getElementById('contact-subject-chevron');
    const subjectOptions = document.querySelectorAll('.contact-subject-option');

    if (subjectBtn && subjectPanel && subjectLabel && subjectInput) {
        const toggleSubjectDropdown = () => {
            const isHidden = subjectPanel.classList.contains('hidden');
            if (isHidden) {
                subjectPanel.classList.remove('hidden');
                if (subjectChevron) subjectChevron.classList.add('rotate-180');
            } else {
                subjectPanel.classList.add('hidden');
                if (subjectChevron) subjectChevron.classList.remove('rotate-180');
            }
        };

        const closeSubjectDropdown = () => {
            subjectPanel.classList.add('hidden');
            if (subjectChevron) subjectChevron.classList.remove('rotate-180');
        };

        subjectBtn.addEventListener('click', (e) => {
            e.preventDefault();
            toggleSubjectDropdown();
        });

        subjectOptions.forEach(opt => {
            opt.addEventListener('click', () => {
                const val = opt.dataset.value;
                subjectInput.value = val;
                subjectLabel.textContent = val;

                subjectOptions.forEach(o => {
                    o.classList.remove('bg-pink-50', 'text-pink-600');
                    o.classList.add('text-zinc-700');
                    const check = o.querySelector('.subject-check');
                    if (check) check.classList.add('hidden');
                });

                opt.classList.remove('text-zinc-700');
                opt.classList.add('bg-pink-50', 'text-pink-600');
                const activeCheck = opt.querySelector('.subject-check');
                if (activeCheck) activeCheck.classList.remove('hidden');

                closeSubjectDropdown();
            });
        });

        document.addEventListener('click', (e) => {
            if (subjectWrapper && !subjectWrapper.contains(e.target)) {
                closeSubjectDropdown();
            }
        });
    }

    // =========================================================================
    // 10. Global Keyboard Shortcuts (Escape Key Listener)
    // =========================================================================
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            collapseSearchBar();
            closeMobileMenu();
            closeCartDrawer();
            closePromoModal();
            if (navBoutiqueDropdown) {
                navBoutiqueDropdown.classList.remove('opacity-100', 'visible', 'translate-y-0');
                navBoutiqueDropdown.classList.add('opacity-0', 'invisible', 'translate-y-1');
            }
            if (sortDropdownPanel) {
                sortDropdownPanel.classList.remove('opacity-100', 'visible');
                sortDropdownPanel.classList.add('opacity-0', 'invisible');
                if (sortDropdownChevron) sortDropdownChevron.classList.remove('rotate-180');
            }
        }
    });

    // =========================================================================
    // 11. Scroll Reveal Animations (IntersectionObserver)
    // =========================================================================
    const revealElements = document.querySelectorAll('.reveal-on-scroll');
    if (revealElements.length > 0) {
        const revealObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.08,
            rootMargin: '0px 0px -30px 0px'
        });

        revealElements.forEach(el => revealObserver.observe(el));
    }

    // Initial render of cart badges on page load
    renderCartUI();
});
