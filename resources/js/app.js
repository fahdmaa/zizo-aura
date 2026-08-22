import '../css/app.css';

document.addEventListener('DOMContentLoaded', () => {
    // 1. Cart Management & Dynamic Badge
    let cartCount = 0;
    const cartBadge = document.getElementById('cart-count-badge');
    const toast = document.getElementById('app-toast');
    const toastMessage = document.getElementById('toast-message');

    const showToast = (message) => {
        if (!toast || !toastMessage) return;
        toastMessage.textContent = message;
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
        }, 2500);
    };

    // 2. Add to Cart Button Handlers with French Feedback
    document.querySelectorAll('.btn-card-pill').forEach(button => {
        button.addEventListener('click', (e) => {
            if (button.tagName.toLowerCase() === 'a') return; // Don't block navigation if it's a link
            if (button.id === 'product-add-cart-btn') return; // Handled by dedicated quantity listener
            e.preventDefault();
            const productName = button.dataset.productName || 'Produit';
            cartCount++;
            if (cartBadge) {
                cartBadge.textContent = cartCount;
                cartBadge.classList.add('scale-125');
                setTimeout(() => cartBadge.classList.remove('scale-125'), 300);
            }

            const originalHTML = button.innerHTML;
            button.style.backgroundColor = '#10b981';
            button.innerHTML = `<i class="ti ti-check text-base"></i><span>Ajouté ✓</span>`;
            button.disabled = true;

            showToast(`${productName} a été ajouté au panier !`);

            setTimeout(() => {
                button.style.backgroundColor = '#000000';
                button.innerHTML = originalHTML;
                button.disabled = false;
            }, 1800);
        });
    });

    // Product Page Quantity Selector & Dynamic Add to Cart
    const qtyMinusBtn = document.getElementById('qty-minus-btn');
    const qtyPlusBtn = document.getElementById('qty-plus-btn');
    const qtyInput = document.getElementById('product-quantity-input');
    const qtyDisplay = document.getElementById('product-quantity-display');
    const productAddCartBtn = document.getElementById('product-add-cart-btn');
    const btnCartText = document.getElementById('btn-cart-text');

    if (qtyMinusBtn && qtyPlusBtn && (qtyInput || qtyDisplay) && productAddCartBtn) {
        const unitPrice = parseFloat(productAddCartBtn.dataset.unitPrice) || 350;
        const productName = productAddCartBtn.dataset.productName || 'Produit';

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
            let current = parseInt(qtyInput ? qtyInput.value : qtyDisplay.textContent) || 1;
            if (current > 1) {
                updateQtyUI(current - 1);
            }
        });

        qtyPlusBtn.addEventListener('click', () => {
            let current = parseInt(qtyInput ? qtyInput.value : qtyDisplay.textContent) || 1;
            if (current < 20) {
                updateQtyUI(current + 1);
            }
        });

        productAddCartBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const qty = parseInt(qtyInput ? qtyInput.value : qtyDisplay.textContent) || 1;
            cartCount += qty;
            if (cartBadge) {
                cartBadge.textContent = cartCount;
                cartBadge.classList.add('scale-125');
                setTimeout(() => cartBadge.classList.remove('scale-125'), 300);
            }

            const origContent = productAddCartBtn.innerHTML;
            productAddCartBtn.style.backgroundColor = '#10b981';
            productAddCartBtn.innerHTML = `<i class="ti ti-check text-base sm:text-lg"></i><span>${qty} Ajouté${qty > 1 ? 's' : ''} ✓</span>`;
            productAddCartBtn.disabled = true;

            showToast(`${qty}x ${productName} ajouté${qty > 1 ? 's' : ''} au panier !`);

            setTimeout(() => {
                productAddCartBtn.style.backgroundColor = '#000000';
                productAddCartBtn.innerHTML = origContent;
                productAddCartBtn.disabled = false;
            }, 1800);
        });
    }

    // 3. Flavor Swatches Selection Switcher
    document.querySelectorAll('.product-card').forEach(card => {
        const swatches = card.querySelectorAll('.flavor-swatch');
        const flavorLabel = card.querySelector('.flavor-label');

        swatches.forEach(swatch => {
            swatch.addEventListener('click', (e) => {
                e.preventDefault();
                swatches.forEach(s => {
                    s.classList.remove('scale-110', 'ring-2', 'ring-pink-500');
                });
                swatch.classList.add('scale-110', 'ring-2', 'ring-pink-500');

                const name = swatch.dataset.flavorName;
                if (flavorLabel && name) {
                    flavorLabel.textContent = name;
                    flavorLabel.classList.add('text-pink-600');
                    setTimeout(() => flavorLabel.classList.remove('text-pink-600'), 400);
                }
            });
        });
    });

    // 4. Customer Reviews Carousel Slider Navigation
    const reviewsSlider = document.getElementById('reviews-slider');
    const prevBtn = document.getElementById('review-prev');
    const nextBtn = document.getElementById('review-next');

    if (reviewsSlider && prevBtn && nextBtn) {
        prevBtn.addEventListener('click', () => {
            reviewsSlider.scrollBy({ left: -380, behavior: 'smooth' });
        });

        nextBtn.addEventListener('click', () => {
            reviewsSlider.scrollBy({ left: 380, behavior: 'smooth' });
        });
    }

    // 5. Summer Discount Promo Modal Logic
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
        }, 700);

        if (closePromoBtn) {
            closePromoBtn.addEventListener('click', closePromoModal);
        }

        promoBackdrop.addEventListener('click', (e) => {
            if (e.target === promoBackdrop) {
                closePromoModal();
            }
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
                }, 2000);
            });
        });
    }

    // 6. Minimalist Inline Expanding Banner Search Bar
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
        searchPill.classList.add('w-44', 'sm:w-56', 'md:w-60', 'bg-zinc-100', 'border-zinc-200', 'shadow-2xs');
        navbarInput.classList.remove('w-0', 'opacity-0');
        navbarInput.classList.add('w-full', 'opacity-100');
        navbarInput.focus();
    };

    const collapseSearchBar = () => {
        if (!searchPill || !navbarInput) return;
        if (navbarInput.value.trim().length === 0) {
            searchPill.classList.remove('w-44', 'sm:w-56', 'md:w-60', 'bg-zinc-100', 'border-zinc-200', 'shadow-2xs');
            searchPill.classList.add('w-10', 'bg-transparent', 'border-transparent');
            navbarInput.classList.remove('w-full', 'opacity-100');
            navbarInput.classList.add('w-0', 'opacity-0');
            if (clearBtn) clearBtn.classList.add('hidden');
        }
        if (suggestionsBox) {
            suggestionsBox.classList.add('hidden');
        }
    };

    // Expand when clicking the search icon
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

    // Auto-expand if on page with query prefilled
    if (navbarInput && navbarInput.value.trim().length > 0) {
        expandSearchBar();
        if (clearBtn) clearBtn.classList.remove('hidden');
    }

    // Live instant suggestions fetch
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

    // Collapse on click outside
    document.addEventListener('click', (e) => {
        if (searchWrapper && !searchWrapper.contains(e.target)) {
            collapseSearchBar();
        }
    });

    // Collapse on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            collapseSearchBar();
        }
    });

    // 7. Scroll Reveal Appearing Animations (Shopping Page & Elements)
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

    // 8. Contact Page Themed Subject Dropdown
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

                // Update UI active classes
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

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeSubjectDropdown();
            }
        });
    }
});
