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
    const CART_STORAGE_KEY = 'zizo_aura_shopping_cart';
    const COUPON_STORAGE_KEY = 'zizo_aura_applied_coupon';
    const STANDARD_SHIPPING_FEE = 35; // DH partout au Maroc

    const getCart = () => {
        try {
            return JSON.parse(localStorage.getItem(CART_STORAGE_KEY)) || [];
        } catch (e) {
            return [];
        }
    };

    const saveCart = (cart) => {
        localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
        renderCartUI();
    };

    const getAppliedCoupon = () => {
        try {
            const raw = localStorage.getItem(COUPON_STORAGE_KEY);
            return raw ? JSON.parse(raw) : null;
        } catch (e) {
            return null;
        }
    };

    const setAppliedCoupon = (coupon) => {
        try {
            if (coupon) {
                localStorage.setItem(COUPON_STORAGE_KEY, JSON.stringify(coupon));
            } else {
                localStorage.removeItem(COUPON_STORAGE_KEY);
            }
        } catch (e) {}
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
    const cartDrawerDiscountRow = document.getElementById('cart-drawer-discount-row');
    const cartDrawerDiscountLabel = document.getElementById('cart-drawer-discount-label');
    const cartDrawerDiscountAmount = document.getElementById('cart-drawer-discount-amount');
    const cartWhatsappBtn = document.getElementById('cart-whatsapp-btn');
    const navbarCartBtn = document.getElementById('navbar-cart-btn');
    const cartContinueShoppingBtn = document.getElementById('cart-continue-shopping-btn');

    // Coupon UI Elements
    const couponInputWrapper = document.getElementById('coupon-input-wrapper');
    const couponInput = document.getElementById('cart-coupon-input');
    const couponApplyBtn = document.getElementById('cart-coupon-apply-btn');
    const couponAppliedBadge = document.getElementById('coupon-applied-badge');
    const appliedCouponCode = document.getElementById('applied-coupon-code');
    const appliedCouponDiscount = document.getElementById('applied-coupon-discount');
    const couponRemoveBtn = document.getElementById('coupon-remove-btn');
    const couponFeedbackMsg = document.getElementById('coupon-feedback-msg');

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

    let couponFeedbackTimeout = null;

    const showCouponFeedback = (msg, type) => {
        if (!couponFeedbackMsg) return;
        if (couponFeedbackTimeout) {
            clearTimeout(couponFeedbackTimeout);
            couponFeedbackTimeout = null;
        }

        if (type === 'clear' || !msg) {
            couponFeedbackMsg.textContent = '';
            couponFeedbackMsg.classList.add('hidden');
            return;
        }

        couponFeedbackMsg.textContent = msg;
        couponFeedbackMsg.classList.remove('hidden', 'text-rose-600', 'text-emerald-600');
        if (type === 'success') {
            couponFeedbackMsg.classList.add('text-emerald-600');
        } else {
            couponFeedbackMsg.classList.add('text-rose-600');
        }

        // Auto-dissolve after exactly 3 seconds to keep UI clean and non-redundant
        couponFeedbackTimeout = setTimeout(() => {
            couponFeedbackMsg.textContent = '';
            couponFeedbackMsg.classList.add('hidden');
        }, 3000);
    };

    const renderCartUI = () => {
        const cart = getCart();
        const totalItemsCount = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
        const subtotal = cart.reduce((sum, item) => sum + ((parseFloat(item.price) || 0) * (item.quantity || 1)), 0);
        const appliedCoupon = getAppliedCoupon();

        // Calculate discount
        let discountAmount = 0;
        if (appliedCoupon && subtotal > 0) {
            if (appliedCoupon.type === 'percent') {
                discountAmount = Math.round(subtotal * (parseFloat(appliedCoupon.value) || 0) / 100);
            } else {
                discountAmount = Math.min(parseFloat(appliedCoupon.value) || 0, subtotal);
            }
        } else if (subtotal === 0 && appliedCoupon) {
            setAppliedCoupon(null);
        }

        // Update count badges
        if (cartBadge) {
            cartBadge.textContent = totalItemsCount;
            cartBadge.classList.add('scale-125');
            setTimeout(() => cartBadge.classList.remove('scale-125'), 250);
        }
        if (cartDrawerCount) {
            cartDrawerCount.textContent = totalItemsCount;
        }

        // Update Coupon Card Display
        if (appliedCoupon && subtotal > 0) {
            if (couponInputWrapper) couponInputWrapper.classList.add('hidden');
            if (couponAppliedBadge) {
                couponAppliedBadge.classList.remove('hidden');
                if (appliedCouponCode) appliedCouponCode.textContent = appliedCoupon.code;
                if (appliedCouponDiscount) appliedCouponDiscount.textContent = `(${appliedCoupon.label || ('-' + appliedCoupon.value + '%')})`;
            }
            if (cartDrawerDiscountRow) {
                cartDrawerDiscountRow.classList.remove('hidden');
                if (cartDrawerDiscountLabel) cartDrawerDiscountLabel.textContent = `Remise code (${appliedCoupon.code})`;
                if (cartDrawerDiscountAmount) cartDrawerDiscountAmount.textContent = `-${discountAmount} DH`;
            }
        } else {
            if (couponInputWrapper) couponInputWrapper.classList.remove('hidden');
            if (couponAppliedBadge) couponAppliedBadge.classList.add('hidden');
            if (cartDrawerDiscountRow) cartDrawerDiscountRow.classList.add('hidden');
        }

        // Calculate shipping fee (Flat rate 35 DH when cart has items, 0 DH when empty)
        const shippingFee = subtotal === 0 ? 0 : STANDARD_SHIPPING_FEE;

        // Calculate final total (Subtotal - Discount + Shipping)
        const finalTotal = subtotal === 0 ? 0 : Math.max(0, subtotal - discountAmount + shippingFee);

        // Update Totals Display
        if (cartDrawerSubtotal) cartDrawerSubtotal.textContent = `${subtotal} DH`;

        if (cartDrawerShipping) {
            if (subtotal === 0) {
                cartDrawerShipping.textContent = '0 DH';
                cartDrawerShipping.className = 'font-bold text-zinc-400';
            } else {
                cartDrawerShipping.textContent = `${STANDARD_SHIPPING_FEE} DH`;
                cartDrawerShipping.className = 'font-bold text-zinc-900';
            }
        }

        if (cartDrawerTotal) {
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
                const shippingCostText = `${STANDARD_SHIPPING_FEE} DH`;
                msg += `\n*Sous-total :* ${subtotal} DH\n`;
                if (appliedCoupon && discountAmount > 0) {
                    msg += `*Code promo (${appliedCoupon.code}) :* -${discountAmount} DH\n`;
                }
                msg += `*Livraison :* ${shippingCostText}\n*Total à payer :* ${finalTotal} DH\n\nMerci de me confirmer la commande !`;
                cartWhatsappBtn.href = `https://wa.me/212682787594?text=${encodeURIComponent(msg)}`;
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
                        <i class="uil uil-shopping-bag"></i>
                    </div>
                    <h4 class="text-base font-extrabold text-zinc-900 mb-1">Votre panier est vide</h4>
                    <p class="text-xs text-zinc-400 max-w-xs mb-6 font-medium">
                        Découvrez nos formules solaires et coffrets exclusifs pour composer votre rituel beauté.
                    </p>
                    <a href="/boutique" id="empty-cart-shop-btn" class="btn-card-pill py-3 px-6 text-xs uppercase font-extrabold">
                        <span>Explorer la boutique</span>
                        <i class="uil uil-arrow-right text-sm"></i>
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
                const productUrl = item.slug ? `/boutique/produit/${item.slug}` : '/boutique';
                html += `
                    <div class="py-3.5 flex items-center gap-3.5 group">
                        <a href="${productUrl}" class="w-16 h-16 rounded-xl bg-[#f8f9fa] border border-zinc-100 p-1 flex items-center justify-center shrink-0 overflow-hidden">
                            <img src="${item.image || '/images/sdj_bum_bum_set.png'}" alt="${item.name}" class="w-full h-full object-contain group-hover:scale-105 transition-transform" />
                        </a>
                        <div class="flex-1 min-w-0">
                            <a href="${productUrl}" class="text-xs font-bold text-zinc-900 hover:text-pink-600 transition-colors line-clamp-1 block">
                                ${item.name}
                            </a>
                            ${variantInfo ? `<p class="text-[10px] text-zinc-400 font-medium truncate mt-0.5">${variantInfo}</p>` : ''}
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs font-extrabold text-pink-600">${item.price} DH</span>
                                <div class="flex items-center bg-[#f8f9fa] border border-zinc-200 rounded-full px-1.5 py-0.5 shadow-2xs">
                                    <button type="button" data-cart-action="dec" data-index="${index}" class="btn-circle-action w-7 h-7 min-w-[28px] min-h-[28px] bg-white text-zinc-700 flex items-center justify-center cursor-pointer shadow-2xs text-xs" aria-label="Diminuer">
                                        <i class="uil uil-minus"></i>
                                    </button>
                                    <span class="w-6 text-center text-xs font-bold text-zinc-900 select-none">${item.quantity || 1}</span>
                                    <button type="button" data-cart-action="inc" data-index="${index}" class="btn-circle-action w-7 h-7 min-w-[28px] min-h-[28px] bg-white text-zinc-700 flex items-center justify-center cursor-pointer shadow-2xs text-xs" aria-label="Augmenter">
                                        <i class="uil uil-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" data-cart-action="remove" data-index="${index}" class="p-2 text-zinc-400 hover:text-rose-500 rounded-xl transition-colors cursor-pointer shrink-0" aria-label="Supprimer du panier">
                            <i class="uil uil-trash-alt text-base"></i>
                        </button>
                    </div>
                `;
            });
            cartDrawerItems.innerHTML = html;
        }
    };

    // Coupon Application Logic
    const handleApplyCoupon = async () => {
        if (!couponInput) return;
        const code = couponInput.value.trim();
        if (!code) {
            showCouponFeedback('Veuillez saisir un code promo.', 'error');
            return;
        }

        const cart = getCart();
        const subtotal = cart.reduce((sum, item) => sum + ((parseFloat(item.price) || 0) * (item.quantity || 1)), 0);

        if (subtotal === 0) {
            showCouponFeedback('Votre panier est vide.', 'error');
            return;
        }

        if (couponApplyBtn) {
            couponApplyBtn.disabled = true;
            couponApplyBtn.innerHTML = '<i class="uil uil-spinner-alt animate-spin text-xs"></i>';
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        try {
            const res = await fetch('/api/coupon/validate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                },
                body: JSON.stringify({ code, subtotal }),
            });

            const data = await res.json();

            if (res.ok && data.valid) {
                setAppliedCoupon(data);
                couponInput.value = '';
                showCouponFeedback('', 'clear');
                showToast(`Code promo ${data.code} appliqué !`);
                renderCartUI();
            } else {
                showCouponFeedback(data.message || 'Code promo invalide.', 'error');
            }
        } catch (e) {
            showCouponFeedback('Erreur lors de la validation du code.', 'error');
        } finally {
            if (couponApplyBtn) {
                couponApplyBtn.disabled = false;
                couponApplyBtn.textContent = 'Appliquer';
            }
        }
    };

    const handleRemoveCoupon = () => {
        setAppliedCoupon(null);
        showCouponFeedback('', 'clear');
        renderCartUI();
        showToast('Code promo retiré');
    };

    if (couponApplyBtn) {
        couponApplyBtn.addEventListener('click', handleApplyCoupon);
    }

    if (couponInput) {
        couponInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                handleApplyCoupon();
            }
        });
    }

    if (couponRemoveBtn) {
        couponRemoveBtn.addEventListener('click', handleRemoveCoupon);
    }

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

    // Clean up cart when order is processed through WhatsApp
    if (cartWhatsappBtn) {
        cartWhatsappBtn.addEventListener('click', () => {
            const cart = getCart();
            if (cart.length > 0) {
                // Short timeout so the new tab captures the WhatsApp link before resetting
                setTimeout(() => {
                    saveCart([]);
                    setAppliedCoupon(null);
                    showCouponFeedback('', 'clear');
                    showToast('Commande transmise ! Votre panier a été réinitialisé.');
                }, 350);
            }
        });
    }

    // =========================================================================
    // 2.1 Online Checkout Modal & Order Processing (Route 2)
    // =========================================================================
    const checkoutModal = document.getElementById('checkout-modal');
    const checkoutModalContainer = document.getElementById('checkout-modal-container');
    const checkoutModalCloseBtn = document.getElementById('checkout-modal-close-btn');
    const cartOnlineCheckoutBtn = document.getElementById('cart-online-checkout-btn');
    const checkoutFormView = document.getElementById('checkout-form-view');
    const checkoutSuccessView = document.getElementById('checkout-success-view');
    const checkoutCustomerName = document.getElementById('checkout-customer-name');
    const checkoutCustomerPhone = document.getElementById('checkout-customer-phone');
    const checkoutCity = document.getElementById('checkout-city');
    const checkoutShippingAddress = document.getElementById('checkout-shipping-address');
    const checkoutCustomerEmail = document.getElementById('checkout-customer-email');
    const checkoutNotes = document.getElementById('checkout-notes');
    const checkoutItemsPreview = document.getElementById('checkout-items-preview');
    const checkoutRecapSubtotal = document.getElementById('checkout-recap-subtotal');
    const checkoutRecapDiscountRow = document.getElementById('checkout-recap-discount-row');
    const checkoutRecapDiscountLabel = document.getElementById('checkout-recap-discount-label');
    const checkoutRecapDiscountAmount = document.getElementById('checkout-recap-discount-amount');
    const checkoutRecapTotal = document.getElementById('checkout-recap-total');
    const checkoutSubmitBtn = document.getElementById('checkout-submit-btn');
    const checkoutBtnText = document.getElementById('checkout-btn-text');
    const checkoutBtnIcon = document.getElementById('checkout-btn-icon');
    const checkoutBtnSpinner = document.getElementById('checkout-btn-spinner');
    const checkoutErrorBanner = document.getElementById('checkout-error-banner');
    const checkoutErrorMsg = document.getElementById('checkout-error-msg');
    const checkoutSuccessCloseBtn = document.getElementById('checkout-success-close-btn');

    const successOrderNumber = document.getElementById('success-order-number');
    const successCustomerName = document.getElementById('success-customer-name');
    const successCustomerPhone = document.getElementById('success-customer-phone');
    const successCity = document.getElementById('success-city');
    const successTotal = document.getElementById('success-total');

    const renderCheckoutRecap = () => {
        const cart = getCart();
        const subtotal = cart.reduce((sum, item) => sum + ((parseFloat(item.price) || 0) * (item.quantity || 1)), 0);
        const appliedCoupon = getAppliedCoupon();

        let discountAmount = 0;
        if (appliedCoupon && subtotal > 0) {
            if (appliedCoupon.type === 'percent') {
                discountAmount = Math.round(subtotal * (parseFloat(appliedCoupon.value) || 0) / 100);
            } else {
                discountAmount = Math.min(parseFloat(appliedCoupon.value) || 0, subtotal);
            }
        }

        const shippingFee = subtotal === 0 ? 0 : STANDARD_SHIPPING_FEE;
        const finalTotal = subtotal === 0 ? 0 : Math.max(0, subtotal - discountAmount + shippingFee);

        if (checkoutRecapSubtotal) checkoutRecapSubtotal.textContent = `${subtotal} DH`;

        if (checkoutRecapDiscountRow) {
            if (appliedCoupon && discountAmount > 0) {
                checkoutRecapDiscountRow.classList.remove('hidden');
                if (checkoutRecapDiscountLabel) checkoutRecapDiscountLabel.textContent = `Remise (${appliedCoupon.code})`;
                if (checkoutRecapDiscountAmount) checkoutRecapDiscountAmount.textContent = `-${discountAmount} DH`;
            } else {
                checkoutRecapDiscountRow.classList.add('hidden');
            }
        }

        if (checkoutRecapTotal) checkoutRecapTotal.textContent = `${finalTotal} DH`;

        if (checkoutItemsPreview) {
            let html = '';
            cart.forEach(item => {
                const variantInfo = [item.flavor, item.size].filter(Boolean).join(' • ');
                html += `
                    <div class="py-2 flex items-center justify-between gap-3 text-xs">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <img src="${item.image || '/images/sdj_bum_bum_set.png'}" alt="${item.name}" class="w-9 h-9 object-contain rounded-lg bg-white border border-zinc-200 p-0.5 shrink-0" />
                            <div class="truncate">
                                <p class="font-bold text-zinc-900 truncate">${item.name}</p>
                                ${variantInfo ? `<p class="text-[10px] text-zinc-400 font-medium truncate">${variantInfo}</p>` : ''}
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-zinc-500 font-semibold mr-1.5">x${item.quantity || 1}</span>
                            <span class="font-extrabold text-pink-600">${item.price * (item.quantity || 1)} DH</span>
                        </div>
                    </div>
                `;
            });
            checkoutItemsPreview.innerHTML = html;
        }
    };

    let activeCheckoutMode = 'cart'; // 'cart' | 'preorder'
    let currentPreorderItem = null;

    const renderPreorderRecap = (item) => {
        const qty = parseInt(item.quantity, 10) || 1;
        const unitPrice = parseFloat(item.price) || 0;
        const subtotal = Math.round(unitPrice * qty);
        const shippingFee = subtotal === 0 ? 0 : STANDARD_SHIPPING_FEE;
        const finalTotal = subtotal + shippingFee;

        if (checkoutRecapSubtotal) checkoutRecapSubtotal.textContent = `${subtotal} DH`;
        if (checkoutRecapDiscountRow) checkoutRecapDiscountRow.classList.add('hidden');
        if (checkoutRecapTotal) checkoutRecapTotal.textContent = `${finalTotal} DH`;

        if (checkoutItemsPreview) {
            const variantInfo = [item.flavor, item.size].filter(Boolean).join(' • ');
            checkoutItemsPreview.innerHTML = `
                <div class="py-2 flex items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <img src="${item.image || '/images/sdj_bum_bum_set.png'}" alt="${item.name}" class="w-9 h-9 object-contain rounded-lg bg-white border border-zinc-200 p-0.5 shrink-0" />
                        <div class="truncate">
                            <div class="flex items-center gap-1.5 truncate">
                                <p class="font-bold text-zinc-900 truncate">${item.name}</p>
                                <span class="px-1.5 py-0.2 rounded-full bg-amber-100 text-amber-800 font-extrabold text-[9px] shrink-0">Précommande</span>
                            </div>
                            ${variantInfo ? `<p class="text-[10px] text-zinc-400 font-medium truncate">${variantInfo}</p>` : ''}
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-zinc-500 font-semibold mr-1.5">x${qty}</span>
                        <span class="font-extrabold text-pink-600">${subtotal} DH</span>
                    </div>
                </div>
            `;
        }
    };

    const openPreorderModal = (item) => {
        activeCheckoutMode = 'preorder';
        currentPreorderItem = item;

        closeCartDrawer();

        const modalTitle = document.getElementById('checkout-modal-title');
        const modalSubtitle = document.querySelector('#checkout-modal-container .px-5 p') || document.querySelector('#checkout-modal-container p');
        if (modalTitle) modalTitle.textContent = 'Précommander en ligne';
        if (modalSubtitle) modalSubtitle.textContent = 'Paiement en espèces à la livraison dès disponibilité';

        if (checkoutBtnText) checkoutBtnText.textContent = 'Confirmer la précommande (Paiement à la livraison)';

        renderPreorderRecap(item);

        // Reset views
        if (checkoutFormView) checkoutFormView.classList.remove('hidden');
        if (checkoutSuccessView) checkoutSuccessView.classList.add('hidden');
        if (checkoutErrorBanner) checkoutErrorBanner.classList.add('hidden');

        if (checkoutModal && checkoutModalContainer) {
            checkoutModal.classList.remove('opacity-0', 'pointer-events-none');
            checkoutModal.classList.add('opacity-100', 'pointer-events-auto');
            checkoutModalContainer.classList.remove('scale-95', 'opacity-0');
            checkoutModalContainer.classList.add('scale-100', 'opacity-100');
            document.body.classList.add('overflow-hidden');
        }
    };

    const openCheckoutModal = () => {
        activeCheckoutMode = 'cart';
        currentPreorderItem = null;

        const cart = getCart();
        if (cart.length === 0) {
            showToast('Votre panier est vide.');
            openCartDrawer();
            return;
        }

        closeCartDrawer();

        const modalTitle = document.getElementById('checkout-modal-title');
        const modalSubtitle = document.querySelector('#checkout-modal-container .px-5 p') || document.querySelector('#checkout-modal-container p');
        if (modalTitle) modalTitle.textContent = 'Commander en ligne';
        if (modalSubtitle) modalSubtitle.textContent = 'Paiement en espèces à la livraison partout au Maroc';

        if (checkoutBtnText) checkoutBtnText.textContent = 'Confirmer la commande (Paiement à la livraison)';

        renderCheckoutRecap();

        // Reset views
        if (checkoutFormView) checkoutFormView.classList.remove('hidden');
        if (checkoutSuccessView) checkoutSuccessView.classList.add('hidden');
        if (checkoutErrorBanner) checkoutErrorBanner.classList.add('hidden');

        if (checkoutModal && checkoutModalContainer) {
            checkoutModal.classList.remove('opacity-0', 'pointer-events-none');
            checkoutModal.classList.add('opacity-100', 'pointer-events-auto');
            checkoutModalContainer.classList.remove('scale-95', 'opacity-0');
            checkoutModalContainer.classList.add('scale-100', 'opacity-100');
            document.body.classList.add('overflow-hidden');
        }
    };

    const closeCheckoutModal = () => {
        if (checkoutModal && checkoutModalContainer) {
            checkoutModal.classList.remove('opacity-100', 'pointer-events-auto');
            checkoutModal.classList.add('opacity-0', 'pointer-events-none');
            checkoutModalContainer.classList.remove('scale-100', 'opacity-100');
            checkoutModalContainer.classList.add('scale-95', 'opacity-0');
            document.body.classList.remove('overflow-hidden');
        }
    };

    const handleCheckoutSubmit = async () => {
        const cart = getCart();
        const items = activeCheckoutMode === 'preorder' ? [currentPreorderItem] : cart;

        if (!items || items.length === 0 || !items[0]) {
            showToast(activeCheckoutMode === 'preorder' ? 'Aucun article en précommande sélectionné.' : 'Votre panier est vide.');
            closeCheckoutModal();
            return;
        }

        const name = checkoutCustomerName?.value.trim();
        const phone = checkoutCustomerPhone?.value.trim();
        const city = checkoutCity?.value.trim();
        const address = checkoutShippingAddress?.value.trim();
        const email = checkoutCustomerEmail?.value.trim();
        const notes = checkoutNotes?.value.trim();
        const appliedCoupon = getAppliedCoupon();

        if (!name || !phone || !city || !address) {
            if (checkoutErrorBanner && checkoutErrorMsg) {
                checkoutErrorMsg.textContent = 'Veuillez remplir tous les champs obligatoires (*).';
                checkoutErrorBanner.classList.remove('hidden');
            }
            return;
        }

        if (phone.length < 8) {
            if (checkoutErrorBanner && checkoutErrorMsg) {
                checkoutErrorMsg.textContent = 'Veuillez renseigner un numéro de téléphone valide.';
                checkoutErrorBanner.classList.remove('hidden');
            }
            return;
        }

        if (checkoutErrorBanner) checkoutErrorBanner.classList.add('hidden');

        // Loading state
        if (checkoutSubmitBtn) {
            checkoutSubmitBtn.disabled = true;
            if (checkoutBtnText) checkoutBtnText.textContent = 'Validation en cours...';
            if (checkoutBtnIcon) checkoutBtnIcon.classList.add('hidden');
            if (checkoutBtnSpinner) checkoutBtnSpinner.classList.remove('hidden');
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        const payload = {
            customer_name: name,
            customer_phone: phone,
            customer_email: email || null,
            city: city,
            shipping_address: address,
            notes: notes || null,
            coupon_code: appliedCoupon && activeCheckoutMode === 'cart' ? appliedCoupon.code : null,
            is_preorder: activeCheckoutMode === 'preorder',
            items: items
        };

        try {
            const res = await fetch('/api/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                },
                body: JSON.stringify(payload)
            });

            const data = await res.json();

            if (res.ok && (data.success || data.order)) {
                const orderData = data.order || {};
                
                // Clear cart state if regular checkout
                if (activeCheckoutMode === 'cart') {
                    saveCart([]);
                    setAppliedCoupon(null);
                    showCouponFeedback('', 'clear');
                }

                // Populate success view
                if (successOrderNumber) successOrderNumber.textContent = `#${orderData.order_number || ('CMD-' + orderData.id)}`;
                if (successCustomerName) successCustomerName.textContent = name;
                if (successCustomerPhone) successCustomerPhone.textContent = phone;
                if (successCity) successCity.textContent = city;
                if (successTotal) successTotal.textContent = `${orderData.total || '0'} DH`;

                const successBadgeTitle = document.getElementById('success-badge-title');
                const successHeadingTitle = document.getElementById('success-heading-title');
                const successMessageText = document.getElementById('success-message-text');
                const successContactNotice = document.getElementById('success-contact-notice');

                if (activeCheckoutMode === 'preorder' || data.is_preorder) {
                    if (successBadgeTitle) {
                        successBadgeTitle.textContent = 'Précommande enregistrée avec succès';
                        successBadgeTitle.className = 'px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-[11px] font-black uppercase tracking-wider';
                    }
                    if (successHeadingTitle) successHeadingTitle.textContent = 'Merci pour votre précommande !';
                    if (successMessageText) successMessageText.innerHTML = `Votre numéro de précommande est <strong id="success-order-number" class="text-pink-600 font-extrabold">#${orderData.order_number || ('CMD-' + orderData.id)}</strong>. Notre équipe va réserver votre article et vous contacter dès réception du stock.`;
                    if (successContactNotice) successContactNotice.innerHTML = `<i class="uil uil-clock text-sm text-amber-600 mr-1"></i>Notre service client vous contactera dès la réception du stock pour confirmer la livraison.`;
                    showToast('Précommande enregistrée avec succès !');
                } else {
                    if (successBadgeTitle) {
                        successBadgeTitle.textContent = 'Commande validée avec succès';
                        successBadgeTitle.className = 'px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-black uppercase tracking-wider';
                    }
                    if (successHeadingTitle) successHeadingTitle.textContent = 'Merci pour votre commande !';
                    if (successMessageText) successMessageText.innerHTML = `Votre numéro de commande est <strong id="success-order-number" class="text-pink-600 font-extrabold">#${orderData.order_number || ('CMD-' + orderData.id)}</strong>. Notre équipe va préparer votre colis avec le plus grand soin.`;
                    if (successContactNotice) successContactNotice.innerHTML = `<i class="uil uil-phone-volume text-sm text-pink-600 mr-1"></i>Notre service client vous contactera très rapidement par téléphone pour confirmer l'expédition.`;
                    showToast('Commande validée avec succès !');
                }

                // Switch to success view
                if (checkoutFormView) checkoutFormView.classList.add('hidden');
                if (checkoutSuccessView) checkoutSuccessView.classList.remove('hidden');

            } else {
                const errorMsg = data.message || (data.errors ? Object.values(data.errors).flat().join(' ') : 'Une erreur est survenue lors de la validation.');
                if (checkoutErrorBanner && checkoutErrorMsg) {
                    checkoutErrorMsg.textContent = errorMsg;
                    checkoutErrorBanner.classList.remove('hidden');
                }
            }
        } catch (err) {
            if (checkoutErrorBanner && checkoutErrorMsg) {
                checkoutErrorMsg.textContent = 'Erreur réseau. Veuillez réessayer ou commander via WhatsApp.';
                checkoutErrorBanner.classList.remove('hidden');
            }
        } finally {
            if (checkoutSubmitBtn) {
                checkoutSubmitBtn.disabled = false;
                if (checkoutBtnText) checkoutBtnText.textContent = activeCheckoutMode === 'preorder' ? 'Confirmer la précommande (Paiement à la livraison)' : 'Confirmer la commande (Paiement à la livraison)';
                if (checkoutBtnIcon) checkoutBtnIcon.classList.remove('hidden');
                if (checkoutBtnSpinner) checkoutBtnSpinner.classList.add('hidden');
            }
        }
    };

    if (cartOnlineCheckoutBtn) {
        cartOnlineCheckoutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openCheckoutModal();
        });
    }

    if (checkoutModalCloseBtn) {
        checkoutModalCloseBtn.addEventListener('click', closeCheckoutModal);
    }

    if (checkoutSuccessCloseBtn) {
        checkoutSuccessCloseBtn.addEventListener('click', closeCheckoutModal);
    }

    if (checkoutModal) {
        checkoutModal.addEventListener('click', (e) => {
            if (e.target === checkoutModal) closeCheckoutModal();
        });
    }

    if (checkoutSubmitBtn) {
        checkoutSubmitBtn.addEventListener('click', handleCheckoutSubmit);
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
                image: image || '/images/sdj_bum_bum_set.png',
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
    document.querySelectorAll('button[data-add-to-cart]').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();

            const name = button.dataset.productName || 'Produit';
            const price = parseFloat(button.dataset.productPrice) || 350;
            const image = button.dataset.productImage || '';
            const slug = button.dataset.productSlug || '';

            addItemToCart({ name, price, image, slug, quantity: 1 });

            // Button Feedback Animation
            const origHtml = button.innerHTML;
            button.style.backgroundColor = '#10b981';
            button.innerHTML = `<i class="uil uil-check text-base"></i><span>Ajouté ✓</span>`;
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
    const productPreorderBtn = document.getElementById('product-preorder-btn');
    const mainActionBtn = productAddCartBtn || productPreorderBtn;
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
                b.classList.remove('border-black', 'bg-zinc-900', 'text-white', 'ring-2', 'ring-black/10', 'shadow-sm');
                b.classList.add('border-zinc-200', 'text-zinc-700', 'bg-white');
            });
            btn.classList.remove('border-zinc-200', 'text-zinc-700', 'bg-white');
            btn.classList.add('border-black', 'bg-zinc-900', 'text-white', 'ring-2', 'ring-black/10', 'shadow-sm');

            const size = btn.dataset.sizeName || '';
            currentSize = size;
            if (selectedSizeLabel) {
                selectedSizeLabel.textContent = size;
            }
        });
    });

    // Quantity Selector Controller (Active for both direct order & pre-order)
    if (qtyMinusBtn && qtyPlusBtn && mainActionBtn) {
        const unitPrice = parseFloat(mainActionBtn.dataset.unitPrice) || 350;
        const isPreorderMode = !productAddCartBtn && !!productPreorderBtn;

        const updateQtyUI = (qty) => {
            if (qtyInput) qtyInput.value = qty;
            if (qtyDisplay) qtyDisplay.textContent = qty;
            qtyMinusBtn.disabled = (qty <= 1);
            const totalPrice = Math.round(unitPrice * qty);
            if (btnCartText) {
                btnCartText.textContent = isPreorderMode ? `Précommander • ${totalPrice} DH` : `Ajouter au panier • ${totalPrice} DH`;
            }
        };

        qtyMinusBtn.addEventListener('click', () => {
            let current = parseInt(qtyInput ? qtyInput.value : (qtyDisplay ? qtyDisplay.textContent : '1'), 10) || 1;
            if (current > 1) updateQtyUI(current - 1);
        });

        qtyPlusBtn.addEventListener('click', () => {
            let current = parseInt(qtyInput ? qtyInput.value : (qtyDisplay ? qtyDisplay.textContent : '1'), 10) || 1;
            if (current < 20) updateQtyUI(current + 1);
        });

        if (productAddCartBtn) {
            productAddCartBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const qty = parseInt(qtyInput ? qtyInput.value : (qtyDisplay ? qtyDisplay.textContent : '1'), 10) || 1;
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
                productAddCartBtn.innerHTML = `<i class="uil uil-check text-base sm:text-lg"></i><span>${qty} Ajouté${qty > 1 ? 's' : ''} ✓</span>`;
                productAddCartBtn.disabled = true;

                setTimeout(() => {
                    productAddCartBtn.style.backgroundColor = '';
                    productAddCartBtn.innerHTML = origContent;
                    productAddCartBtn.disabled = false;
                    openCartDrawer();
                }, 1000);
            });
        }

        if (productPreorderBtn) {
            productPreorderBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const qty = parseInt(qtyInput ? qtyInput.value : (qtyDisplay ? qtyDisplay.textContent : '1'), 10) || 1;
                const name = productPreorderBtn.dataset.productName || 'Produit';
                const price = parseFloat(productPreorderBtn.dataset.unitPrice) || 350;
                const image = productPreorderBtn.dataset.productImage || '';
                const slug = productPreorderBtn.dataset.productSlug || '';
                const id = productPreorderBtn.dataset.productId || null;

                openPreorderModal({
                    id,
                    name,
                    price,
                    image,
                    slug,
                    flavor: currentFlavor,
                    size: currentSize,
                    quantity: qty
                });
            });
        }

        // Mobile Sticky Purchase Bar Scroll Visibility Controller
        const mobileStickyBuyBar = document.getElementById('mobile-sticky-buy-bar');
        const mobileStickyAddBtn = document.getElementById('mobile-sticky-add-btn');

        if (mobileStickyBuyBar && mainActionBtn) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (window.innerWidth < 640) {
                        if (!entry.isIntersecting && entry.boundingClientRect.top < 0) {
                            mobileStickyBuyBar.classList.remove('translate-y-full');
                            mobileStickyBuyBar.classList.add('translate-y-0');
                        } else {
                            mobileStickyBuyBar.classList.remove('translate-y-0');
                            mobileStickyBuyBar.classList.add('translate-y-full');
                        }
                    }
                });
            }, { threshold: 0 });

            observer.observe(mainActionBtn);

            if (mobileStickyAddBtn) {
                mobileStickyAddBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const qty = parseInt(qtyInput ? qtyInput.value : '1', 10) || 1;
                    const name = mainActionBtn.dataset.productName || 'Produit';
                    const price = parseFloat(mainActionBtn.dataset.unitPrice || mainActionBtn.dataset.productPrice) || 350;
                    const image = mainActionBtn.dataset.productImage || '';
                    const slug = mainActionBtn.dataset.productSlug || '';
                    const id = mainActionBtn.dataset.productId || null;

                    if (productPreorderBtn) {
                        openPreorderModal({
                            id,
                            name,
                            price,
                            image,
                            slug,
                            flavor: currentFlavor,
                            size: currentSize,
                            quantity: qty
                        });
                    } else {
                        addItemToCart({
                            name,
                            price,
                            image,
                            slug,
                            flavor: currentFlavor,
                            size: currentSize,
                            quantity: qty
                        });
                        openCartDrawer();
                    }
                });
            }
        }
    }

    // Global Pre-order Buttons Listener (Catalog, Marquee, Related Products)
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('button[data-preorder-product]');
        if (!btn) return;
        if (btn === productPreorderBtn || (typeof mobileStickyAddBtn !== 'undefined' && btn === mobileStickyAddBtn)) return;

        e.preventDefault();
        const name = btn.dataset.productName || 'Produit';
        const price = parseFloat(btn.dataset.productPrice || btn.dataset.unitPrice) || 350;
        const image = btn.dataset.productImage || '';
        const slug = btn.dataset.productSlug || '';
        const id = btn.dataset.productId || null;

        openPreorderModal({
            id,
            name,
            price,
            image,
            slug,
            flavor: '',
            size: '',
            quantity: 1
        });
    });

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
        const promoDismissed = sessionStorage.getItem('zizo_aura_promo_seen');
        if (!promoDismissed) {
            setTimeout(() => {
                openPromoModal();
                sessionStorage.setItem('zizo_aura_promo_seen', 'true');
            }, 800);
        }

        if (closePromoBtn) {
            closePromoBtn.addEventListener('click', () => {
                closePromoModal();
                sessionStorage.setItem('zizo_aura_promo_seen', 'true');
            });
        }

        promoBackdrop.addEventListener('click', (e) => {
            if (e.target === promoBackdrop) {
                closePromoModal();
                sessionStorage.setItem('zizo_aura_promo_seen', 'true');
            }
        });

        // Allow manual trigger if user clicks any promo trigger button/banner
        document.querySelectorAll('[data-open-promo]').forEach(el => {
            el.addEventListener('click', (e) => {
                e.preventDefault();
                openPromoModal();
            });
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

        const updateArrowStates = () => {
            const maxScroll = reviewsSlider.scrollWidth - reviewsSlider.clientWidth;
            const atStart = reviewsSlider.scrollLeft <= 5;
            const atEnd = reviewsSlider.scrollLeft >= maxScroll - 5;

            if (atStart) {
                prevBtn.classList.add('opacity-40', 'cursor-not-allowed');
                prevBtn.classList.remove('hover:border-pink-600', 'hover:text-pink-600');
            } else {
                prevBtn.classList.remove('opacity-40', 'cursor-not-allowed');
                prevBtn.classList.add('hover:border-pink-600', 'hover:text-pink-600');
            }

            if (atEnd) {
                nextBtn.classList.add('opacity-40', 'cursor-not-allowed');
            } else {
                nextBtn.classList.remove('opacity-40', 'cursor-not-allowed');
            }
        };

        prevBtn.addEventListener('click', () => {
            reviewsSlider.scrollBy({ left: -getScrollStep(), behavior: 'smooth' });
        });

        nextBtn.addEventListener('click', () => {
            reviewsSlider.scrollBy({ left: getScrollStep(), behavior: 'smooth' });
        });

        reviewsSlider.addEventListener('scroll', updateArrowStates, { passive: true });
        window.addEventListener('resize', updateArrowStates);
        updateArrowStates();
    }

    // =========================================================================
    // 8. Search Bar & Live Autocomplete Suggestions (Grow & Shrink Animation)
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
        searchPill.classList.remove('is-collapsed');
        searchPill.classList.add('is-expanded');
        if (expandBtn) expandBtn.setAttribute('aria-expanded', 'true');
        navbarInput.focus();
    };

    const collapseSearchBar = (force = false) => {
        if (!searchPill || !navbarInput) return;
        if (force || navbarInput.value.trim().length === 0) {
            searchPill.classList.remove('is-expanded');
            searchPill.classList.add('is-collapsed');
            if (expandBtn) expandBtn.setAttribute('aria-expanded', 'false');
            if (clearBtn) clearBtn.classList.add('hidden');
        }
        if (suggestionsBox) suggestionsBox.classList.add('hidden');
    };

    if (expandBtn) {
        expandBtn.addEventListener('click', (e) => {
            const isCollapsed = searchPill && searchPill.classList.contains('is-collapsed');
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

    if (searchPill) {
        searchPill.addEventListener('click', (e) => {
            if (searchPill.classList.contains('is-collapsed')) {
                expandSearchBar();
            }
        });
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
                                <i class="uil uil-arrow-right text-[10px]"></i>
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
        navbarInput.addEventListener('click', () => {
            if (searchPill && searchPill.classList.contains('is-collapsed')) {
                expandSearchBar();
            }
        });

        navbarInput.addEventListener('focus', () => {
            expandSearchBar();
            if (navbarInput.value.trim().length > 0) {
                fetchLiveSuggestions(navbarInput.value);
            }
        });

        navbarInput.addEventListener('input', (e) => {
            clearTimeout(searchTimer);
            const val = e.target.value;
            if (clearBtn) {
                if (val.trim().length > 0) {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                }
            }
            searchTimer = setTimeout(() => {
                fetchLiveSuggestions(val);
            }, 180);
        });

        navbarInput.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (suggestionsBox && !suggestionsBox.classList.contains('hidden')) {
                    suggestionsBox.classList.add('hidden');
                } else {
                    navbarInput.blur();
                    collapseSearchBar(true);
                }
            }
        });
    }

    if (clearBtn && navbarInput) {
        clearBtn.addEventListener('click', (e) => {
            e.stopPropagation();
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

    if (navbarInput && navbarInput.value.trim().length > 0) {
        expandSearchBar();
        if (clearBtn) clearBtn.classList.remove('hidden');
    }

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

    // Contact Form AJAX Handler & Success Transition
    const storeContactForm = document.getElementById('store-contact-form');
    const contactSuccessState = document.getElementById('contact-success-state');
    const contactSuccessDesc = document.getElementById('contact-success-desc');
    const contactSubmitBtn = document.getElementById('contact-submit-btn');
    const contactNewMsgBtn = document.getElementById('contact-new-message-btn');

    if (storeContactForm && contactSuccessState) {
        storeContactForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            if (contactSubmitBtn) {
                contactSubmitBtn.disabled = true;
                contactSubmitBtn.innerHTML = `
                    <i class="uil uil-spinner-alt animate-spin text-base"></i>
                    <span>Envoi de votre message...</span>
                `;
            }

            const formData = new FormData(storeContactForm);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                const response = await fetch(storeContactForm.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || '',
                    },
                    body: formData,
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Update success description if provided
                    if (contactSuccessDesc && data.message) {
                        contactSuccessDesc.textContent = data.message;
                    }

                    // Smooth transition to luxury success state
                    storeContactForm.classList.add('hidden');
                    contactSuccessState.classList.remove('hidden');
                    contactSuccessState.classList.add('animate-fadeIn');

                    // Reset form fields
                    storeContactForm.reset();

                    // Toast notification
                    showToast('Message envoyé avec succès !');

                    // Scroll to top of card smoothly
                    const card = document.getElementById('contact-form-card');
                    if (card) {
                        card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                } else {
                    const errorMsg = data.message || (data.errors ? Object.values(data.errors).flat().join(' ') : 'Une erreur est survenue.');
                    showToast(errorMsg);
                    if (contactSubmitBtn) {
                        contactSubmitBtn.disabled = false;
                        contactSubmitBtn.innerHTML = `
                            <i class="uil uil-envelope-send text-base"></i>
                            <span>Envoyer le message</span>
                        `;
                    }
                }
            } catch (err) {
                // Fallback to standard form submission if fetch failed
                storeContactForm.submit();
            }
        });

        if (contactNewMsgBtn) {
            contactNewMsgBtn.addEventListener('click', () => {
                contactSuccessState.classList.add('hidden');
                storeContactForm.classList.remove('hidden');
                if (contactSubmitBtn) {
                    contactSubmitBtn.disabled = false;
                    contactSubmitBtn.innerHTML = `
                        <i class="uil uil-envelope-send text-base"></i>
                        <span>Envoyer le message</span>
                    `;
                }
                const firstInput = storeContactForm.querySelector('input[name="name"]');
                if (firstInput) firstInput.focus();
            });
        }
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

    // =========================================================================
    // 12. Product Full Page: Interactive Multi-Image Gallery & Lightbox
    // =========================================================================
    const galleryShowcase = document.getElementById('product-gallery-showcase');
    const mainStageImg = document.getElementById('product-main-stage-img');
    const galleryCounterIdx = document.getElementById('gallery-current-idx');
    const galleryPrevBtn = document.getElementById('gallery-prev-btn');
    const galleryNextBtn = document.getElementById('gallery-next-btn');
    const galleryThumbs = document.querySelectorAll('.gallery-thumb-btn');

    // Lightbox modal elements
    const lightboxModal = document.getElementById('product-lightbox-modal');
    const lightboxCloseBtn = document.getElementById('lightbox-close-btn');
    const lightboxPrevBtn = document.getElementById('lightbox-prev-btn');
    const lightboxNextBtn = document.getElementById('lightbox-next-btn');
    const lightboxMainImg = document.getElementById('lightbox-main-img');
    const lightboxCounter = document.getElementById('lightbox-counter');

    if (galleryShowcase && mainStageImg) {
        const imagesList = Array.from(galleryThumbs).map(b => b.dataset.imageSrc).filter(Boolean);
        if (imagesList.length === 0 && mainStageImg.src) {
            imagesList.push(mainStageImg.src);
        }

        let currentIndex = 0;

        const updateGalleryUI = (newIndex) => {
            if (imagesList.length === 0) return;
            currentIndex = (newIndex + imagesList.length) % imagesList.length;
            const currentSrc = imagesList[currentIndex];

            // Smooth fade effect
            mainStageImg.style.opacity = '0.3';
            mainStageImg.style.transform = 'scale(0.97)';
            setTimeout(() => {
                mainStageImg.src = currentSrc;
                mainStageImg.dataset.currentIndex = currentIndex;
                mainStageImg.style.opacity = '1';
                mainStageImg.style.transform = '';
            }, 120);

            if (galleryCounterIdx) {
                galleryCounterIdx.textContent = currentIndex + 1;
            }

            // Update thumbnails active state
            galleryThumbs.forEach((thumb, idx) => {
                if (idx === currentIndex) {
                    thumb.classList.remove('border-zinc-200', 'opacity-70');
                    thumb.classList.add('border-pink-500', 'ring-2', 'ring-pink-500/20', 'shadow-sm', 'scale-105', 'opacity-100');
                    thumb.scrollIntoView({ behavior: 'smooth', inline: 'nearest', block: 'nearest' });
                } else {
                    thumb.classList.remove('border-pink-500', 'ring-2', 'ring-pink-500/20', 'shadow-sm', 'scale-105', 'opacity-100');
                    thumb.classList.add('border-zinc-200', 'opacity-70');
                }
            });

            // Update Lightbox image if open
            if (lightboxMainImg && lightboxCounter) {
                lightboxMainImg.src = currentSrc;
                lightboxCounter.textContent = `${currentIndex + 1} / ${imagesList.length}`;
            }
        };

        // Wire thumbnail click
        galleryThumbs.forEach((thumb, idx) => {
            thumb.addEventListener('click', (e) => {
                e.preventDefault();
                updateGalleryUI(idx);
            });
        });

        // Wire Prev & Next buttons
        if (galleryPrevBtn) {
            galleryPrevBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                updateGalleryUI(currentIndex - 1);
            });
        }

        if (galleryNextBtn) {
            galleryNextBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                updateGalleryUI(currentIndex + 1);
            });
        }

        // Touch Swipe gestures on Mobile
        let touchStartX = 0;
        let touchEndX = 0;

        mainStageImg.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        mainStageImg.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            const diff = touchEndX - touchStartX;
            if (Math.abs(diff) > 40) {
                if (diff < 0) {
                    updateGalleryUI(currentIndex + 1); // Swipe left -> next
                } else {
                    updateGalleryUI(currentIndex - 1); // Swipe right -> prev
                }
            }
        }, { passive: true });

        // Lightbox Modal open on clicking main stage image
        const openLightbox = () => {
            if (!lightboxModal || !lightboxMainImg) return;
            lightboxMainImg.src = imagesList[currentIndex] || mainStageImg.src;
            if (lightboxCounter) lightboxCounter.textContent = `${currentIndex + 1} / ${imagesList.length}`;
            lightboxModal.classList.remove('opacity-0', 'pointer-events-none');
            lightboxModal.classList.add('opacity-100', 'pointer-events-auto');
            document.body.classList.add('overflow-hidden');
        };

        const closeLightbox = () => {
            if (!lightboxModal) return;
            lightboxModal.classList.remove('opacity-100', 'pointer-events-auto');
            lightboxModal.classList.add('opacity-0', 'pointer-events-none');
            document.body.classList.remove('overflow-hidden');
        };

        mainStageImg.addEventListener('click', openLightbox);

        if (lightboxCloseBtn) lightboxCloseBtn.addEventListener('click', closeLightbox);
        if (lightboxModal) {
            lightboxModal.addEventListener('click', (e) => {
                if (e.target === lightboxModal) closeLightbox();
            });
        }

        if (lightboxPrevBtn) {
            lightboxPrevBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                updateGalleryUI(currentIndex - 1);
            });
        }

        if (lightboxNextBtn) {
            lightboxNextBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                updateGalleryUI(currentIndex + 1);
            });
        }

        // Keyboard navigation Left & Right Arrow keys
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                updateGalleryUI(currentIndex - 1);
            } else if (e.key === 'ArrowRight') {
                updateGalleryUI(currentIndex + 1);
            } else if (e.key === 'Escape') {
                closeLightbox();
            }
        });
    }

    // =========================================================================
    // 25. Full-Width Hero 4-Picture Carousel Controller
    // =========================================================================
    const initHeroCarousel = () => {
        const heroSection = document.getElementById('hero-carousel-section');
        const stage = document.getElementById('heroCarouselStage');
        const track = document.getElementById('heroCarouselTrack');
        if (!heroSection || !stage || !track) return;

        const slides = Array.from(heroSection.querySelectorAll('.hero-slide'));
        const brandPills = Array.from(heroSection.querySelectorAll('.hero-brand-pill'));
        const prevBtn = document.getElementById('heroPrevBtn');
        const nextBtn = document.getElementById('heroNextBtn');
        const autoplayToggle = document.getElementById('heroAutoplayToggle');

        if (slides.length === 0) return;

        const TOTAL = slides.length;
        const AUTOPLAY_INTERVAL = 5000; // ms per slide

        let currentIndex = 0;
        let autoplayTimer = null;
        let isPaused = false;
        let isHovered = false;

        let startX = 0;
        let currentX = 0;
        let isDragging = false;

        const setSlide = (newIndex, { instant = false } = {}) => {
            newIndex = ((newIndex % TOTAL) + TOTAL) % TOTAL;
            if (newIndex === currentIndex && !instant) return;

            currentIndex = newIndex;

            if (instant) {
                track.style.transition = 'none';
                track.style.transform = `translateX(-${currentIndex * 100}%)`;
                void track.offsetWidth; // Force reflow
                track.style.transition = '';
            } else {
                track.style.transform = `translateX(-${currentIndex * 100}%)`;
            }

            // Update Brand Pills
            brandPills.forEach((pill, idx) => {
                const isActive = idx === currentIndex;
                pill.classList.toggle('is-active', isActive);
                pill.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });

            // Update Slides active state for accessibility
            slides.forEach((slide, idx) => {
                slide.setAttribute('aria-hidden', idx === currentIndex ? 'false' : 'true');
            });
        };

        const startAutoplay = () => {
            stopAutoplay();
            if (isPaused) return;

            autoplayTimer = setInterval(() => {
                if (isHovered || isDragging) return;
                setSlide(currentIndex + 1);
            }, AUTOPLAY_INTERVAL);
        };

        const stopAutoplay = () => {
            if (autoplayTimer) {
                clearInterval(autoplayTimer);
                autoplayTimer = null;
            }
        };

        // Navigation Arrows
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                setSlide(currentIndex - 1);
                startAutoplay();
            });
        }
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                setSlide(currentIndex + 1);
                startAutoplay();
            });
        }

        // Brand Pills
        brandPills.forEach((pill) => {
            pill.addEventListener('click', () => {
                const target = parseInt(pill.dataset.slideTarget, 10);
                if (!isNaN(target)) {
                    setSlide(target);
                    startAutoplay();
                }
            });
        });

        // Autoplay Toggle Button
        if (autoplayToggle) {
            autoplayToggle.addEventListener('click', () => {
                isPaused = !isPaused;
                autoplayToggle.classList.toggle('is-paused', isPaused);
                const pauseIcon = autoplayToggle.querySelector('.hero-autoplay-icon-pause');
                const playIcon = autoplayToggle.querySelector('.hero-autoplay-icon-play');
                if (pauseIcon && playIcon) {
                    if (isPaused) {
                        pauseIcon.classList.add('hidden');
                        pauseIcon.classList.remove('flex');
                        playIcon.classList.remove('hidden');
                        playIcon.classList.add('flex');
                        stopAutoplay();
                    } else {
                        playIcon.classList.add('hidden');
                        playIcon.classList.remove('flex');
                        pauseIcon.classList.remove('hidden');
                        pauseIcon.classList.add('flex');
                        startAutoplay();
                    }
                }
            });
        }

        // Pause on Hover
        stage.addEventListener('mouseenter', () => {
            isHovered = true;
        });
        stage.addEventListener('mouseleave', () => {
            isHovered = false;
        });

        // Touch / Drag Gesture
        let hasDragged = false;

        const handleDragStart = (e) => {
            startX = e.type.includes('mouse') ? e.clientX : e.touches[0].clientX;
            currentX = startX;
            isDragging = true;
            hasDragged = false;
            track.style.transition = 'none';
        };

        const handleDragMove = (e) => {
            if (!isDragging) return;
            currentX = e.type.includes('mouse') ? e.clientX : e.touches[0].clientX;
            const deltaX = currentX - startX;
            if (Math.abs(deltaX) > 8) {
                hasDragged = true;
            }
            const baseOffset = -(currentIndex * 100);
            const dragPercent = (deltaX / stage.offsetWidth) * 100;
            track.style.transform = `translateX(${baseOffset + dragPercent}%)`;
        };

        const handleDragEnd = () => {
            if (!isDragging) return;
            isDragging = false;
            track.style.transition = '';
            const deltaX = currentX - startX;
            const threshold = 40; // px
            if (deltaX > threshold) {
                setSlide(currentIndex - 1);
            } else if (deltaX < -threshold) {
                setSlide(currentIndex + 1);
            } else {
                setSlide(currentIndex);
            }
            startAutoplay();
        };

        // Prevent accidental link navigation if user dragged
        track.addEventListener('click', (e) => {
            if (hasDragged) {
                e.preventDefault();
                e.stopPropagation();
                hasDragged = false;
            }
        }, true);

        stage.addEventListener('touchstart', handleDragStart, { passive: true });
        stage.addEventListener('touchmove', handleDragMove, { passive: true });
        stage.addEventListener('touchend', handleDragEnd);

        stage.addEventListener('mousedown', handleDragStart);
        window.addEventListener('mousemove', handleDragMove);
        window.addEventListener('mouseup', handleDragEnd);

        // Keyboard Navigation
        window.addEventListener('keydown', (e) => {
            const rect = heroSection.getBoundingClientRect();
            const isInView = rect.top < window.innerHeight && rect.bottom > 0;
            if (!isInView) return;

            if (e.key === 'ArrowLeft') {
                setSlide(currentIndex - 1);
                startAutoplay();
            } else if (e.key === 'ArrowRight') {
                setSlide(currentIndex + 1);
                startAutoplay();
            }
        });

        // Initialize first slide
        setSlide(0, { instant: true });
        startAutoplay();
    };

    initHeroCarousel();

    // Initial render of cart badges on page load
    renderCartUI();
});
