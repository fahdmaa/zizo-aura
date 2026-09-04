/**
 * Zizo Aura — Admin Single Page Application & API Controller
 * Consumes:
 *   - GET /api/admin/dashboard
 *   - GET, POST, PUT, DELETE, RESTORE /api/admin/products
 *   - GET, POST, PUT, DELETE /api/admin/categories
 *   - GET, POST, PUT, TOGGLE, DELETE /api/admin/coupons
 *   - GET, PATCH /api/admin/orders
 *   - GET, PATCH /api/admin/messages
 */

(function () {
    'use strict';

    // ─── API Client ──────────────────────────────────────────────────────────
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const api = {
        async request(endpoint, options = {}) {
            const defaultHeaders = {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            };

            const config = {
                credentials: 'same-origin',
                ...options,
                headers: {
                    ...defaultHeaders,
                    ...(options.headers || {}),
                },
            };

            if (options.body && typeof options.body === 'object') {
                config.body = JSON.stringify(options.body);
            }

            try {
                const response = await fetch(endpoint, config);
                if (response.status === 204) {
                    return null;
                }
                if (response.status === 401) {
                    window.location.href = '/admin/login';
                    return null;
                }
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    const message = data.message || (data.errors ? Object.values(data.errors).flat().join(' ') : 'Une erreur est survenue.');
                    const error = new Error(message);
                    error.status = response.status;
                    error.data = data;
                    throw error;
                }
                return data;
            } catch (err) {
                console.error(`[API Error] ${endpoint}:`, err);
                throw err;
            }
        },

        get(endpoint) {
            return this.request(endpoint, { method: 'GET' });
        },

        post(endpoint, body = {}) {
            return this.request(endpoint, { method: 'POST', body });
        },

        put(endpoint, body = {}) {
            return this.request(endpoint, { method: 'PUT', body });
        },

        patch(endpoint, body = {}) {
            return this.request(endpoint, { method: 'PATCH', body });
        },

        delete(endpoint) {
            return this.request(endpoint, { method: 'DELETE' });
        },
    };

    // ─── Toast System ────────────────────────────────────────────────────────
    const toast = {
        container: null,
        init() {
            this.container = document.getElementById('admin-toast-container');
            if (!this.container) {
                this.container = document.createElement('div');
                this.container.id = 'admin-toast-container';
                document.body.appendChild(this.container);
            }
            this.container.className = 'fixed bottom-24 sm:bottom-28 left-1/2 -translate-x-1/2 z-50 flex flex-col items-center gap-2 pointer-events-none w-max max-w-[90vw]';
        },
        show(message, type = 'success') {
            this.init();
            const el = document.createElement('div');
            el.className = `pointer-events-auto transform translate-y-2 opacity-0 transition-all duration-250 ease-out inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full shadow-lg border text-xs font-semibold whitespace-nowrap ${
                type === 'error'
                    ? 'bg-zinc-950/95 backdrop-blur-md text-red-200 border-red-800/80 shadow-red-950/20'
                    : type === 'info'
                    ? 'bg-zinc-950/95 backdrop-blur-md text-sky-200 border-sky-800/80 shadow-sky-950/20'
                    : 'bg-zinc-950/95 backdrop-blur-md text-white border-pink-500/40 shadow-pink-950/20'
            }`;

            const iconSvg = type === 'error'
                ? '<i class="ti ti-alert-circle text-rose-400 text-sm shrink-0"></i>'
                : type === 'info'
                ? '<i class="ti ti-info-circle text-sky-400 text-sm shrink-0"></i>'
                : '<i class="ti ti-check text-pink-400 text-sm shrink-0"></i>';

            el.innerHTML = `
                ${iconSvg}
                <span class="leading-none">${message}</span>
            `;

            this.container.appendChild(el);

            // Animate in
            requestAnimationFrame(() => {
                el.classList.remove('translate-y-2', 'opacity-0');
            });

            setTimeout(() => {
                if (el.parentNode) {
                    el.classList.add('opacity-0', 'translate-y-1', 'scale-95');
                    setTimeout(() => el.remove(), 250);
                }
            }, 2500);
        }
    };

    // ─── Modal / Drawer System ───────────────────────────────────────────────
    const modalSystem = {
        container: null,
        drawerContainer: null,
        init() {
            this.container = document.getElementById('admin-modal-root');
            this.drawerContainer = document.getElementById('admin-drawer-root');
            if (!this.container) {
                this.container = document.createElement('div');
                this.container.id = 'admin-modal-root';
                document.body.appendChild(this.container);
            }
            if (!this.drawerContainer) {
                this.drawerContainer = document.createElement('div');
                this.drawerContainer.id = 'admin-drawer-root';
                document.body.appendChild(this.drawerContainer);
            }

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    this.closeModal();
                    this.closeDrawer();
                }
            });
        },

        openModal(htmlContent, { maxWidth = 'max-w-xl', onMount = null } = {}) {
            this.init();
            this.container.innerHTML = `
                <div id="admin-modal-backdrop" class="fixed inset-0 z-40 bg-zinc-950/60 backdrop-blur-xs flex items-center justify-center p-4 transition-opacity duration-200 opacity-0">
                    <div id="admin-modal-box" class="w-full ${maxWidth} bg-white rounded-3xl shadow-2xl border border-zinc-100 overflow-hidden transform scale-95 transition-all duration-200 max-h-[92vh] flex flex-col">
                        ${htmlContent}
                    </div>
                </div>
            `;
            document.body.classList.add('overflow-hidden');

            const backdrop = document.getElementById('admin-modal-backdrop');
            const box = document.getElementById('admin-modal-box');

            requestAnimationFrame(() => {
                backdrop.classList.remove('opacity-0');
                box.classList.remove('scale-95');
                box.classList.add('scale-100');
            });

            backdrop.addEventListener('click', (e) => {
                if (e.target === backdrop) this.closeModal();
            });

            const closeBtns = box.querySelectorAll('[data-close-modal]');
            closeBtns.forEach(btn => btn.addEventListener('click', () => this.closeModal()));

            if (typeof onMount === 'function') {
                onMount(box);
            }
        },

        closeModal() {
            const backdrop = document.getElementById('admin-modal-backdrop');
            const box = document.getElementById('admin-modal-box');
            if (!backdrop || !box) return;

            backdrop.classList.add('opacity-0');
            box.classList.remove('scale-100');
            box.classList.add('scale-95');
            setTimeout(() => {
                if (this.container) this.container.innerHTML = '';
                document.body.classList.remove('overflow-hidden');
            }, 200);
        },

        openDrawer(htmlContent, { maxWidth = 'max-w-2xl', onMount = null } = {}) {
            this.init();
            this.drawerContainer.innerHTML = `
                <div id="admin-drawer-backdrop" class="fixed inset-0 z-40 bg-zinc-950/50 backdrop-blur-xs flex justify-end transition-opacity duration-300 opacity-0">
                    <div id="admin-drawer-panel" class="w-full ${maxWidth} bg-white shadow-2xl h-full flex flex-col transform translate-x-full transition-transform duration-300 ease-out border-l border-zinc-200">
                        ${htmlContent}
                    </div>
                </div>
            `;
            document.body.classList.add('overflow-hidden');

            const backdrop = document.getElementById('admin-drawer-backdrop');
            const panel = document.getElementById('admin-drawer-panel');

            requestAnimationFrame(() => {
                backdrop.classList.remove('opacity-0');
                panel.classList.remove('translate-x-full');
            });

            backdrop.addEventListener('click', (e) => {
                if (e.target === backdrop) this.closeDrawer();
            });

            const closeBtns = panel.querySelectorAll('[data-close-drawer]');
            closeBtns.forEach(btn => btn.addEventListener('click', () => this.closeDrawer()));

            if (typeof onMount === 'function') {
                onMount(panel);
            }
        },

        closeDrawer() {
            const backdrop = document.getElementById('admin-drawer-backdrop');
            const panel = document.getElementById('admin-drawer-panel');
            if (!backdrop || !panel) return;

            backdrop.classList.add('opacity-0');
            panel.classList.add('translate-x-full');
            setTimeout(() => {
                if (this.drawerContainer) this.drawerContainer.innerHTML = '';
                document.body.classList.remove('overflow-hidden');
            }, 300);
        },

        confirm({ title = 'Confirmer l\'action', message = 'Êtes-vous sûr de vouloir continuer ?', confirmText = 'Confirmer', cancelText = 'Annuler', type = 'danger', onConfirm }) {
            const isDanger = type === 'danger';
            const html = `
                <div class="p-6 md:p-7">
                    <div class="w-12 h-12 rounded-2xl ${isDanger ? 'bg-red-50 text-red-600' : 'bg-pink-50 text-pink-600'} flex items-center justify-center text-2xl mb-4">
                        <i class="ti ${isDanger ? 'ti-alert-triangle' : 'ti-help'}"></i>
                    </div>
                    <h3 class="text-lg font-bold text-zinc-900">${title}</h3>
                    <p class="text-sm text-zinc-500 mt-2 leading-relaxed">${message}</p>
                    <div class="mt-6 flex items-center justify-end gap-3">
                        <button type="button" data-close-modal class="px-4 py-2.5 rounded-xl border border-zinc-200 text-xs font-bold text-zinc-700 hover:bg-zinc-50 transition cursor-pointer">
                            ${cancelText}
                        </button>
                        <button type="button" id="admin-confirm-btn" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white transition shadow-sm cursor-pointer ${
                            isDanger ? 'bg-red-600 hover:bg-red-700' : 'bg-pink-600 hover:bg-pink-700'
                        }">
                            ${confirmText}
                        </button>
                    </div>
                </div>
            `;

            this.openModal(html, {
                maxWidth: 'max-w-md',
                onMount: (box) => {
                    box.querySelector('#admin-confirm-btn')?.addEventListener('click', async () => {
                        const btn = box.querySelector('#admin-confirm-btn');
                        btn.disabled = true;
                        btn.innerHTML = '<i class="ti ti-loader-2 animate-spin mr-1"></i> Traitement...';
                        try {
                            if (typeof onConfirm === 'function') {
                                await onConfirm();
                            }
                            modalSystem.closeModal();
                        } catch (err) {
                            toast.show(err.message || 'Erreur', 'error');
                            btn.disabled = false;
                            btn.textContent = confirmText;
                        }
                    });
                }
            });
        }
    };

    // ─── Image Processing Helpers ─────────────────────────────────────────────
    function readFileAsCompressedDataUrl(file, maxWidth = 800, quality = 0.80) {
        return new Promise((resolve, reject) => {
            if (!file || !file.type.startsWith('image/')) {
                return reject(new Error('Le fichier sélectionné n\'est pas une image valide.'));
            }
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = new Image();
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;

                    if (width > maxWidth || height > maxWidth) {
                        if (width > height) {
                            height = Math.round((height * maxWidth) / width);
                            width = maxWidth;
                        } else {
                            width = Math.round((width * maxWidth) / height);
                            height = maxWidth;
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    
                    // Fill white background for clean JPEG export
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, width, height);
                    ctx.drawImage(img, 0, 0, width, height);

                    const dataUrl = canvas.toDataURL('image/jpeg', quality);
                    resolve(dataUrl);
                };
                img.onerror = () => reject(new Error('Impossible de charger l\'image.'));
                img.src = e.target.result;
            };
            reader.onerror = () => reject(new Error('Erreur lors de la lecture du fichier.'));
            reader.readAsDataURL(file);
        });
    }

    // ─── Number / Currency Helpers ───────────────────────────────────────────
    const formatDH = (num) => {
        const val = parseFloat(num) || 0;
        return new Intl.NumberFormat('fr-FR', {
            maximumFractionDigits: 2,
            minimumFractionDigits: 0
        }).format(val) + ' DH';
    };

    const escapeHtml = (str) => {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    };

    const formatDate = (dateStr) => {
        if (!dateStr) return '—';
        try {
            const d = new Date(dateStr);
            return d.toLocaleDateString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        } catch {
            return dateStr;
        }
    };

    // ─── Status Badges ───────────────────────────────────────────────────────
    const orderStatusBadge = (status) => {
        const map = {
            pending: { label: 'En attente', bg: 'bg-amber-50 text-amber-700 border-amber-200/80', dot: 'bg-amber-500' },
            confirmed: { label: 'Confirmée', bg: 'bg-sky-50 text-sky-700 border-sky-200/80', dot: 'bg-sky-500' },
            processing: { label: 'En préparation', bg: 'bg-indigo-50 text-indigo-700 border-indigo-200/80', dot: 'bg-indigo-500' },
            shipped: { label: 'Expédiée', bg: 'bg-purple-50 text-purple-700 border-purple-200/80', dot: 'bg-purple-500' },
            delivered: { label: 'Livrée', bg: 'bg-emerald-50 text-emerald-700 border-emerald-200/80', dot: 'bg-emerald-500' },
            cancelled: { label: 'Annulée', bg: 'bg-rose-50 text-rose-700 border-rose-200/80', dot: 'bg-rose-500' },
        };
        const conf = map[status] || { label: status, bg: 'bg-zinc-100 text-zinc-700 border-zinc-200', dot: 'bg-zinc-400' };
        return `
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border ${conf.bg}">
                <span class="w-1.5 h-1.5 rounded-full ${conf.dot}"></span>
                ${conf.label}
            </span>
        `;
    };

    // ─── Application State ───────────────────────────────────────────────────
    const appState = {
        currentView: 'dashboard',
        categoriesCache: [],
        dashboardData: null,
        productsData: null,
        productsFilter: { search: '', category_id: '', status: '', page: 1 },
        categoriesData: null,
        couponsData: null,
        ordersData: null,
        ordersFilter: { search: '', status: '', page: 1 },
        messagesData: null,
        messagesFilter: { page: 1, filter: 'all' },
        reviewsData: null,
        reviewsFilter: { search: '', status: '' },
        unreadMessagesCount: 0,
        pendingOrdersCount: 0,
    };

    // ─── View Controller ─────────────────────────────────────────────────────
    const app = {
        root: null,

        async init() {
            this.root = document.getElementById('admin-app-root');
            if (!this.root) return;

            modalSystem.init();
            toast.init();

            // Mobile menu drawer
            const mobileMenuBtn = document.getElementById('admin-mobile-toggle');
            const sidebar = document.getElementById('admin-sidebar');
            const sidebarBackdrop = document.getElementById('admin-sidebar-backdrop');
            const closeSidebarBtn = document.getElementById('admin-sidebar-close');

            if (mobileMenuBtn && sidebar && sidebarBackdrop) {
                mobileMenuBtn.addEventListener('click', () => {
                    sidebar.classList.remove('-translate-x-full');
                    sidebarBackdrop.classList.remove('opacity-0', 'pointer-events-none');
                });

                const closeMenu = () => {
                    sidebar.classList.add('-translate-x-full');
                    sidebarBackdrop.classList.add('opacity-0', 'pointer-events-none');
                };

                if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeMenu);
                sidebarBackdrop.addEventListener('click', closeMenu);
            }

            // Global click listener to close custom floating dropdowns
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.custom-admin-dropdown') && !e.target.closest('.custom-form-dropdown')) {
                    document.querySelectorAll('.custom-dropdown-panel').forEach(p => p.classList.add('hidden'));
                    document.querySelectorAll('.custom-admin-dropdown .chevron-icon, .custom-form-dropdown .chevron-icon').forEach(c => c.classList.remove('rotate-180'));
                }
            });

            // Router hash listener
            window.addEventListener('hashchange', () => this.handleHashChange());

            // Preload categories before initial route render
            await this.ensureCategoriesLoaded();
            this.handleHashChange();

            // Load global notification badges (messages & orders)
            this.refreshGlobalBadges();

            // Periodic live polling every 20 seconds
            setInterval(() => {
                this.refreshGlobalBadges();
                if (appState.currentView === 'messages') {
                    this.loadMessagesList();
                }
            }, 20000);
        },

        async refresh() {
            const btn = document.getElementById('admin-refresh-btn');
            const icon = btn?.querySelector('.ti-refresh');
            if (icon) icon.classList.add('animate-spin');

            try {
                // 1. Force refresh categories cache
                await this.ensureCategoriesLoaded(true);

                // 2. Refresh unread messages and pending orders badges
                await this.refreshGlobalBadges();

                // 3. Re-render current active view with fresh data
                const hash = window.location.hash.replace('#', '') || 'dashboard';
                const [view, param] = hash.split('/');
                await this.navigate(view || 'dashboard', param);

                showToast('Données et messages actualisés');
            } catch (err) {
                console.error('Refresh error', err);
                showToast('Erreur lors de l\'actualisation', 'error');
            } finally {
                if (icon) {
                    setTimeout(() => icon.classList.remove('animate-spin'), 600);
                }
            }
        },

        async preloadCategories() {
            try {
                const cats = await api.get('/api/admin/categories');
                appState.categoriesCache = Array.isArray(cats) ? cats : (cats?.data || []);
            } catch (err) {
                console.warn('Could not preload categories', err);
            }
        },

        async ensureCategoriesLoaded(force = false) {
            if (force || !appState.categoriesCache || appState.categoriesCache.length === 0) {
                await this.preloadCategories();
            }
            return appState.categoriesCache;
        },

        handleHashChange() {
            const hash = window.location.hash.replace('#', '') || 'dashboard';
            const [view, param] = hash.split('/');
            this.navigate(view || 'dashboard', param);
        },

        updateSidebarActive(view) {
            // Update bottom dock tabs
            document.querySelectorAll('.admin-dock-tab').forEach(el => {
                const target = el.dataset.view;
                if (target === view) {
                    el.classList.add('active');
                } else {
                    el.classList.remove('active');
                }
            });
        },

        async navigate(view, param = null) {
            appState.currentView = view;
            this.updateSidebarActive(view);

            switch (view) {
                case 'dashboard':
                    await this.renderDashboard();
                    break;
                case 'products':
                    if (param === 'create') {
                        await this.renderProducts();
                        this.openProductEditor(null);
                    } else if (param) {
                        await this.renderProducts();
                        this.openProductEditor(param);
                    } else {
                        await this.renderProducts();
                    }
                    break;
                case 'categories':
                    await this.renderCategories();
                    break;
                case 'discounts':
                case 'coupons':
                    await this.renderDiscounts();
                    break;
                case 'orders':
                    if (param) {
                        await this.renderOrders();
                        this.openOrderDetail(param);
                    } else {
                        await this.renderOrders();
                    }
                    break;
                case 'messages':
                    await this.renderMessages();
                    break;
                case 'reviews':
                    if (param === 'create') {
                        await this.renderReviews();
                        this.openReviewEditor(null);
                    } else if (param) {
                        await this.renderReviews();
                        this.openReviewEditorById(param);
                    } else {
                        await this.renderReviews();
                    }
                    break;
                default:
                    await this.renderDashboard();
            }
        },

        // ═════════════════════════════════════════════════════════════════════
        // VIEW 1: DASHBOARD
        // ═════════════════════════════════════════════════════════════════════
        async renderDashboard() {
            this.root.innerHTML = `
                <div class="flex items-center justify-center py-24">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-10 h-10 border-3 border-pink-500/20 border-t-pink-500 rounded-full animate-spin"></div>
                        <span class="text-xs font-bold text-zinc-400">Chargement du tableau de bord...</span>
                    </div>
                </div>
            `;

            try {
                const data = await api.get('/api/admin/dashboard');
                appState.dashboardData = data;
                const stats = data.stats || {};
                const recentOrders = data.recent_orders || [];

                // Update badge in sidebar for unread messages & pending orders
                this.updateSidebarBadges(stats.pending_orders, stats.unread_messages);

                this.root.innerHTML = `
                    <div class="space-y-8 animate-fadeIn">
                        <!-- Top Greeting Banner -->
                        <div class="bg-white rounded-3xl p-6 md:p-8 border border-zinc-100 shadow-sm relative overflow-hidden">
                            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                                <div>
                                    <h1 class="text-2xl md:text-3xl font-black tracking-tight text-zinc-900">Tableau de bord Zizo Aura</h1>
                                    <p class="text-zinc-500 text-xs md:text-sm mt-1 max-w-xl font-medium">
                                        Vue d'ensemble de vos ventes, commandes en direct et gestion du catalogue cosmétique.
                                    </p>
                                </div>
                                <div class="flex flex-wrap items-center gap-3">
                                    <button type="button" id="dash-quick-prod" class="btn-pill-primary btn-pill-sm cursor-pointer">
                                        <i class="ti ti-plus text-sm"></i>
                                        <span>Nouveau produit</span>
                                    </button>
                                    <button type="button" id="dash-quick-coupon" class="btn-pill-secondary btn-pill-sm cursor-pointer">
                                        <i class="ti ti-ticket text-sm"></i>
                                        <span>Nouveau code promo</span>
                                    </button>
                                    <button type="button" id="dash-quick-review" class="btn-pill-secondary btn-pill-sm cursor-pointer">
                                        <i class="ti ti-star text-sm"></i>
                                        <span>Ajouter un avis</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- KPI Cards Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-7 gap-4">
                            <!-- KPI: Revenue -->
                            <div class="bg-white rounded-3xl p-5 border border-zinc-100 shadow-sm flex flex-col justify-between hover:border-pink-200 transition-all group">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Chiffre d'Affaires</span>
                                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                                        <i class="ti ti-wallet"></i>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="text-xl md:text-2xl font-black text-zinc-900 tracking-tight">${formatDH(stats.revenue)}</div>
                                    <span class="text-[10px] text-zinc-400 font-medium">Commandes validées</span>
                                </div>
                            </div>

                            <!-- KPI: Pending Orders -->
                            <div class="bg-white rounded-3xl p-5 border border-zinc-100 shadow-sm flex flex-col justify-between hover:border-amber-200 transition-all group cursor-pointer" onclick="window.location.hash='#orders'">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-wider">En attente</span>
                                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                                        <i class="ti ti-clock-hour-4"></i>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="text-xl md:text-2xl font-black text-amber-600 tracking-tight">${stats.pending_orders || 0}</div>
                                    <span class="text-[10px] text-zinc-400 font-medium">À traiter d'urgence</span>
                                </div>
                            </div>

                            <!-- KPI: Total Orders -->
                            <div class="bg-white rounded-3xl p-5 border border-zinc-100 shadow-sm flex flex-col justify-between hover:border-pink-200 transition-all group cursor-pointer" onclick="window.location.hash='#orders'">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Total Commandes</span>
                                    <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                                        <i class="ti ti-shopping-bag"></i>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="text-xl md:text-2xl font-black text-zinc-900 tracking-tight">${stats.orders || 0}</div>
                                    <span class="text-[10px] text-zinc-400 font-medium">Historique complet</span>
                                </div>
                            </div>

                            <!-- KPI: Products -->
                            <div class="bg-white rounded-3xl p-5 border border-zinc-100 shadow-sm flex flex-col justify-between hover:border-pink-200 transition-all group cursor-pointer" onclick="window.location.hash='#products'">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Produits</span>
                                    <div class="w-8 h-8 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                                        <i class="ti ti-package"></i>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="text-xl md:text-2xl font-black text-zinc-900 tracking-tight">${stats.products || 0}</div>
                                    <span class="text-[10px] text-zinc-400 font-medium">Au catalogue</span>
                                </div>
                            </div>

                            <!-- KPI: Active Coupons -->
                            <div class="bg-white rounded-3xl p-5 border border-zinc-100 shadow-sm flex flex-col justify-between hover:border-purple-200 transition-all group cursor-pointer" onclick="window.location.hash='#discounts'">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Codes Promo</span>
                                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                                        <i class="ti ti-ticket"></i>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="text-xl md:text-2xl font-black text-zinc-900 tracking-tight">${stats.active_coupons || 0}</div>
                                    <span class="text-[10px] text-zinc-400 font-medium">Actifs & valides</span>
                                </div>
                            </div>

                            <!-- KPI: Customer Reviews -->
                            <div class="bg-white rounded-3xl p-5 border border-zinc-100 shadow-sm flex flex-col justify-between hover:border-pink-200 transition-all group cursor-pointer" onclick="window.location.hash='#reviews'">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Avis Clients</span>
                                    <div class="w-8 h-8 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                                        <i class="ti ti-star"></i>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="text-xl md:text-2xl font-black text-zinc-900 tracking-tight">${stats.total_reviews || 0}</div>
                                    <span class="text-[10px] text-zinc-400 font-medium">${stats.visible_reviews || 0} visibles en vitrine</span>
                                </div>
                            </div>

                            <!-- KPI: Unread Messages -->
                            <div class="bg-white rounded-3xl p-5 border border-zinc-100 shadow-sm flex flex-col justify-between hover:border-rose-200 transition-all group cursor-pointer" onclick="window.location.hash='#messages'">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Messages</span>
                                    <div class="w-8 h-8 rounded-xl ${stats.unread_messages > 0 ? 'bg-rose-50 text-rose-600 animate-pulse' : 'bg-zinc-100 text-zinc-500'} flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                                        <i class="ti ti-mail"></i>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="text-xl md:text-2xl font-black ${stats.unread_messages > 0 ? 'text-rose-600' : 'text-zinc-900'} tracking-tight">${stats.unread_messages || 0}</div>
                                    <span class="text-[10px] text-zinc-400 font-medium">Non lus</span>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Orders Section -->
                        <div class="bg-white rounded-3xl border border-zinc-100 shadow-sm overflow-hidden">
                            <div class="px-6 py-5 border-b border-zinc-100 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center">
                                        <i class="ti ti-truck-delivery text-lg"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-base font-bold text-zinc-900">Dernières commandes reçues</h2>
                                        <p class="text-xs text-zinc-400">Les 5 plus récentes commandes passées sur la boutique</p>
                                    </div>
                                </div>
                                <a href="#orders" class="text-xs font-bold text-pink-600 hover:text-pink-700 flex items-center gap-1">
                                    <span>Toutes les commandes</span>
                                    <i class="ti ti-arrow-right text-xs"></i>
                                </a>
                            </div>

                            ${recentOrders.length === 0 ? `
                                <div class="py-16 text-center">
                                    <div class="w-14 h-14 rounded-2xl bg-zinc-50 text-zinc-300 flex items-center justify-center mx-auto text-2xl mb-3">
                                        <i class="ti ti-shopping-bag"></i>
                                    </div>
                                    <p class="text-sm font-bold text-zinc-600">Aucune commande enregistrée</p>
                                    <p class="text-xs text-zinc-400 mt-1">Les futures commandes s'afficheront ici en temps réel.</p>
                                </div>
                            ` : `
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left text-xs">
                                        <thead class="bg-zinc-50/80 text-zinc-400 font-bold uppercase tracking-wider text-[10px] border-b border-zinc-100">
                                            <tr>
                                                <th class="px-6 py-3.5">N° Commande</th>
                                                <th class="px-6 py-3.5">Client & Contact</th>
                                                <th class="px-6 py-3.5">Articles</th>
                                                <th class="px-6 py-3.5">Total</th>
                                                <th class="px-6 py-3.5">Statut</th>
                                                <th class="px-6 py-3.5">Date</th>
                                                <th class="px-6 py-3.5 text-right">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-zinc-100">
                                            ${recentOrders.map(order => `
                                                <tr class="hover:bg-pink-50/30 transition-colors group">
                                                    <td class="px-6 py-4 font-mono font-bold text-zinc-900">#${order.id}</td>
                                                    <td class="px-6 py-4">
                                                        <div class="font-bold text-zinc-900">${order.customer_name}</div>
                                                        <div class="text-[11px] text-zinc-400 font-medium">${order.customer_phone} • ${order.city || 'Maroc'}</div>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <span class="inline-flex items-center gap-1 font-semibold text-zinc-600">
                                                            <i class="ti ti-package text-zinc-400"></i>
                                                            ${order.items?.length || 0} article(s)
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 font-black text-zinc-900">${formatDH(order.total)}</td>
                                                    <td class="px-6 py-4">${orderStatusBadge(order.status)}</td>
                                                    <td class="px-6 py-4 text-zinc-400">${formatDate(order.created_at)}</td>
                                                    <td class="px-6 py-4 text-right">
                                                        <button type="button" onclick="window.location.hash='#orders/${order.id}'" class="px-3 py-1.5 rounded-xl bg-zinc-100 hover:bg-pink-600 hover:text-white text-zinc-700 font-bold text-xs transition cursor-pointer">
                                                            Détail
                                                        </button>
                                                    </td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            `}
                        </div>
                    </div>
                `;

                // Wire action buttons
                document.getElementById('dash-quick-prod')?.addEventListener('click', () => {
                    window.location.hash = '#products/create';
                });
                document.getElementById('dash-quick-coupon')?.addEventListener('click', () => {
                    window.location.hash = '#discounts';
                });
                document.getElementById('dash-quick-review')?.addEventListener('click', () => {
                    window.location.hash = '#reviews/create';
                });

            } catch (err) {
                this.root.innerHTML = `
                    <div class="bg-red-50 border border-red-200 rounded-3xl p-8 text-center max-w-lg mx-auto mt-12">
                        <div class="w-12 h-12 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center mx-auto text-2xl mb-3">
                            <i class="ti ti-alert-circle"></i>
                        </div>
                        <h3 class="text-base font-bold text-red-900">Impossible de charger le tableau de bord</h3>
                        <p class="text-xs text-red-600 mt-1">${err.message}</p>
                        <button type="button" onclick="window.adminApp.renderDashboard()" class="mt-4 px-4 py-2 rounded-xl bg-red-600 text-white text-xs font-bold hover:bg-red-700 transition">
                            Réessayer
                        </button>
                    </div>
                `;
            }
        },

        updateSidebarBadges(pendingOrders, unreadMessages) {
            if (pendingOrders !== undefined && pendingOrders !== null) {
                appState.pendingOrdersCount = parseInt(pendingOrders, 10) || 0;
            }
            if (unreadMessages !== undefined && unreadMessages !== null) {
                appState.unreadMessagesCount = parseInt(unreadMessages, 10) || 0;
            }

            const unreadCount = appState.unreadMessagesCount ?? 0;
            const pendingCount = appState.pendingOrdersCount ?? 0;

            // Dock Messages Badge (small red circle on top right of messages icon)
            const dockUnread = document.getElementById('dock-unread-badge');
            if (dockUnread) {
                if (unreadCount > 0) {
                    dockUnread.textContent = unreadCount > 99 ? '99+' : unreadCount;
                    dockUnread.classList.remove('hidden');
                } else {
                    dockUnread.classList.add('hidden');
                }
            }

            // Header Messages Badge
            const headerUnread = document.getElementById('header-unread-badge');
            if (headerUnread) {
                if (unreadCount > 0) {
                    headerUnread.textContent = unreadCount > 99 ? '99+' : unreadCount;
                    headerUnread.classList.remove('hidden');
                } else {
                    headerUnread.classList.add('hidden');
                }
            }

            // Dock Orders Badge
            const dockPending = document.getElementById('dock-pending-badge');
            if (dockPending) {
                if (pendingCount > 0) {
                    dockPending.textContent = pendingCount > 99 ? '99+' : pendingCount;
                    dockPending.classList.remove('hidden');
                } else {
                    dockPending.classList.add('hidden');
                }
            }
        },

        async refreshGlobalBadges() {
            try {
                const data = await api.get('/api/admin/dashboard');
                if (data?.stats) {
                    this.updateSidebarBadges(data.stats.pending_orders, data.stats.unread_messages);
                }
            } catch (err) {
                console.warn('Could not refresh global notification badges', err);
            }
        },

        // ═════════════════════════════════════════════════════════════════════
        // VIEW 2: PRODUCTS
        // ═════════════════════════════════════════════════════════════════════
        async renderProducts(page = 1) {
            await this.ensureCategoriesLoaded();
            appState.productsFilter.page = page;
            const filter = appState.productsFilter;
            const activeCat = appState.categoriesCache.find(c => String(c.id) === String(filter.category_id));
            const catLabel = activeCat ? activeCat.name : 'Toutes les catégories';

            const statusLabels = {
                '': 'Tous les statuts',
                'active': 'Actifs',
                'inactive': 'Inactifs',
                'deleted': 'Archivés'
            };
            const statusLabel = statusLabels[filter.status] || 'Tous les statuts';

            this.root.innerHTML = `
                <div class="space-y-6 animate-fadeIn">
                    <!-- Products Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-black text-zinc-900 tracking-tight">Catalogue Produits</h1>
                            <p class="text-xs text-zinc-400 font-medium">Gérez vos formules, variantes olfactives, stocks et tarifs</p>
                        </div>
                        <button type="button" id="btn-create-product" class="btn-pill-primary btn-pill-sm shrink-0">
                            <i class="ti ti-plus text-sm"></i>
                            <span>Nouveau produit</span>
                        </button>
                    </div>

                    <!-- Search & Filter Controls -->
                    <div class="bg-white rounded-3xl p-4 border border-zinc-100 shadow-sm flex flex-col md:flex-row items-center gap-3">
                        <div class="admin-search-container ${filter.search ? 'is-expanded' : ''} flex-1 w-full">
                            <i class="ti ti-search admin-search-icon"></i>
                            <input type="text" id="prod-search-input" value="${filter.search || ''}" placeholder="Rechercher par nom de formule, fragrance..." class="admin-search-input" autocomplete="off" />
                            <button type="button" id="prod-search-clear" class="admin-search-clear-btn ${filter.search ? '' : 'hidden'}" title="Effacer la recherche">
                                <i class="ti ti-x text-xs"></i>
                            </button>
                        </div>
                        <div class="flex items-center gap-2.5 w-full md:w-auto shrink-0 flex-wrap sm:flex-nowrap">
                            <!-- Category Custom Dropdown -->
                            <div class="relative custom-admin-dropdown" id="prod-cat-dropdown-wrap">
                                <button type="button" class="btn-pill-secondary btn-pill-sm cursor-pointer flex items-center justify-between gap-2.5 select-none" id="prod-cat-trigger">
                                    <span class="truncate max-w-[140px] text-zinc-900 font-bold">${catLabel}</span>
                                    <i class="ti ti-chevron-down text-xs text-zinc-400 transition-transform duration-200 chevron-icon"></i>
                                </button>
                                <div class="custom-dropdown-panel absolute left-0 sm:right-0 sm:left-auto top-full mt-2 min-w-[210px] w-max max-w-xs bg-white/95 backdrop-blur-md rounded-2xl shadow-[0_20px_45px_rgba(0,0,0,0.14)] border border-zinc-100 p-1.5 z-50 hidden max-h-64 overflow-y-auto">
                                    <button type="button" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all cursor-pointer ${filter.category_id === '' ? 'bg-pink-50 text-pink-600' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black'}" data-cat-id="">
                                        <span>Toutes les catégories</span>
                                        ${filter.category_id === '' ? '<i class="ti ti-check text-xs"></i>' : ''}
                                    </button>
                                    ${appState.categoriesCache.map(cat => `
                                        <button type="button" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all cursor-pointer ${String(filter.category_id) === String(cat.id) ? 'bg-pink-50 text-pink-600' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black'}" data-cat-id="${cat.id}">
                                            <span class="truncate text-left">${cat.name}</span>
                                            ${String(filter.category_id) === String(cat.id) ? '<i class="ti ti-check text-xs shrink-0 ml-2"></i>' : ''}
                                        </button>
                                    `).join('')}
                                </div>
                            </div>

                            <!-- Status Custom Dropdown -->
                            <div class="relative custom-admin-dropdown" id="prod-status-dropdown-wrap">
                                <button type="button" class="btn-pill-secondary btn-pill-sm cursor-pointer flex items-center justify-between gap-2.5 select-none" id="prod-status-trigger">
                                    <span class="text-zinc-900 font-bold">${statusLabel}</span>
                                    <i class="ti ti-chevron-down text-xs text-zinc-400 transition-transform duration-200 chevron-icon"></i>
                                </button>
                                <div class="custom-dropdown-panel absolute right-0 top-full mt-2 min-w-[170px] bg-white/95 backdrop-blur-md rounded-2xl shadow-[0_20px_45px_rgba(0,0,0,0.14)] border border-zinc-100 p-1.5 z-50 hidden">
                                    ${[
                                        ['', 'Tous les statuts'],
                                        ['active', 'Actifs'],
                                        ['inactive', 'Inactifs'],
                                        ['deleted', 'Archivés']
                                    ].map(([val, lbl]) => `
                                        <button type="button" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all cursor-pointer ${filter.status === val ? 'bg-pink-50 text-pink-600' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black'}" data-status-val="${val}">
                                            <span>${lbl}</span>
                                            ${filter.status === val ? '<i class="ti ti-check text-xs"></i>' : ''}
                                        </button>
                                    `).join('')}
                                </div>
                            </div>

                            ${(filter.search || filter.category_id || filter.status) ? `
                                <button type="button" id="prod-reset-filters" class="btn-circle-action w-9 h-9 rounded-full bg-zinc-100 hover:bg-zinc-200 text-zinc-600 text-xs transition cursor-pointer shrink-0" title="Réinitialiser les filtres">
                                    <i class="ti ti-x text-sm"></i>
                                </button>
                            ` : ''}
                        </div>
                    </div>

                    <!-- Products Table Container -->
                    <div id="products-table-box" class="bg-white rounded-3xl border border-zinc-100 shadow-sm overflow-hidden">
                        <div class="flex items-center justify-center py-20">
                            <div class="w-8 h-8 border-2 border-pink-500/20 border-t-pink-500 rounded-full animate-spin"></div>
                        </div>
                    </div>
                </div>
            `;

            // Bind filter events
            const searchInput = document.getElementById('prod-search-input');
            const searchContainer = searchInput?.closest('.admin-search-container');
            const searchClearBtn = document.getElementById('prod-search-clear');
            let timer = null;

            searchInput?.addEventListener('focus', () => {
                searchContainer?.classList.add('is-expanded');
            });

            searchInput?.addEventListener('blur', () => {
                if (!searchInput.value.trim()) {
                    searchContainer?.classList.remove('is-expanded');
                }
            });

            searchInput?.addEventListener('input', (e) => {
                const val = e.target.value;
                if (searchClearBtn) {
                    if (val.trim().length > 0) {
                        searchClearBtn.classList.remove('hidden');
                        searchContainer?.classList.add('is-expanded');
                    } else {
                        searchClearBtn.classList.add('hidden');
                    }
                }
                clearTimeout(timer);
                timer = setTimeout(() => {
                    appState.productsFilter.search = val;
                    appState.productsFilter.page = 1;
                    this.loadProductsList();
                }, 300);
            });

            searchInput?.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    if (searchInput.value) {
                        searchInput.value = '';
                        searchClearBtn?.classList.add('hidden');
                        appState.productsFilter.search = '';
                        appState.productsFilter.page = 1;
                        this.loadProductsList();
                    }
                    searchInput.blur();
                    searchContainer?.classList.remove('is-expanded');
                }
            });

            searchClearBtn?.addEventListener('click', () => {
                if (searchInput) {
                    searchInput.value = '';
                    searchClearBtn.classList.add('hidden');
                    searchInput.focus();
                    appState.productsFilter.search = '';
                    appState.productsFilter.page = 1;
                    this.loadProductsList();
                }
            });

            // Category custom dropdown wiring
            const catWrap = document.getElementById('prod-cat-dropdown-wrap');
            const catTrigger = document.getElementById('prod-cat-trigger');
            const catPanel = catWrap?.querySelector('.custom-dropdown-panel');
            const catChevron = catTrigger?.querySelector('.chevron-icon');

            catTrigger?.addEventListener('click', (e) => {
                e.stopPropagation();
                document.querySelectorAll('.custom-dropdown-panel').forEach(p => {
                    if (p !== catPanel) p.classList.add('hidden');
                });
                document.querySelectorAll('.custom-admin-dropdown .chevron-icon').forEach(c => {
                    if (c !== catChevron) c.classList.remove('rotate-180');
                });
                const isHidden = catPanel.classList.toggle('hidden');
                catChevron?.classList.toggle('rotate-180', !isHidden);
            });

            catWrap?.querySelectorAll('[data-cat-id]').forEach(opt => {
                opt.addEventListener('click', (e) => {
                    e.stopPropagation();
                    catPanel?.classList.add('hidden');
                    catChevron?.classList.remove('rotate-180');
                    appState.productsFilter.category_id = opt.dataset.catId;
                    appState.productsFilter.page = 1;
                    this.renderProducts(1);
                });
            });

            // Status custom dropdown wiring
            const statusWrap = document.getElementById('prod-status-dropdown-wrap');
            const statusTrigger = document.getElementById('prod-status-trigger');
            const statusPanel = statusWrap?.querySelector('.custom-dropdown-panel');
            const statusChevron = statusTrigger?.querySelector('.chevron-icon');

            statusTrigger?.addEventListener('click', (e) => {
                e.stopPropagation();
                document.querySelectorAll('.custom-dropdown-panel').forEach(p => {
                    if (p !== statusPanel) p.classList.add('hidden');
                });
                document.querySelectorAll('.custom-admin-dropdown .chevron-icon').forEach(c => {
                    if (c !== statusChevron) c.classList.remove('rotate-180');
                });
                const isHidden = statusPanel.classList.toggle('hidden');
                statusChevron?.classList.toggle('rotate-180', !isHidden);
            });

            statusWrap?.querySelectorAll('[data-status-val]').forEach(opt => {
                opt.addEventListener('click', (e) => {
                    e.stopPropagation();
                    statusPanel?.classList.add('hidden');
                    statusChevron?.classList.remove('rotate-180');
                    appState.productsFilter.status = opt.dataset.statusVal;
                    appState.productsFilter.page = 1;
                    this.renderProducts(1);
                });
            });

            document.getElementById('prod-reset-filters')?.addEventListener('click', () => {
                appState.productsFilter = { search: '', category_id: '', status: '', page: 1 };
                this.renderProducts(1);
            });

            document.getElementById('btn-create-product')?.addEventListener('click', () => {
                this.openProductEditor(null);
            });

            // Load data
            await this.loadProductsList();
        },

        async loadProductsList() {
            const tableBox = document.getElementById('products-table-box');
            if (!tableBox) return;

            const filter = appState.productsFilter;
            let query = `?page=${filter.page}`;
            if (filter.search) query += `&search=${encodeURIComponent(filter.search)}`;
            if (filter.category_id) query += `&category_id=${filter.category_id}`;
            if (filter.status) query += `&status=${filter.status}`;

            try {
                const data = await api.get(`/api/admin/products${query}`);
                appState.productsData = data;
                const products = data.data || [];

                if (products.length === 0) {
                    tableBox.innerHTML = `
                        <div class="py-16 text-center">
                            <div class="w-14 h-14 rounded-2xl bg-zinc-50 text-zinc-300 flex items-center justify-center mx-auto text-2xl mb-3">
                                <i class="ti ti-package-off"></i>
                            </div>
                            <p class="text-sm font-bold text-zinc-700">Aucun produit trouvé</p>
                            <p class="text-xs text-zinc-400 mt-1">Modifiez vos filtres ou créez une nouvelle référence.</p>
                        </div>
                    `;
                    return;
                }

                tableBox.innerHTML = `
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-zinc-50/80 text-zinc-400 font-bold uppercase tracking-wider text-[10px] border-b border-zinc-100">
                                <tr>
                                    <th class="px-6 py-3.5">Produit</th>
                                    <th class="px-6 py-3.5">Catégorie</th>
                                    <th class="px-6 py-3.5">Tarif</th>
                                    <th class="px-6 py-3.5">Stock</th>
                                    <th class="px-6 py-3.5">Statut</th>
                                    <th class="px-6 py-3.5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                ${products.map(p => {
                                    const isDeleted = !!p.deleted_at;
                                    return `
                                        <tr class="hover:bg-pink-50/30 transition-colors ${isDeleted ? 'opacity-50 bg-zinc-50/50' : ''}">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3.5">
                                                    <div class="w-12 h-12 rounded-xl bg-zinc-100 border border-zinc-200/80 p-1 shrink-0 overflow-hidden flex items-center justify-center">
                                                        <img src="${p.image || '/images/sdj_bum_bum_set.png'}" alt="${p.name}" class="w-full h-full object-contain" onerror="this.src='/images/sdj_bum_bum_set.png'" />
                                                    </div>
                                                    <div class="min-w-0">
                                                        <div class="font-bold text-zinc-900 text-xs truncate max-w-xs flex items-center gap-1.5">
                                                            <span>${p.name}</span>
                                                            ${p.is_bestseller ? '<span class="px-1.5 py-0.5 rounded-full bg-pink-100 text-pink-600 text-[9px] font-extrabold">Best-seller</span>' : ''}
                                                            ${p.is_new ? '<span class="px-1.5 py-0.5 rounded-full bg-sky-100 text-sky-600 text-[9px] font-extrabold">Nouveau</span>' : ''}
                                                        </div>
                                                        <div class="text-[11px] text-zinc-400 font-mono mt-0.5 truncate max-w-xs">${p.slug}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex px-2.5 py-1 rounded-full bg-zinc-100 text-zinc-700 text-[11px] font-semibold">
                                                    ${p.category?.name || 'Non classé'}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                ${p.discounted_price ? `
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="font-black text-pink-600">${formatDH(p.discounted_price)}</span>
                                                        <span class="line-through text-zinc-400 text-[10px]">${formatDH(p.price)}</span>
                                                    </div>
                                                ` : `
                                                    <span class="font-bold text-zinc-900">${formatDH(p.price)}</span>
                                                `}
                                            </td>
                                            <td class="px-6 py-4">
                                                ${p.in_stock ? `
                                                    <span class="inline-flex items-center gap-1 text-emerald-700 text-[11px] font-bold">
                                                        <i class="ti ti-check text-xs"></i>
                                                        En stock ${p.stock_quantity !== null ? `(${p.stock_quantity})` : ''}
                                                    </span>
                                                ` : `
                                                    <span class="inline-flex items-center gap-1 text-rose-600 text-[11px] font-bold">
                                                        <i class="ti ti-x text-xs"></i>
                                                        Rupture
                                                    </span>
                                                `}
                                            </td>
                                            <td class="px-6 py-4">
                                                ${isDeleted ? `
                                                    <span class="inline-flex px-2.5 py-1 rounded-full bg-zinc-200 text-zinc-600 text-[10px] font-bold">Archivé</span>
                                                ` : p.is_active ? `
                                                    <span class="inline-flex px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold">Actif</span>
                                                ` : `
                                                    <span class="inline-flex px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold">Inactif (Précommande)</span>
                                                `}
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex items-center justify-end gap-1.5">
                                                    ${isDeleted ? `
                                                        <button type="button" data-action="restore" data-id="${p.id}" class="px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white font-bold text-xs transition cursor-pointer">
                                                            <i class="ti ti-refresh mr-1"></i> Restaurer
                                                        </button>
                                                    ` : `
                                                        <!-- Option 1: Toggle Status (Actif / Inactif) -->
                                                        <button type="button"
                                                                data-action="toggle-status"
                                                                data-id="${p.id}"
                                                                data-name="${p.name}"
                                                                data-active="${p.is_active ? '1' : '0'}"
                                                                class="px-2.5 py-1.5 rounded-xl font-bold text-xs transition cursor-pointer flex items-center gap-1.5 ${
                                                                    p.is_active
                                                                        ? 'bg-emerald-50 hover:bg-amber-50 text-emerald-700 hover:text-amber-700 border border-emerald-200 hover:border-amber-300'
                                                                        : 'bg-amber-50 hover:bg-emerald-50 text-amber-700 hover:text-emerald-700 border border-amber-200 hover:border-emerald-300'
                                                                }"
                                                                title="${p.is_active ? 'Actif : Cliquer pour passer en Inactif (Mode Précommande)' : 'Inactif : Cliquer pour passer en Actif (Achat direct)'}">
                                                            <i class="ti ${p.is_active ? 'ti-toggle-right text-base text-emerald-600' : 'ti-toggle-left text-base text-amber-600'}"></i>
                                                            <span class="hidden xl:inline">${p.is_active ? 'Actif' : 'Inactif'}</span>
                                                        </button>

                                                        <!-- Edit Button -->
                                                        <button type="button" data-action="edit" data-id="${p.id}" class="p-2 rounded-xl bg-zinc-100 hover:bg-zinc-900 hover:text-white text-zinc-700 transition cursor-pointer" title="Modifier la fiche produit">
                                                            <i class="ti ti-edit text-sm"></i>
                                                        </button>

                                                        <!-- Option 2: Radical Delete (Hard Delete from Website) -->
                                                        <button type="button" data-action="force-delete" data-id="${p.id}" data-name="${p.name}" class="p-2 rounded-xl bg-red-50 hover:bg-red-600 hover:text-white text-red-600 transition cursor-pointer" title="Supprimer radicalement le produit du site">
                                                            <i class="ti ti-trash text-sm"></i>
                                                        </button>

                                                        <!-- Duplicate Button -->
                                                        <button type="button" data-action="duplicate" data-id="${p.id}" data-name="${p.name}" class="p-2 rounded-xl bg-purple-50 hover:bg-purple-600 hover:text-white text-purple-600 transition cursor-pointer" title="Dupliquer le produit">
                                                            <i class="ti ti-copy text-sm"></i>
                                                        </button>
                                                    `}
                                                </div>
                                            </td>
                                        </tr>
                                    `;
                                }).join('')}
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    ${data.last_page > 1 ? `
                        <div class="px-6 py-4 border-t border-zinc-100 flex items-center justify-between text-xs">
                            <span class="text-zinc-400 font-medium">Page ${data.current_page} sur ${data.last_page} (${data.total} produits)</span>
                            <div class="flex items-center gap-1.5">
                                <button type="button" ${data.current_page === 1 ? 'disabled class="opacity-40 cursor-not-allowed"' : ''} id="prod-prev-page" class="px-3 py-1.5 rounded-xl border border-zinc-200 font-bold text-zinc-700 hover:bg-zinc-50 transition cursor-pointer">
                                    Précédent
                                </button>
                                <button type="button" ${data.current_page === data.last_page ? 'disabled class="opacity-40 cursor-not-allowed"' : ''} id="prod-next-page" class="px-3 py-1.5 rounded-xl border border-zinc-200 font-bold text-zinc-700 hover:bg-zinc-50 transition cursor-pointer">
                                    Suivant
                                </button>
                            </div>
                        </div>
                    ` : ''}
                `;

                // Wire row actions
                tableBox.querySelectorAll('button[data-action="toggle-status"]').forEach(btn => {
                    btn.addEventListener('click', async () => {
                        const id = btn.dataset.id;
                        const name = btn.dataset.name;
                        const origHtml = btn.innerHTML;
                        btn.disabled = true;
                        btn.innerHTML = '<i class="ti ti-loader-2 animate-spin text-sm"></i>';
                        try {
                            const res = await api.post(`/api/admin/products/${id}/toggle-status`);
                            if (res.is_active) {
                                toast.show(`"${name}" est maintenant ACTIF (Achat direct disponible sur le site).`);
                            } else {
                                toast.show(`"${name}" est maintenant INACTIF (Mode Précommande activé sur le site).`);
                            }
                            await this.loadProductsList();
                        } catch (err) {
                            toast.show(err.message || 'Erreur lors du changement de statut', 'error');
                            btn.disabled = false;
                            btn.innerHTML = origHtml;
                        }
                    });
                });

                tableBox.querySelectorAll('button[data-action="force-delete"]').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = btn.dataset.id;
                        const name = btn.dataset.name;
                        modalSystem.confirm({
                            title: 'Supprimer radicalement le produit',
                            message: `Attention : Voulez-vous vraiment supprimer radicalement "${name}" du site web et de la base de données ? Cette action est irréversible.`,
                            confirmText: 'Supprimer définitivement',
                            type: 'danger',
                            onConfirm: async () => {
                                await api.delete(`/api/admin/products/${id}/force`);
                                toast.show(`Produit "${name}" supprimé radicalement du site.`);
                                this.loadProductsList();
                            }
                        });
                    });
                });

                tableBox.querySelectorAll('button[data-action="duplicate"]').forEach(btn => {
                    btn.addEventListener('click', async () => {
                        const id = btn.dataset.id;
                        const origHtml = btn.innerHTML;
                        btn.disabled = true;
                        btn.innerHTML = '<i class="ti ti-loader-2 animate-spin text-sm"></i>';
                        try {
                            const res = await api.post(`/api/admin/products/${id}/duplicate`);
                            toast.show(`Produit "${res.name}" dupliqué avec succès !`);
                            await this.loadProductsList();
                        } catch (err) {
                            toast.show(err.message || 'Erreur lors de la duplication', 'error');
                            btn.disabled = false;
                            btn.innerHTML = origHtml;
                        }
                    });
                });

                tableBox.querySelectorAll('button[data-action="edit"]').forEach(btn => {
                    btn.addEventListener('click', () => this.openProductEditor(btn.dataset.id));
                });

                tableBox.querySelectorAll('button[data-action="restore"]').forEach(btn => {
                    btn.addEventListener('click', async () => {
                        const id = btn.dataset.id;
                        try {
                            await api.post(`/api/admin/products/${id}/restore`);
                            toast.show('Produit restauré avec succès.');
                            this.loadProductsList();
                        } catch (err) {
                            toast.show(err.message || 'Erreur de restauration', 'error');
                        }
                    });
                });

                // Wire pagination
                document.getElementById('prod-prev-page')?.addEventListener('click', () => {
                    if (data.current_page > 1) {
                        this.renderProducts(data.current_page - 1);
                    }
                });

                document.getElementById('prod-next-page')?.addEventListener('click', () => {
                    if (data.current_page < data.last_page) {
                        this.renderProducts(data.current_page + 1);
                    }
                });

            } catch (err) {
                tableBox.innerHTML = `
                    <div class="py-12 text-center text-red-500 text-xs">
                        <i class="ti ti-alert-circle text-lg mb-1 block"></i>
                        Erreur de chargement des produits : ${err.message}
                    </div>
                `;
            }
        },

        // ═════════════════════════════════════════════════════════════════════
        // VIEW 3: PRODUCT EDITOR (MODAL / FORM)
        // ═════════════════════════════════════════════════════════════════════
        async openProductEditor(productId = null) {
            await this.ensureCategoriesLoaded();
            const isEditing = !!productId;
            let product = {
                category_id: appState.categoriesCache[0]?.id || '',
                name: '',
                subtitle: '',
                slug: '',
                description: '',
                ingredients: '',
                olfactory: '',
                usage: '',
                price: '',
                discounted_price: '',
                image: '',
                gallery: [],
                badge: '',
                badge_color: 'bg-pink-500 text-white',
                rating: 5.0,
                review_count: 1,
                is_new: false,
                is_bestseller: false,
                in_stock: true,
                is_active: true,
                stock_quantity: '',
                has_sizes: false,
                has_flavors: false,
                sort_order: 0,
                sizes: [],
                flavors: [],
            };

            if (isEditing) {
                try {
                    const res = await api.get(`/api/admin/products/${productId}`);
                    product = { ...product, ...res };
                } catch (err) {
                    toast.show(err.message || 'Impossible de charger le produit', 'error');
                    return;
                }
            }

            const selectedCategory = appState.categoriesCache.find(c => String(c.id) === String(product.category_id)) || appState.categoriesCache[0];
            const currentCatId = selectedCategory ? selectedCategory.id : (product.category_id || '');
            const currentCatName = selectedCategory ? selectedCategory.name : 'Choisir une catégorie';

            const html = `
                <div class="p-6 md:p-7 flex flex-col h-full overflow-hidden max-h-[88vh]">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-zinc-100 shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center text-lg">
                                <i class="ti ${isEditing ? 'ti-edit' : 'ti-plus'}"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-zinc-900">${isEditing ? 'Modifier le produit' : 'Nouveau produit'}</h3>
                                <p class="text-[11px] text-zinc-400 font-medium">${isEditing ? product.name : 'Ajoutez une référence cosmétique à votre boutique'}</p>
                            </div>
                        </div>
                        <button type="button" data-close-modal class="text-zinc-400 hover:text-zinc-700 cursor-pointer">
                            <i class="ti ti-x text-base"></i>
                        </button>
                    </div>

                    <!-- Scrollable Form Body -->
                    <form id="product-editor-form" class="flex-1 overflow-y-auto pr-1 space-y-6 text-xs custom-scrollbar">
                        <!-- Section 1: Basic Info -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-black uppercase tracking-wider text-zinc-400 border-b border-zinc-100 pb-2">Informations Générales</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <label class="block font-bold text-zinc-700 mb-1">Nom du produit <span class="text-pink-600">*</span></label>
                                    <input type="text" name="name" id="pe-name" value="${product.name || ''}" required placeholder="ex: Brume Parfumée Cheirosa 68" class="input-luxury w-full" />
                                </div>

                                <div>
                                    <label class="block font-bold text-zinc-700 mb-1">Catégorie <span class="text-pink-600">*</span></label>
                                    <input type="hidden" name="category_id" id="pe-category-id" value="${currentCatId}" required />
                                    <div class="relative custom-form-dropdown" id="pe-category-dropdown">
                                        <button type="button" id="pe-category-trigger" class="w-full px-4 py-3 bg-white border border-zinc-200 hover:border-pink-300 rounded-2xl text-xs font-semibold text-zinc-900 flex items-center justify-between cursor-pointer focus:outline-none focus:border-pink-500 focus:ring-4 focus:ring-pink-500/12 transition-all">
                                            <span id="pe-category-label" class="font-bold text-zinc-900 truncate">${currentCatName}</span>
                                            <i class="ti ti-chevron-down text-zinc-400 transition-transform duration-200 chevron-icon"></i>
                                        </button>
                                        <div id="pe-category-panel" class="custom-dropdown-panel absolute top-full left-0 right-0 mt-2 bg-white/95 backdrop-blur-md rounded-2xl shadow-[0_15px_35px_rgba(0,0,0,0.14)] border border-zinc-100 p-1.5 z-50 hidden max-h-56 overflow-y-auto">
                                            ${appState.categoriesCache.map(cat => `
                                                <button type="button" class="pe-category-opt w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all cursor-pointer ${String(currentCatId) === String(cat.id) ? 'bg-pink-50 text-pink-600' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black'}" data-cat-id="${cat.id}" data-cat-name="${cat.name}">
                                                    <span class="truncate">${cat.name}</span>
                                                    <i class="ti ti-check text-xs ${String(currentCatId) === String(cat.id) ? '' : 'hidden'}"></i>
                                                </button>
                                            `).join('')}
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block font-bold text-zinc-700 mb-1">Slug URL <span class="text-zinc-400 font-normal">(auto si vide)</span></label>
                                    <input type="text" name="slug" value="${product.slug || ''}" placeholder="ex: brume-cheirosa-68" class="input-luxury w-full font-mono text-[11px]" />
                                </div>

                                <div class="col-span-2">
                                    <label class="block font-bold text-zinc-700 mb-1">Sous-titre / Accroche</label>
                                    <input type="text" name="subtitle" value="${product.subtitle || ''}" placeholder="ex: Brume florale & gourmande pour le corps" class="input-luxury w-full" />
                                </div>

                                <div class="col-span-2">
                                    <label class="block font-bold text-zinc-700 mb-1">Description détaillée</label>
                                    <textarea name="description" rows="3" placeholder="Présentation du produit..." class="textarea-luxury w-full">${product.description || ''}</textarea>
                                </div>

                                <div>
                                    <label class="block font-bold text-zinc-700 mb-1">Notes Olfactives</label>
                                    <textarea name="olfactory" rows="2" placeholder="ex: Pitahaya rose, Jasmin du Brésil, Vanille pure" class="textarea-luxury w-full">${product.olfactory || ''}</textarea>
                                </div>

                                <div>
                                    <label class="block font-bold text-zinc-700 mb-1">Conseils d'utilisation</label>
                                    <textarea name="usage" rows="2" placeholder="ex: Vaporisez sur l'ensemble du corps et des cheveux" class="textarea-luxury w-full">${product.usage || ''}</textarea>
                                </div>

                                <div class="col-span-2">
                                    <label class="block font-bold text-zinc-700 mb-1">Ingrédients clés</label>
                                    <textarea name="ingredients" rows="2" placeholder="ex: Aqua, Alcohol Denat., Parfum, Benzyl Salicylate..." class="textarea-luxury w-full">${product.ingredients || ''}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Pricing -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-black uppercase tracking-wider text-zinc-400 border-b border-zinc-100 pb-2">Prix & Promotions (en DH)</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-bold text-zinc-700 mb-1">Prix original (DH) <span class="text-pink-600">*</span></label>
                                    <input type="number" step="0.01" min="0.01" name="price" id="pe-price" value="${product.price || ''}" required placeholder="350" class="input-luxury w-full font-bold text-zinc-900" />
                                </div>
                                <div>
                                    <label class="block font-bold text-zinc-700 mb-1">Prix soldé (DH) <span class="text-zinc-400 font-normal">(optionnel)</span></label>
                                    <input type="number" step="0.01" min="0" name="discounted_price" id="pe-discount" value="${product.discounted_price || ''}" placeholder="280" class="input-luxury w-full font-bold text-pink-600" />
                                </div>
                            </div>
                            <div id="pe-discount-badge-preview" class="hidden text-[11px] font-bold text-pink-600 bg-pink-50 p-2.5 rounded-2xl flex items-center gap-2">
                                <i class="ti ti-percentage text-sm"></i>
                                <span id="pe-discount-calc">Réduction : -0%</span>
                            </div>
                        </div>

                        <!-- Section 3: Media & Images (Upload & Live Visualizer) -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-black uppercase tracking-wider text-zinc-400 border-b border-zinc-100 pb-2">Visuels & Photos du Produit</h4>
                            
                            <!-- Main Image Upload & Visualization -->
                            <div>
                                <label class="block font-bold text-zinc-700 mb-1.5">
                                    Image principale <span class="text-pink-600">*</span>
                                </label>
                                <input type="hidden" name="image" id="pe-image" value="${product.image || ''}" required />
                                <input type="file" id="pe-main-file-input" accept="image/*" class="hidden" />

                                <!-- Dropzone / Upload Button (when no image) -->
                                <div id="pe-main-upload-zone" class="${product.image ? 'hidden' : ''} border-2 border-dashed border-zinc-200 hover:border-pink-500 rounded-3xl p-6 text-center cursor-pointer transition-all duration-200 bg-zinc-50/50 hover:bg-pink-50/30 group">
                                    <div class="w-12 h-12 rounded-2xl bg-white shadow-xs border border-zinc-100 text-pink-600 flex items-center justify-center mx-auto mb-2.5 group-hover:scale-110 transition-transform">
                                        <i class="ti ti-photo-up text-xl"></i>
                                    </div>
                                    <p class="font-bold text-zinc-800 text-xs">Importer l'image principale</p>
                                    <p class="text-[11px] text-zinc-400 mt-0.5">Glissez-déposez ou cliquez pour parcourir vos fichiers (PNG, JPG, WEBP)</p>
                                    <button type="button" class="mt-3 btn-pill-secondary btn-pill-sm pointer-events-none inline-flex items-center gap-1.5">
                                        <i class="ti ti-upload text-sm"></i>
                                        <span>Choisir un fichier</span>
                                    </button>
                                </div>

                                <!-- Visual Preview Box (when image exists/uploaded) -->
                                <div id="pe-main-preview-card" class="${product.image ? '' : 'hidden'} bg-zinc-50/70 border border-zinc-200/80 rounded-3xl p-4 flex flex-col sm:flex-row items-center gap-4 transition-all">
                                    <div class="relative w-24 h-24 rounded-2xl bg-white border border-zinc-200 shadow-2xs overflow-hidden shrink-0 flex items-center justify-center p-1.5 group">
                                        <img id="pe-main-preview-img" src="${product.image || '/images/sdj_bum_bum_set.png'}" alt="Preview" class="w-full h-full object-contain transition-transform group-hover:scale-105" />
                                    </div>
                                    <div class="flex-1 text-center sm:text-left space-y-1 min-w-0">
                                        <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold">
                                            <i class="ti ti-check text-xs"></i> Image prête pour publication
                                        </div>
                                        <p id="pe-main-filename" class="text-xs font-bold text-zinc-900 truncate">Image principale sélectionnée</p>
                                        <p class="text-[11px] text-zinc-400">Prévisualisation directe avant validation.</p>
                                        <div class="flex items-center justify-center sm:justify-start gap-2 pt-1">
                                            <button type="button" id="pe-change-main-btn" class="btn-pill-secondary btn-pill-sm cursor-pointer">
                                                <i class="ti ti-refresh text-xs"></i>
                                                <span>Changer l'image</span>
                                            </button>
                                            <button type="button" id="pe-remove-main-btn" class="btn-pill-secondary btn-pill-sm text-red-600 hover:border-red-300 hover:bg-red-50 cursor-pointer">
                                                <i class="ti ti-trash text-xs"></i>
                                                <span>Supprimer</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Gallery Photos Upload & Grid Visualization -->
                            <div class="space-y-3 pt-2 border-t border-zinc-100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="font-bold text-zinc-700">Galerie photos additionnelles</label>
                                        <p class="text-[11px] text-zinc-400">Ajoutez des visuels sous d'autres angles ou teintes</p>
                                    </div>
                                    <button type="button" id="pe-add-gallery-file-btn" class="btn-pill-secondary btn-pill-sm cursor-pointer">
                                        <i class="ti ti-photo-plus text-sm"></i>
                                        <span>Importer des photos</span>
                                    </button>
                                </div>

                                <input type="file" id="pe-gallery-file-input" accept="image/*" multiple class="hidden" />

                                <!-- Gallery Grid of Visual Previews -->
                                <div id="pe-gallery-grid" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
                                    ${(product.gallery || []).map((imgUrl, idx) => `
                                        <div class="gallery-photo-card relative aspect-square rounded-2xl bg-white border border-zinc-200 shadow-2xs overflow-hidden group">
                                            <input type="hidden" name="gallery[]" value="${imgUrl}" />
                                            <img src="${imgUrl}" alt="" class="w-full h-full object-cover transition-transform group-hover:scale-105" />
                                            <button type="button" class="remove-gallery-photo-btn absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-black/60 hover:bg-red-600 text-white flex items-center justify-center opacity-90 group-hover:opacity-100 transition-all cursor-pointer shadow-sm" title="Supprimer">
                                                <i class="ti ti-x text-xs"></i>
                                            </button>
                                        </div>
                                    `).join('')}
                                    
                                    <!-- Add Tile Trigger -->
                                    <button type="button" id="pe-gallery-add-tile" class="aspect-square rounded-2xl border-2 border-dashed border-zinc-200 hover:border-pink-500 hover:bg-pink-50/20 flex flex-col items-center justify-center gap-1.5 text-zinc-400 hover:text-pink-600 transition-all cursor-pointer group">
                                        <div class="w-8 h-8 rounded-xl bg-zinc-100 group-hover:bg-pink-50 flex items-center justify-center text-zinc-500 group-hover:text-pink-600 transition-colors">
                                            <i class="ti ti-plus text-base"></i>
                                        </div>
                                        <span class="text-[10px] font-bold">Ajouter</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Inventory & Badges -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-black uppercase tracking-wider text-zinc-400 border-b border-zinc-100 pb-2">Inventaire, Statuts & Badges</h4>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                <label class="flex items-center gap-2 p-2.5 bg-zinc-50 rounded-2xl border border-zinc-200 cursor-pointer hover:border-pink-300 transition-colors">
                                    <input type="checkbox" name="is_active" ${product.is_active ? 'checked' : ''} class="w-4 h-4 rounded accent-pink-600" />
                                    <span class="font-bold text-zinc-800">Produit Actif</span>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 bg-zinc-50 rounded-2xl border border-zinc-200 cursor-pointer hover:border-pink-300 transition-colors">
                                    <input type="checkbox" name="in_stock" ${product.in_stock ? 'checked' : ''} class="w-4 h-4 rounded accent-pink-600" />
                                    <span class="font-bold text-zinc-800">En stock</span>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 bg-zinc-50 rounded-2xl border border-zinc-200 cursor-pointer hover:border-pink-300 transition-colors">
                                    <input type="checkbox" name="is_bestseller" ${product.is_bestseller ? 'checked' : ''} class="w-4 h-4 rounded accent-pink-600" />
                                    <span class="font-bold text-zinc-800">Best-seller</span>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 bg-zinc-50 rounded-2xl border border-zinc-200 cursor-pointer hover:border-pink-300 transition-colors">
                                    <input type="checkbox" name="is_new" ${product.is_new ? 'checked' : ''} class="w-4 h-4 rounded accent-pink-600" />
                                    <span class="font-bold text-zinc-800">Nouveauté</span>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 bg-zinc-50 rounded-2xl border border-zinc-200 cursor-pointer hover:border-pink-300 transition-colors">
                                    <input type="checkbox" name="has_sizes" id="pe-has-sizes" ${product.has_sizes ? 'checked' : ''} class="w-4 h-4 rounded accent-pink-600" />
                                    <span class="font-bold text-zinc-800">Variantes Formats</span>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 bg-zinc-50 rounded-2xl border border-zinc-200 cursor-pointer hover:border-pink-300 transition-colors">
                                    <input type="checkbox" name="has_flavors" id="pe-has-flavors" ${product.has_flavors ? 'checked' : ''} class="w-4 h-4 rounded accent-pink-600" />
                                    <span class="font-bold text-zinc-800">Variantes Parfums</span>
                                </label>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <div>
                                    <label class="block font-bold text-zinc-700 mb-1">Quantité en stock</label>
                                    <input type="number" min="0" name="stock_quantity" value="${product.stock_quantity ?? ''}" placeholder="ex: 50" class="input-luxury w-full" />
                                </div>
                                <div>
                                    <label class="block font-bold text-zinc-700 mb-1">Position / Tri</label>
                                    <input type="number" name="sort_order" value="${product.sort_order || 0}" placeholder="0" class="input-luxury w-full" />
                                </div>
                                <div>
                                    <label class="block font-bold text-zinc-700 mb-1">Note (0-5)</label>
                                    <input type="number" step="0.1" min="0" max="5" name="rating" value="${product.rating || 5.0}" placeholder="4.8" class="input-luxury w-full" />
                                </div>
                                <div>
                                    <label class="block font-bold text-zinc-700 mb-1">Nb d'avis</label>
                                    <input type="number" min="0" name="review_count" value="${product.review_count || 1}" placeholder="12" class="input-luxury w-full" />
                                </div>
                            </div>
                        </div>

                        <!-- Section 5: Size Variants -->
                        <div id="pe-sizes-wrapper" class="space-y-3 ${product.has_sizes || product.sizes?.length ? '' : 'hidden'}">
                            <div class="flex items-center justify-between border-b border-zinc-100 pb-2">
                                <h4 class="text-xs font-black uppercase tracking-wider text-zinc-400">Variantes de Format / Contenance</h4>
                                <button type="button" id="pe-add-size-btn" class="btn-pill-secondary btn-pill-sm cursor-pointer">
                                    <i class="ti ti-plus"></i> Ajouter un format
                                </button>
                            </div>
                            <div id="pe-sizes-list" class="space-y-2.5">
                                ${(product.sizes || []).map((s, i) => `
                                    <div class="flex items-center gap-2 bg-zinc-50 p-2.5 rounded-2xl border border-zinc-200 size-row">
                                        <input type="text" name="size_label[]" value="${s.label || ''}" placeholder="ex: 90ml" required class="input-luxury flex-1 text-xs font-semibold" />
                                        <input type="number" step="0.01" name="size_price[]" value="${s.price || ''}" placeholder="Prix spécifique (DH)" class="input-luxury w-32 text-xs font-semibold" />
                                        <label class="flex items-center gap-1 text-[11px] font-bold text-zinc-600 cursor-pointer">
                                            <input type="checkbox" name="size_instock[]" ${s.in_stock ? 'checked' : ''} class="w-3.5 h-3.5 accent-pink-600 rounded" />
                                            <span>En stock</span>
                                        </label>
                                        <button type="button" class="btn-circle-action w-7 h-7 rounded-full border border-zinc-200 hover:border-red-300 hover:text-red-600 text-zinc-400 remove-size-btn cursor-pointer">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                `).join('')}
                            </div>
                        </div>

                        <!-- Section 6: Flavor / Fragrance Variants -->
                        <div id="pe-flavors-wrapper" class="space-y-3 ${product.has_flavors || product.flavors?.length ? '' : 'hidden'}">
                            <div class="flex items-center justify-between border-b border-zinc-100 pb-2">
                                <h4 class="text-xs font-black uppercase tracking-wider text-zinc-400">Variantes de Parfum / Teinte</h4>
                                <button type="button" id="pe-add-flavor-btn" class="btn-pill-secondary btn-pill-sm cursor-pointer">
                                    <i class="ti ti-plus"></i> Ajouter un parfum
                                </button>
                            </div>
                            <div id="pe-flavors-list" class="space-y-2.5">
                                ${(product.flavors || []).map((f, i) => `
                                    <div class="flex items-center gap-2 bg-zinc-50 p-2.5 rounded-2xl border border-zinc-200 flavor-row">
                                        <input type="text" name="flavor_label[]" value="${f.label || ''}" placeholder="ex: Cheirosa 68" required class="input-luxury flex-1 text-xs font-semibold" />
                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <input type="color" value="${f.color_hex || '#ff1b7a'}" class="w-8 h-8 rounded-xl cursor-pointer border border-zinc-200 flavor-color-picker" />
                                            <input type="text" name="flavor_color[]" value="${f.color_hex || '#ff1b7a'}" placeholder="#ff1b7a" class="input-luxury w-24 text-[11px] font-mono flavor-color-hex" />
                                        </div>
                                        <label class="flex items-center gap-1 text-[11px] font-bold text-zinc-600 cursor-pointer">
                                            <input type="checkbox" name="flavor_instock[]" ${f.in_stock ? 'checked' : ''} class="w-3.5 h-3.5 accent-pink-600 rounded" />
                                            <span>En stock</span>
                                        </label>
                                        <button type="button" class="btn-circle-action w-7 h-7 rounded-full border border-zinc-200 hover:border-red-300 hover:text-red-600 text-zinc-400 remove-flavor-btn cursor-pointer">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </form>

                    <!-- Modal Footer -->
                    <div class="flex items-center justify-between gap-3 pt-4 border-t border-zinc-100 mt-5 shrink-0">
                        <div>
                            ${isEditing ? `
                                <button type="button" id="pe-duplicate-btn" class="btn-pill-secondary btn-pill-sm text-purple-700 bg-purple-50 hover:bg-purple-100 hover:border-purple-200 cursor-pointer flex items-center gap-1.5" title="Créer une copie de ce produit">
                                    <i class="ti ti-copy text-sm"></i>
                                    <span>Dupliquer</span>
                                </button>
                            ` : ''}
                        </div>
                        <div class="flex items-center gap-2.5">
                            <button type="button" data-close-modal class="btn-pill-secondary btn-pill-sm cursor-pointer">
                                Annuler
                            </button>
                            <button type="submit" form="product-editor-form" id="pe-submit-btn" class="btn-pill-primary btn-pill-sm">
                                <i class="ti ti-check text-sm"></i>
                                <span>${isEditing ? 'Enregistrer les modifications' : 'Créer le produit'}</span>
                            </button>
                        </div>
                    </div>
                </div>
            `;

            modalSystem.openModal(html, {
                maxWidth: 'max-w-3xl',
                onMount: (panel) => {
                    // Category custom dropdown wiring
                    const catTrigger = panel.querySelector('#pe-category-trigger');
                    const catPanel = panel.querySelector('#pe-category-panel');
                    const catLabel = panel.querySelector('#pe-category-label');
                    const catInput = panel.querySelector('#pe-category-id');
                    const catChevron = catTrigger?.querySelector('.chevron-icon');

                    catTrigger?.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const isHidden = catPanel.classList.toggle('hidden');
                        catChevron?.classList.toggle('rotate-180', !isHidden);
                    });

                    panel.querySelectorAll('.pe-category-opt').forEach(opt => {
                        opt.addEventListener('click', (e) => {
                            e.stopPropagation();
                            const catId = opt.dataset.catId;
                            const catName = opt.dataset.catName;
                            if (catInput) catInput.value = catId;
                            if (catLabel) catLabel.textContent = catName;

                            panel.querySelectorAll('.pe-category-opt').forEach(o => {
                                const isMatch = o.dataset.catId === catId;
                                o.classList.toggle('bg-pink-50', isMatch);
                                o.classList.toggle('text-pink-600', isMatch);
                                o.classList.toggle('text-zinc-700', !isMatch);
                                const check = o.querySelector('.ti-check');
                                if (check) check.classList.toggle('hidden', !isMatch);
                            });

                            catPanel.classList.add('hidden');
                            catChevron?.classList.remove('rotate-180');
                        });
                    });

                    // Main Image Upload & Visualization wiring
                    const mainFileInput = panel.querySelector('#pe-main-file-input');
                    const mainUploadZone = panel.querySelector('#pe-main-upload-zone');
                    const mainPreviewCard = panel.querySelector('#pe-main-preview-card');
                    const mainPreviewImg = panel.querySelector('#pe-main-preview-img');
                    const mainFilename = panel.querySelector('#pe-main-filename');
                    const mainHiddenInput = panel.querySelector('#pe-image');
                    const changeMainBtn = panel.querySelector('#pe-change-main-btn');
                    const removeMainBtn = panel.querySelector('#pe-remove-main-btn');

                    const handleMainImageFile = async (file) => {
                        if (!file) return;
                        try {
                            const dataUrl = await readFileAsCompressedDataUrl(file);
                            mainHiddenInput.value = dataUrl;
                            mainPreviewImg.src = dataUrl;
                            mainFilename.textContent = file.name || 'Image sélectionnée';
                            mainUploadZone.classList.add('hidden');
                            mainPreviewCard.classList.remove('hidden');
                        } catch (err) {
                            toast.show(err.message || 'Erreur lors du traitement de l\'image', 'error');
                        }
                    };

                    mainUploadZone?.addEventListener('click', () => mainFileInput?.click());
                    changeMainBtn?.addEventListener('click', () => mainFileInput?.click());
                    mainFileInput?.addEventListener('change', (e) => {
                        if (e.target.files?.[0]) handleMainImageFile(e.target.files[0]);
                    });

                    // Main image Drag & Drop
                    mainUploadZone?.addEventListener('dragover', (e) => {
                        e.preventDefault();
                        mainUploadZone.classList.add('border-pink-500', 'bg-pink-50/40');
                    });
                    mainUploadZone?.addEventListener('dragleave', () => {
                        mainUploadZone.classList.remove('border-pink-500', 'bg-pink-50/40');
                    });
                    mainUploadZone?.addEventListener('drop', (e) => {
                        e.preventDefault();
                        mainUploadZone.classList.remove('border-pink-500', 'bg-pink-50/40');
                        if (e.dataTransfer?.files?.[0]) handleMainImageFile(e.dataTransfer.files[0]);
                    });

                    removeMainBtn?.addEventListener('click', () => {
                        mainHiddenInput.value = '';
                        mainFileInput.value = '';
                        mainPreviewImg.src = '';
                        mainPreviewCard.classList.add('hidden');
                        mainUploadZone.classList.remove('hidden');
                    });

                    // Additional Photos (Gallery) Upload & Visualization wiring
                    const galleryFileInput = panel.querySelector('#pe-gallery-file-input');
                    const addGalleryBtn = panel.querySelector('#pe-add-gallery-file-btn');
                    const addGalleryTile = panel.querySelector('#pe-gallery-add-tile');
                    const galleryGrid = panel.querySelector('#pe-gallery-grid');

                    const addGalleryPhotoCard = (dataUrl) => {
                        const card = document.createElement('div');
                        card.className = 'gallery-photo-card relative aspect-square rounded-2xl bg-white border border-zinc-200 shadow-2xs overflow-hidden group animate-fadeIn';
                        card.innerHTML = `
                            <input type="hidden" name="gallery[]" value="${dataUrl}" />
                            <img src="${dataUrl}" alt="" class="w-full h-full object-cover transition-transform group-hover:scale-105" />
                            <button type="button" class="remove-gallery-photo-btn absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-black/60 hover:bg-red-600 text-white flex items-center justify-center opacity-90 group-hover:opacity-100 transition-all cursor-pointer shadow-sm" title="Supprimer">
                                <i class="ti ti-x text-xs"></i>
                            </button>
                        `;
                        card.querySelector('.remove-gallery-photo-btn').addEventListener('click', () => card.remove());
                        galleryGrid.insertBefore(card, addGalleryTile);
                    };

                    const handleGalleryFiles = async (files) => {
                        if (!files || files.length === 0) return;
                        for (const file of Array.from(files)) {
                            try {
                                const dataUrl = await readFileAsCompressedDataUrl(file);
                                addGalleryPhotoCard(dataUrl);
                            } catch (err) {
                                console.warn('Could not load gallery photo:', err);
                            }
                        }
                    };

                    addGalleryBtn?.addEventListener('click', () => galleryFileInput?.click());
                    addGalleryTile?.addEventListener('click', () => galleryFileInput?.click());
                    galleryFileInput?.addEventListener('change', (e) => {
                        handleGalleryFiles(e.target.files);
                        galleryFileInput.value = '';
                    });

                    panel.querySelectorAll('.remove-gallery-photo-btn').forEach(btn => {
                        btn.addEventListener('click', () => btn.closest('.gallery-photo-card')?.remove());
                    });

                    // Toggle Size and Flavor sections
                    const hasSizesCb = panel.querySelector('#pe-has-sizes');
                    const sizesWrapper = panel.querySelector('#pe-sizes-wrapper');
                    hasSizesCb?.addEventListener('change', () => {
                        if (hasSizesCb.checked) sizesWrapper.classList.remove('hidden');
                    });

                    const hasFlavorsCb = panel.querySelector('#pe-has-flavors');
                    const flavorsWrapper = panel.querySelector('#pe-flavors-wrapper');
                    hasFlavorsCb?.addEventListener('change', () => {
                        if (hasFlavorsCb.checked) flavorsWrapper.classList.remove('hidden');
                    });

                    // Size dynamic rows
                    const sizesList = panel.querySelector('#pe-sizes-list');
                    panel.querySelector('#pe-add-size-btn')?.addEventListener('click', () => {
                        sizesWrapper.classList.remove('hidden');
                        if (hasSizesCb) hasSizesCb.checked = true;
                        const row = document.createElement('div');
                        row.className = 'flex items-center gap-2 bg-zinc-50 p-2.5 rounded-xl border border-zinc-200 size-row';
                        row.innerHTML = `
                            <input type="text" name="size_label[]" value="" placeholder="ex: 240ml" required class="flex-1 bg-white border border-zinc-200 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-zinc-800" />
                            <input type="number" step="0.01" name="size_price[]" value="" placeholder="Prix spécifique (DH)" class="w-32 bg-white border border-zinc-200 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-zinc-800" />
                            <label class="flex items-center gap-1 text-[11px] font-bold text-zinc-600 cursor-pointer">
                                <input type="checkbox" name="size_instock[]" checked class="w-3.5 h-3.5 accent-pink-600 rounded" />
                                <span>En stock</span>
                            </label>
                            <button type="button" class="p-1 text-zinc-400 hover:text-red-600 rounded remove-size-btn cursor-pointer">
                                <i class="ti ti-trash"></i>
                            </button>
                        `;
                        row.querySelector('.remove-size-btn').addEventListener('click', () => row.remove());
                        sizesList.appendChild(row);
                    });

                    panel.querySelectorAll('.remove-size-btn').forEach(btn => {
                        btn.addEventListener('click', () => btn.closest('.size-row')?.remove());
                    });

                    // Flavor dynamic rows
                    const flavorsList = panel.querySelector('#pe-flavors-list');
                    const wireFlavorRow = (row) => {
                        const picker = row.querySelector('.flavor-color-picker');
                        const hexInput = row.querySelector('.flavor-color-hex');
                        picker?.addEventListener('input', () => hexInput.value = picker.value);
                        hexInput?.addEventListener('input', () => {
                            if (/^#[0-9A-Fa-f]{6}$/.test(hexInput.value)) {
                                picker.value = hexInput.value;
                            }
                        });
                        row.querySelector('.remove-flavor-btn')?.addEventListener('click', () => row.remove());
                    };

                    panel.querySelectorAll('.flavor-row').forEach(wireFlavorRow);

                    panel.querySelector('#pe-add-flavor-btn')?.addEventListener('click', () => {
                        flavorsWrapper.classList.remove('hidden');
                        if (hasFlavorsCb) hasFlavorsCb.checked = true;
                        const row = document.createElement('div');
                        row.className = 'flex items-center gap-2 bg-zinc-50 p-2.5 rounded-xl border border-zinc-200 flavor-row';
                        row.innerHTML = `
                            <input type="text" name="flavor_label[]" value="" placeholder="ex: Cheirosa 59" required class="flex-1 bg-white border border-zinc-200 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-zinc-800" />
                            <div class="flex items-center gap-1.5 shrink-0">
                                <input type="color" value="#a855f7" class="w-8 h-8 rounded-lg cursor-pointer border border-zinc-200 flavor-color-picker" />
                                <input type="text" name="flavor_color[]" value="#a855f7" placeholder="#a855f7" class="w-24 bg-white border border-zinc-200 rounded-lg px-2 py-1.5 font-mono text-[11px] text-zinc-800 flavor-color-hex" />
                            </div>
                            <label class="flex items-center gap-1 text-[11px] font-bold text-zinc-600 cursor-pointer">
                                <input type="checkbox" name="flavor_instock[]" checked class="w-3.5 h-3.5 accent-pink-600 rounded" />
                                <span>En stock</span>
                            </label>
                            <button type="button" class="p-1 text-zinc-400 hover:text-red-600 rounded remove-flavor-btn cursor-pointer">
                                <i class="ti ti-trash"></i>
                            </button>
                        `;
                        wireFlavorRow(row);
                        flavorsList.appendChild(row);
                    });

                    // Form submission
                    const form = panel.querySelector('#product-editor-form');
                    form?.addEventListener('submit', async (e) => {
                        e.preventDefault();
                        const submitBtn = panel.querySelector('#pe-submit-btn');
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<div class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div> Enregistrement...';

                        // Parse sizes
                        const sizes = [];
                        panel.querySelectorAll('.size-row').forEach(r => {
                            const label = r.querySelector('input[name="size_label[]"]')?.value.trim();
                            const priceVal = r.querySelector('input[name="size_price[]"]')?.value;
                            const inStock = r.querySelector('input[name="size_instock[]"]')?.checked;
                            if (label) {
                                sizes.push({
                                    label,
                                    price: priceVal ? parseFloat(priceVal) : null,
                                    in_stock: !!inStock
                                });
                            }
                        });

                        // Parse flavors
                        const flavors = [];
                        panel.querySelectorAll('.flavor-row').forEach(r => {
                            const label = r.querySelector('input[name="flavor_label[]"]')?.value.trim();
                            const color_hex = r.querySelector('input[name="flavor_color[]"]')?.value.trim();
                            const inStock = r.querySelector('input[name="flavor_instock[]"]')?.checked;
                            if (label) {
                                flavors.push({
                                    label,
                                    color_hex: color_hex || null,
                                    in_stock: !!inStock
                                });
                            }
                        });

                        // Parse gallery
                        const gallery = [];
                        panel.querySelectorAll('input[name="gallery[]"]').forEach(inp => {
                            if (inp.value.trim()) gallery.push(inp.value.trim());
                        });

                        const payload = {
                            category_id: parseInt(form.category_id.value, 10),
                            name: form.name.value.trim(),
                            subtitle: form.subtitle.value.trim() || null,
                            slug: form.slug.value.trim() || null,
                            description: form.description.value.trim() || null,
                            olfactory: form.olfactory.value.trim() || null,
                            usage: form.usage.value.trim() || null,
                            ingredients: form.ingredients.value.trim() || null,
                            price: parseFloat(form.price.value),
                            discounted_price: form.discounted_price.value ? parseFloat(form.discounted_price.value) : null,
                            image: form.image.value.trim(),
                            gallery: gallery.length > 0 ? gallery : null,
                            in_stock: form.in_stock.checked,
                            is_active: form.is_active.checked,
                            is_bestseller: form.is_bestseller.checked,
                            is_new: form.is_new.checked,
                            has_sizes: form.has_sizes.checked || sizes.length > 0,
                            has_flavors: form.has_flavors.checked || flavors.length > 0,
                            stock_quantity: form.stock_quantity.value ? parseInt(form.stock_quantity.value, 10) : null,
                            sort_order: form.sort_order.value ? parseInt(form.sort_order.value, 10) : 0,
                            rating: form.rating.value ? parseFloat(form.rating.value) : null,
                            review_count: form.review_count.value ? parseInt(form.review_count.value, 10) : null,
                            sizes: sizes.length > 0 ? sizes : null,
                            flavors: flavors.length > 0 ? flavors : null,
                        };

                        try {
                            if (isEditing) {
                                await api.put(`/api/admin/products/${productId}`, payload);
                                toast.show(`Produit "${payload.name}" mis à jour avec succès.`);
                            } else {
                                await api.post('/api/admin/products', payload);
                                toast.show(`Produit "${payload.name}" créé avec succès.`);
                            }

                            modalSystem.closeModal();
                            if (appState.currentView === 'products') {
                                this.loadProductsList();
                            }
                        } catch (err) {
                            toast.show(err.message || 'Erreur lors de l\'enregistrement', 'error');
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = `<i class="ti ti-check text-sm"></i><span>${isEditing ? 'Enregistrer les modifications' : 'Créer le produit'}</span>`;
                        }
                    });

                    // Duplicate product from modal
                    const duplicateBtn = panel.querySelector('#pe-duplicate-btn');
                    duplicateBtn?.addEventListener('click', async () => {
                        const origHtml = duplicateBtn.innerHTML;
                        duplicateBtn.disabled = true;
                        duplicateBtn.innerHTML = '<i class="ti ti-loader-2 animate-spin text-sm"></i><span>Duplication...</span>';
                        try {
                            const res = await api.post(`/api/admin/products/${productId}/duplicate`);
                            toast.show(`Produit "${res.name}" dupliqué avec succès !`);
                            modalSystem.closeModal();
                            if (appState.currentView === 'products') {
                                await this.loadProductsList();
                            }
                            this.openProductEditor(res.id);
                        } catch (err) {
                            toast.show(err.message || 'Erreur lors de la duplication', 'error');
                            duplicateBtn.disabled = false;
                            duplicateBtn.innerHTML = origHtml;
                        }
                    });
                }
            });
        },

        // ═════════════════════════════════════════════════════════════════════
        // VIEW 4: CATEGORIES
        // ═════════════════════════════════════════════════════════════════════
        async renderCategories() {
            this.root.innerHTML = `
                <div class="space-y-6 animate-fadeIn">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-black text-zinc-900 tracking-tight">Catégories & Collections</h1>
                            <p class="text-xs text-zinc-400 font-medium">Organisez votre catalogue par univers et rituels de beauté</p>
                        </div>
                        <button type="button" id="btn-create-category" class="btn-pill-primary btn-pill-sm shrink-0">
                            <i class="ti ti-plus text-sm"></i>
                            <span>Nouvelle catégorie</span>
                        </button>
                    </div>

                    <!-- Categories Table Container -->
                    <div id="categories-table-box" class="bg-white rounded-3xl border border-zinc-100 shadow-sm overflow-hidden">
                        <div class="flex items-center justify-center py-20">
                            <div class="w-8 h-8 border-2 border-pink-500/20 border-t-pink-500 rounded-full animate-spin"></div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('btn-create-category')?.addEventListener('click', () => {
                this.openCategoryEditor(null);
            });

            await this.loadCategoriesList();
        },

        async loadCategoriesList() {
            const box = document.getElementById('categories-table-box');
            if (!box) return;

            try {
                const categories = await api.get('/api/admin/categories');
                appState.categoriesCache = categories || [];

                if (categories.length === 0) {
                    box.innerHTML = `
                        <div class="py-16 text-center">
                            <div class="w-14 h-14 rounded-2xl bg-zinc-50 text-zinc-300 flex items-center justify-center mx-auto text-2xl mb-3">
                                <i class="ti ti-category"></i>
                            </div>
                            <p class="text-sm font-bold text-zinc-700">Aucune catégorie enregistrée</p>
                            <p class="text-xs text-zinc-400 mt-1">Créez votre première collection pour organiser vos produits.</p>
                        </div>
                    `;
                    return;
                }

                box.innerHTML = `
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-zinc-50/80 text-zinc-400 font-bold uppercase tracking-wider text-[10px] border-b border-zinc-100">
                                <tr>
                                    <th class="px-6 py-3.5">Catégorie</th>
                                    <th class="px-6 py-3.5">Slug</th>
                                    <th class="px-6 py-3.5">Produits</th>
                                    <th class="px-6 py-3.5">Ordre</th>
                                    <th class="px-6 py-3.5">Statut</th>
                                    <th class="px-6 py-3.5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                ${categories.map(c => `
                                    <tr class="hover:bg-pink-50/30 transition-colors">
                                        <td class="px-6 py-4 font-bold text-zinc-900 flex items-center gap-3">
                                            ${c.image ? `
                                                <img src="${c.image}" alt="" class="w-8 h-8 rounded-lg object-cover border border-zinc-200" />
                                            ` : `
                                                <div class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center font-bold text-xs">
                                                    ${c.name.charAt(0)}
                                                </div>
                                            `}
                                            <span>${c.name}</span>
                                        </td>
                                        <td class="px-6 py-4 font-mono text-zinc-500 text-[11px]">${c.slug}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex px-2 py-0.5 rounded-full bg-zinc-100 text-zinc-700 text-[10px] font-bold">
                                                ${c.products_count || 0} référence(s)
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-zinc-500">${c.sort_order}</td>
                                        <td class="px-6 py-4">
                                            ${c.is_active ? `
                                                <span class="inline-flex px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold">Actif</span>
                                            ` : `
                                                <span class="inline-flex px-2.5 py-1 rounded-full bg-zinc-100 text-zinc-600 text-[10px] font-bold">Inactif</span>
                                            `}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <button type="button" data-action="edit-cat" data-id="${c.id}" class="p-2 rounded-xl bg-zinc-100 hover:bg-zinc-900 hover:text-white text-zinc-700 transition cursor-pointer" title="Modifier">
                                                    <i class="ti ti-edit text-sm"></i>
                                                </button>
                                                <button type="button" data-action="delete-cat" data-id="${c.id}" data-name="${c.name}" class="p-2 rounded-xl bg-red-50 hover:bg-red-600 hover:text-white text-red-600 transition cursor-pointer" title="Supprimer">
                                                    <i class="ti ti-trash text-sm"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;

                box.querySelectorAll('button[data-action="edit-cat"]').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = btn.dataset.id;
                        const cat = categories.find(c => String(c.id) === String(id));
                        this.openCategoryEditor(cat);
                    });
                });

                box.querySelectorAll('button[data-action="delete-cat"]').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = btn.dataset.id;
                        const name = btn.dataset.name;
                        modalSystem.confirm({
                            title: 'Supprimer la catégorie',
                            message: `Voulez-vous définitivement supprimer la catégorie "${name}" ?`,
                            confirmText: 'Supprimer la catégorie',
                            type: 'danger',
                            onConfirm: async () => {
                                await api.delete(`/api/admin/categories/${id}`);
                                toast.show(`Catégorie "${name}" supprimée.`);
                                await this.ensureCategoriesLoaded(true);
                                this.loadCategoriesList();
                            }
                        });
                    });
                });

            } catch (err) {
                box.innerHTML = `
                    <div class="py-12 text-center text-red-500 text-xs">
                        <i class="ti ti-alert-circle text-lg mb-1 block"></i>
                        Erreur : ${err.message}
                    </div>
                `;
            }
        },

        openCategoryEditor(category = null) {
            const isEditing = !!category;
            const data = category || {
                name: '',
                slug: '',
                image: '',
                description: '',
                is_active: true,
                sort_order: 0
            };

            const html = `
                <div class="p-6 md:p-7">
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-zinc-100">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center text-lg">
                                <i class="ti ${isEditing ? 'ti-edit' : 'ti-plus'}"></i>
                            </div>
                            <h3 class="text-sm font-bold text-zinc-900">${isEditing ? 'Modifier la catégorie' : 'Nouvelle catégorie'}</h3>
                        </div>
                        <button type="button" data-close-modal class="text-zinc-400 hover:text-zinc-700 cursor-pointer">
                            <i class="ti ti-x text-base"></i>
                        </button>
                    </div>

                    <form id="category-modal-form" class="space-y-4 text-xs">
                        <div>
                            <label class="block font-bold text-zinc-700 mb-1">Nom de la catégorie <span class="text-pink-600">*</span></label>
                            <input type="text" name="name" value="${data.name || ''}" required placeholder="ex: Soins Corps & Solaires" class="input-luxury w-full" />
                        </div>

                        <div>
                            <label class="block font-bold text-zinc-700 mb-1">Slug URL <span class="text-zinc-400 font-normal">(auto si vide)</span></label>
                            <input type="text" name="slug" value="${data.slug || ''}" placeholder="ex: soins-corps" class="input-luxury w-full font-mono text-[11px]" />
                        </div>

                        <div>
                            <label class="block font-bold text-zinc-700 mb-1">Image d'illustration</label>
                            <input type="hidden" name="image" id="cat-image" value="${data.image || ''}" />
                            <input type="file" id="cat-file-input" accept="image/*" class="hidden" />

                            <div id="cat-upload-zone" class="${data.image ? 'hidden' : ''} border-2 border-dashed border-zinc-200 hover:border-pink-500 rounded-2xl p-4 text-center cursor-pointer transition-all bg-zinc-50/50 hover:bg-pink-50/30 group">
                                <i class="ti ti-photo-up text-xl text-pink-600 mb-1 block group-hover:scale-110 transition-transform"></i>
                                <span class="text-xs font-bold text-zinc-800">Importer une image</span>
                                <p class="text-[10px] text-zinc-400 mt-0.5">PNG, JPG, WEBP</p>
                            </div>

                            <div id="cat-preview-card" class="${data.image ? '' : 'hidden'} bg-zinc-50/70 border border-zinc-200 rounded-2xl p-3 flex items-center gap-3">
                                <div class="w-14 h-14 rounded-xl bg-white border border-zinc-200 overflow-hidden shrink-0 flex items-center justify-center p-0.5">
                                    <img id="cat-preview-img" src="${data.image || ''}" alt="Preview" class="w-full h-full object-cover" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-zinc-900 truncate" id="cat-filename">Image sélectionnée</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <button type="button" id="cat-change-btn" class="text-[11px] font-bold text-pink-600 hover:underline cursor-pointer">Changer</button>
                                        <span class="text-zinc-300">•</span>
                                        <button type="button" id="cat-remove-btn" class="text-[11px] font-bold text-red-600 hover:underline cursor-pointer">Supprimer</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-zinc-700 mb-1">Description</label>
                            <textarea name="description" rows="2" placeholder="Brève description de la collection..." class="textarea-luxury w-full">${data.description || ''}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-1">
                            <div>
                                <label class="block font-bold text-zinc-700 mb-1">Position / Ordre</label>
                                <input type="number" name="sort_order" value="${data.sort_order || 0}" class="input-luxury w-full" />
                            </div>
                            <div class="flex items-center">
                                <label class="flex items-center gap-2 p-2.5 bg-zinc-50 rounded-2xl border border-zinc-200 cursor-pointer w-full mt-4">
                                    <input type="checkbox" name="is_active" ${data.is_active ? 'checked' : ''} class="w-4 h-4 rounded accent-pink-600" />
                                    <span class="font-bold text-zinc-800">Catégorie active</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-100 mt-5">
                            <button type="button" data-close-modal class="btn-pill-secondary btn-pill-sm cursor-pointer">
                                Annuler
                            </button>
                            <button type="submit" id="cat-submit-btn" class="btn-pill-primary btn-pill-sm">
                                <i class="ti ti-check"></i>
                                <span>${isEditing ? 'Enregistrer' : 'Créer'}</span>
                            </button>
                        </div>
                    </form>
                </div>
            `;

            modalSystem.openModal(html, {
                maxWidth: 'max-w-md',
                onMount: (box) => {
                    // Category Image Upload wiring
                    const catFileInput = box.querySelector('#cat-file-input');
                    const catUploadZone = box.querySelector('#cat-upload-zone');
                    const catPreviewCard = box.querySelector('#cat-preview-card');
                    const catPreviewImg = box.querySelector('#cat-preview-img');
                    const catHiddenInput = box.querySelector('#cat-image');
                    const catChangeBtn = box.querySelector('#cat-change-btn');
                    const catRemoveBtn = box.querySelector('#cat-remove-btn');
                    const catFilename = box.querySelector('#cat-filename');

                    const handleCatImage = async (file) => {
                        if (!file) return;
                        try {
                            const dataUrl = await readFileAsCompressedDataUrl(file);
                            catHiddenInput.value = dataUrl;
                            catPreviewImg.src = dataUrl;
                            catFilename.textContent = file.name || 'Image sélectionnée';
                            catUploadZone.classList.add('hidden');
                            catPreviewCard.classList.remove('hidden');
                        } catch (err) {
                            toast.show(err.message || 'Erreur image', 'error');
                        }
                    };

                    catUploadZone?.addEventListener('click', () => catFileInput?.click());
                    catChangeBtn?.addEventListener('click', () => catFileInput?.click());
                    catFileInput?.addEventListener('change', (e) => {
                        if (e.target.files?.[0]) handleCatImage(e.target.files[0]);
                    });

                    catRemoveBtn?.addEventListener('click', () => {
                        catHiddenInput.value = '';
                        catFileInput.value = '';
                        catPreviewImg.src = '';
                        catPreviewCard.classList.add('hidden');
                        catUploadZone.classList.remove('hidden');
                    });

                    const form = box.querySelector('#category-modal-form');
                    form?.addEventListener('submit', async (e) => {
                        e.preventDefault();
                        const btn = box.querySelector('#cat-submit-btn');
                        btn.disabled = true;
                        btn.innerHTML = '<i class="ti ti-loader-2 animate-spin mr-1"></i> Traitement...';

                        const payload = {
                            name: form.name.value.trim(),
                            slug: form.slug.value.trim() || null,
                            image: form.image.value.trim() || null,
                            description: form.description.value.trim() || null,
                            sort_order: parseInt(form.sort_order.value, 10) || 0,
                            is_active: form.is_active.checked
                        };

                        try {
                            if (isEditing) {
                                await api.put(`/api/admin/categories/${data.id}`, payload);
                                toast.show(`Catégorie "${payload.name}" mise à jour.`);
                            } else {
                                await api.post('/api/admin/categories', payload);
                                toast.show(`Catégorie "${payload.name}" créée.`);
                            }
                            await this.ensureCategoriesLoaded(true);
                            modalSystem.closeModal();
                            this.loadCategoriesList();
                        } catch (err) {
                            toast.show(err.message || 'Erreur lors de l\'enregistrement', 'error');
                            btn.disabled = false;
                            btn.textContent = isEditing ? 'Enregistrer' : 'Créer';
                        }
                    });
                }
            });
        },

        // ═════════════════════════════════════════════════════════════════════
        // VIEW 5: DISCOUNTS & COUPONS
        // ═════════════════════════════════════════════════════════════════════
        async renderDiscounts() {
            this.root.innerHTML = `
                <div class="space-y-6 animate-fadeIn">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-black text-zinc-900 tracking-tight">Remises & Codes Promo</h1>
                            <p class="text-xs text-zinc-400 font-medium">Pilotez vos campagnes promotionnelles, codes de réduction et soldes produits</p>
                        </div>
                        <button type="button" id="btn-create-coupon" class="btn-pill-primary btn-pill-sm shrink-0">
                            <i class="ti ti-plus text-sm"></i>
                            <span>Nouveau code promo</span>
                        </button>
                    </div>

                    <!-- Tabs: Coupons vs Product Sale Price Editor -->
                    <div class="flex items-center gap-2 border-b border-zinc-200 pb-px">
                        <button type="button" id="tab-coupons-btn" class="px-4 py-2.5 text-xs font-black border-b-2 border-pink-600 text-pink-600 transition flex items-center gap-2 cursor-pointer">
                            <i class="ti ti-ticket text-sm"></i>
                            <span>Codes Promo Panier</span>
                        </button>
                        <button type="button" id="tab-sales-btn" class="px-4 py-2.5 text-xs font-bold text-zinc-500 hover:text-zinc-900 border-b-2 border-transparent transition flex items-center gap-2 cursor-pointer">
                            <i class="ti ti-tag text-sm"></i>
                            <span>Éditeur Rapide de Soldes Produits</span>
                        </button>
                    </div>

                    <!-- Panel 1: Coupons -->
                    <div id="panel-coupons" class="space-y-4">
                        <div id="coupons-table-box" class="bg-white rounded-3xl border border-zinc-100 shadow-sm overflow-hidden">
                            <div class="flex items-center justify-center py-20">
                                <div class="w-8 h-8 border-2 border-pink-500/20 border-t-pink-500 rounded-full animate-spin"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Panel 2: Product Sale Price Quick Editor -->
                    <div id="panel-sales" class="space-y-4 hidden">
                        <div class="bg-white rounded-3xl border border-zinc-100 shadow-sm p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-sm font-bold text-zinc-900">Ajustement instantané des prix soldés</h3>
                                    <p class="text-xs text-zinc-400">Définissez un prix promotionnel inférieur au prix original pour chaque produit</p>
                                </div>
                                <button type="button" id="refresh-sales-btn" class="p-2 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-600 transition cursor-pointer" title="Rafraîchir">
                                    <i class="ti ti-refresh"></i>
                                </button>
                            </div>
                            <div id="sales-editor-box" class="overflow-x-auto">
                                <div class="flex items-center justify-center py-12">
                                    <div class="w-7 h-7 border-2 border-pink-500/20 border-t-pink-500 rounded-full animate-spin"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Tab switching
            const tabCoupons = document.getElementById('tab-coupons-btn');
            const tabSales = document.getElementById('tab-sales-btn');
            const panelCoupons = document.getElementById('panel-coupons');
            const panelSales = document.getElementById('panel-sales');

            tabCoupons?.addEventListener('click', () => {
                tabCoupons.className = 'px-4 py-2.5 text-xs font-black border-b-2 border-pink-600 text-pink-600 transition flex items-center gap-2 cursor-pointer';
                tabSales.className = 'px-4 py-2.5 text-xs font-bold text-zinc-500 hover:text-zinc-900 border-b-2 border-transparent transition flex items-center gap-2 cursor-pointer';
                panelCoupons.classList.remove('hidden');
                panelSales.classList.add('hidden');
            });

            tabSales?.addEventListener('click', () => {
                tabSales.className = 'px-4 py-2.5 text-xs font-black border-b-2 border-pink-600 text-pink-600 transition flex items-center gap-2 cursor-pointer';
                tabCoupons.className = 'px-4 py-2.5 text-xs font-bold text-zinc-500 hover:text-zinc-900 border-b-2 border-transparent transition flex items-center gap-2 cursor-pointer';
                panelSales.classList.remove('hidden');
                panelCoupons.classList.add('hidden');
                this.loadSalesEditorList();
            });

            document.getElementById('btn-create-coupon')?.addEventListener('click', () => {
                this.openCouponEditor(null);
            });

            document.getElementById('refresh-sales-btn')?.addEventListener('click', () => {
                this.loadSalesEditorList();
            });

            await this.loadCouponsList();
        },

        async loadCouponsList() {
            const box = document.getElementById('coupons-table-box');
            if (!box) return;

            try {
                const coupons = await api.get('/api/admin/coupons');
                appState.couponsData = coupons || [];

                if (coupons.length === 0) {
                    box.innerHTML = `
                        <div class="py-16 text-center">
                            <div class="w-14 h-14 rounded-2xl bg-zinc-50 text-zinc-300 flex items-center justify-center mx-auto text-2xl mb-3">
                                <i class="ti ti-ticket"></i>
                            </div>
                            <p class="text-sm font-bold text-zinc-700">Aucun code promo actif</p>
                            <p class="text-xs text-zinc-400 mt-1">Créez des remises en pourcentage ou en montant fixe (DH) pour vos clients.</p>
                        </div>
                    `;
                    return;
                }

                box.innerHTML = `
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-zinc-50/80 text-zinc-400 font-bold uppercase tracking-wider text-[10px] border-b border-zinc-100">
                                <tr>
                                    <th class="px-6 py-3.5">Code Promo</th>
                                    <th class="px-6 py-3.5">Remise</th>
                                    <th class="px-6 py-3.5">Panier Minimum</th>
                                    <th class="px-6 py-3.5">Utilisations</th>
                                    <th class="px-6 py-3.5">Expiration</th>
                                    <th class="px-6 py-3.5">Actif / Inactif</th>
                                    <th class="px-6 py-3.5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                ${coupons.map(c => {
                                    const isExpired = c.expires_at && new Date(c.expires_at) < new Date();
                                    const isExhausted = c.max_uses && c.used_count >= c.max_uses;

                                    return `
                                        <tr class="hover:bg-pink-50/30 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2">
                                                    <span class="font-mono font-black text-sm px-2.5 py-1 rounded-xl bg-pink-50 text-pink-700 border border-pink-200/60 tracking-wider">${c.code}</span>
                                                    <button type="button" onclick="navigator.clipboard.writeText('${c.code}'); window.adminApp.showToast('Code ${c.code} copié !')" class="text-zinc-400 hover:text-zinc-700 cursor-pointer" title="Copier">
                                                        <i class="ti ti-copy text-xs"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 font-bold text-zinc-900">
                                                ${c.type === 'percent' ? `
                                                    <span class="text-pink-600 font-black text-sm">-${parseFloat(c.value)}%</span>
                                                ` : `
                                                    <span class="text-pink-600 font-black text-sm">-${formatDH(c.value)}</span>
                                                `}
                                            </td>
                                            <td class="px-6 py-4 text-zinc-600 font-medium">
                                                ${parseFloat(c.min_order_amount) > 0 ? formatDH(c.min_order_amount) : 'Sans minimum'}
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-zinc-800">${c.used_count} ${c.max_uses ? `/ ${c.max_uses}` : 'utilisations'}</div>
                                                ${isExhausted ? '<span class="text-[10px] text-red-500 font-bold">Quota épuisé</span>' : ''}
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-zinc-600">${c.expires_at ? formatDate(c.expires_at) : 'Illimité'}</div>
                                                ${isExpired ? '<span class="text-[10px] text-red-500 font-bold">Expiré</span>' : ''}
                                            </td>
                                            <td class="px-6 py-4">
                                                <button type="button" data-toggle-coupon="${c.id}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold cursor-pointer transition ${
                                                    c.is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-zinc-100 text-zinc-500 hover:bg-zinc-200'
                                                }" title="${c.is_active ? 'Cliquez pour désactiver' : 'Cliquez pour activer'}">
                                                    <span class="w-1.5 h-1.5 rounded-full ${c.is_active ? 'bg-emerald-500' : 'bg-zinc-400'}"></span>
                                                    <span>${c.is_active ? 'Actif' : 'Désactivé'}</span>
                                                </button>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <button type="button" data-toggle-coupon="${c.id}" class="p-2 rounded-xl transition cursor-pointer ${
                                                        c.is_active
                                                            ? 'bg-emerald-50 hover:bg-amber-500 hover:text-white text-emerald-700 border border-emerald-200/80 hover:border-amber-500'
                                                            : 'bg-zinc-100 hover:bg-emerald-600 hover:text-white text-zinc-400'
                                                    }" title="${c.is_active ? 'Désactiver le code promo' : 'Activer le code promo'}">
                                                        <i class="ti ${c.is_active ? 'ti-power' : 'ti-power'} text-sm"></i>
                                                    </button>
                                                    <button type="button" data-coupon-edit="${c.id}" class="p-2 rounded-xl bg-zinc-100 hover:bg-zinc-900 hover:text-white text-zinc-700 transition cursor-pointer" title="Modifier">
                                                        <i class="ti ti-edit text-sm"></i>
                                                    </button>
                                                    <button type="button" data-coupon-delete="${c.id}" data-code="${c.code}" class="p-2 rounded-xl bg-red-50 hover:bg-red-600 hover:text-white text-red-600 transition cursor-pointer" title="Supprimer">
                                                        <i class="ti ti-trash text-sm"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    `;
                                }).join('')}
                            </tbody>
                        </table>
                    </div>
                `;

                // Wire toggle buttons
                box.querySelectorAll('[data-toggle-coupon]').forEach(btn => {
                    btn.addEventListener('click', async () => {
                        const id = btn.dataset.toggleCoupon;
                        const origHtml = btn.innerHTML;
                        btn.disabled = true;
                        btn.innerHTML = '<i class="ti ti-loader-2 animate-spin text-sm"></i>';
                        try {
                            const res = await api.post(`/api/admin/coupons/${id}/toggle`);
                            toast.show(`Code promo ${res.code} ${res.is_active ? 'activé' : 'désactivé'}.`);
                            await this.loadCouponsList();
                        } catch (err) {
                            toast.show(err.message || 'Erreur', 'error');
                            btn.disabled = false;
                            btn.innerHTML = origHtml;
                        }
                    });
                });

                // Wire edit & delete
                box.querySelectorAll('[data-coupon-edit]').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = btn.dataset.couponEdit;
                        const coupon = coupons.find(c => String(c.id) === String(id));
                        this.openCouponEditor(coupon);
                    });
                });

                box.querySelectorAll('[data-coupon-delete]').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = btn.dataset.couponDelete;
                        const code = btn.dataset.code;
                        modalSystem.confirm({
                            title: 'Supprimer le code promo',
                            message: `Êtes-vous sûr de vouloir supprimer définitivement le code "${code}" ?`,
                            confirmText: 'Supprimer le code',
                            type: 'danger',
                            onConfirm: async () => {
                                await api.delete(`/api/admin/coupons/${id}`);
                                toast.show(`Code promo "${code}" supprimé.`);
                                this.loadCouponsList();
                            }
                        });
                    });
                });

            } catch (err) {
                box.innerHTML = `
                    <div class="py-12 text-center text-red-500 text-xs">
                        <i class="ti ti-alert-circle text-lg mb-1 block"></i>
                        Erreur : ${err.message}
                    </div>
                `;
            }
        },

        openCouponEditor(coupon = null) {
            const isEditing = !!coupon;
            const data = coupon || {
                code: '',
                type: 'percent',
                value: '',
                min_order_amount: 0,
                max_uses: '',
                expires_at: '',
                is_active: true
            };

            const expiryFormatted = data.expires_at ? data.expires_at.split('T')[0] : '';

            const currentType = data.type || 'percent';
            const currentTypeLabel = currentType === 'percent' ? '% Pourcentage' : 'DH Montant fixe';

            const html = `
                <div class="p-6 md:p-7">
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-zinc-100">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center text-lg">
                                <i class="ti ${isEditing ? 'ti-edit' : 'ti-plus'}"></i>
                            </div>
                            <h3 class="text-sm font-bold text-zinc-900">${isEditing ? 'Modifier le code promo' : 'Nouveau code promo'}</h3>
                        </div>
                        <button type="button" data-close-modal class="text-zinc-400 hover:text-zinc-700 cursor-pointer">
                            <i class="ti ti-x text-base"></i>
                        </button>
                    </div>

                    <form id="coupon-modal-form" class="space-y-4 text-xs">
                        <div>
                            <label class="block font-bold text-zinc-700 mb-1">Code Promo <span class="text-pink-600">*</span></label>
                            <input type="text" name="code" value="${data.code || ''}" required placeholder="ex: SUMMER20" class="input-luxury w-full font-mono uppercase text-sm font-black text-pink-600 tracking-wider" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-zinc-700 mb-1">Type de remise <span class="text-pink-600">*</span></label>
                                <input type="hidden" name="type" id="coupon-type-id" value="${currentType}" required />
                                <div class="relative custom-form-dropdown" id="coupon-type-dropdown">
                                    <button type="button" id="coupon-type-trigger" class="w-full px-4 py-3 bg-white border border-zinc-200 hover:border-pink-300 rounded-2xl text-xs font-semibold text-zinc-900 flex items-center justify-between cursor-pointer focus:outline-none focus:border-pink-500 focus:ring-4 focus:ring-pink-500/12 transition-all">
                                        <span id="coupon-type-label" class="font-bold text-zinc-900">${currentTypeLabel}</span>
                                        <i class="ti ti-chevron-down text-zinc-400 transition-transform duration-200 chevron-icon"></i>
                                    </button>
                                    <div id="coupon-type-panel" class="custom-dropdown-panel absolute top-full left-0 right-0 mt-2 bg-white/95 backdrop-blur-md rounded-2xl shadow-[0_15px_35px_rgba(0,0,0,0.14)] border border-zinc-100 p-1.5 z-50 hidden">
                                        <button type="button" class="coupon-type-opt w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all cursor-pointer ${currentType === 'percent' ? 'bg-pink-50 text-pink-600' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black'}" data-value="percent" data-label="% Pourcentage">
                                            <span>% Pourcentage</span>
                                            <i class="ti ti-check text-xs ${currentType === 'percent' ? '' : 'hidden'}"></i>
                                        </button>
                                        <button type="button" class="coupon-type-opt w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all cursor-pointer ${currentType === 'fixed' ? 'bg-pink-50 text-pink-600' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black'}" data-value="fixed" data-label="DH Montant fixe">
                                            <span>DH Montant fixe</span>
                                            <i class="ti ti-check text-xs ${currentType === 'fixed' ? '' : 'hidden'}"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block font-bold text-zinc-700 mb-1">Valeur de la réduction <span class="text-pink-600">*</span></label>
                                <input type="number" step="0.01" min="0.01" name="value" value="${data.value || ''}" required placeholder="ex: 20 ou 50" class="input-luxury w-full font-black text-zinc-900" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-zinc-700 mb-1">Panier minimum (DH)</label>
                                <input type="number" step="0.01" min="0" name="min_order_amount" value="${data.min_order_amount ?? 0}" placeholder="0" class="input-luxury w-full" />
                            </div>
                            <div>
                                <label class="block font-bold text-zinc-700 mb-1">Max utilisations <span class="text-zinc-400 font-normal">(vide = illimité)</span></label>
                                <input type="number" min="1" name="max_uses" value="${data.max_uses || ''}" placeholder="Illimité" class="input-luxury w-full" />
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-zinc-700 mb-1">Date d'expiration <span class="text-zinc-400 font-normal">(optionnel)</span></label>
                            <input type="date" name="expires_at" value="${expiryFormatted}" class="input-luxury w-full" />
                        </div>

                        <div>
                            <label class="flex items-center gap-2 p-3 bg-zinc-50 rounded-2xl border border-zinc-200 cursor-pointer">
                                <input type="checkbox" name="is_active" ${data.is_active ? 'checked' : ''} class="w-4 h-4 rounded accent-pink-600" />
                                <span class="font-bold text-zinc-800">Code promo actif immédiatement</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-100 mt-5">
                            <button type="button" data-close-modal class="btn-pill-secondary btn-pill-sm cursor-pointer">
                                Annuler
                            </button>
                            <button type="submit" id="coupon-submit-btn" class="btn-pill-primary btn-pill-sm">
                                <i class="ti ti-check"></i>
                                <span>${isEditing ? 'Enregistrer' : 'Créer le code'}</span>
                            </button>
                        </div>
                    </form>
                </div>
            `;

            modalSystem.openModal(html, {
                maxWidth: 'max-w-md',
                onMount: (box) => {
                    // Type custom dropdown wiring
                    const typeTrigger = box.querySelector('#coupon-type-trigger');
                    const typePanel = box.querySelector('#coupon-type-panel');
                    const typeLabel = box.querySelector('#coupon-type-label');
                    const typeInput = box.querySelector('#coupon-type-id');
                    const typeChevron = typeTrigger?.querySelector('.chevron-icon');

                    typeTrigger?.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const isHidden = typePanel.classList.toggle('hidden');
                        typeChevron?.classList.toggle('rotate-180', !isHidden);
                    });

                    box.querySelectorAll('.coupon-type-opt').forEach(opt => {
                        opt.addEventListener('click', (e) => {
                            e.stopPropagation();
                            const val = opt.dataset.value;
                            const lbl = opt.dataset.label;
                            if (typeInput) typeInput.value = val;
                            if (typeLabel) typeLabel.textContent = lbl;

                            box.querySelectorAll('.coupon-type-opt').forEach(o => {
                                const isMatch = o.dataset.value === val;
                                o.classList.toggle('bg-pink-50', isMatch);
                                o.classList.toggle('text-pink-600', isMatch);
                                o.classList.toggle('text-zinc-700', !isMatch);
                                const check = o.querySelector('.ti-check');
                                if (check) check.classList.toggle('hidden', !isMatch);
                            });

                            typePanel.classList.add('hidden');
                            typeChevron?.classList.remove('rotate-180');
                        });
                    });

                    const form = box.querySelector('#coupon-modal-form');
                    form?.addEventListener('submit', async (e) => {
                        e.preventDefault();
                        const btn = box.querySelector('#coupon-submit-btn');
                        btn.disabled = true;
                        btn.innerHTML = '<i class="ti ti-loader-2 animate-spin mr-1"></i> Traitement...';

                        const payload = {
                            code: form.code.value.trim().toUpperCase(),
                            type: form.type.value,
                            value: parseFloat(form.value.value),
                            min_order_amount: form.min_order_amount.value ? parseFloat(form.min_order_amount.value) : 0,
                            max_uses: form.max_uses.value ? parseInt(form.max_uses.value, 10) : null,
                            expires_at: form.expires_at.value ? form.expires_at.value : null,
                            is_active: form.is_active.checked
                        };

                        try {
                            if (isEditing) {
                                await api.put(`/api/admin/coupons/${data.id}`, payload);
                                toast.show(`Code promo "${payload.code}" mis à jour.`);
                            } else {
                                await api.post('/api/admin/coupons', payload);
                                toast.show(`Code promo "${payload.code}" créé.`);
                            }
                            modalSystem.closeModal();
                            this.loadCouponsList();
                        } catch (err) {
                            toast.show(err.message || 'Erreur', 'error');
                            btn.disabled = false;
                            btn.textContent = isEditing ? 'Enregistrer' : 'Créer le code';
                        }
                    });
                }
            });
        },

        async loadSalesEditorList() {
            const box = document.getElementById('sales-editor-box');
            if (!box) return;

            try {
                const res = await api.get('/api/admin/products?status=active&per_page=100');
                const products = res.data || [];

                if (products.length === 0) {
                    box.innerHTML = '<p class="text-xs text-zinc-400 text-center py-6">Aucun produit actif disponible.</p>';
                    return;
                }

                box.innerHTML = `
                    <table class="w-full text-left text-xs">
                        <thead class="bg-zinc-50 text-zinc-400 font-bold uppercase tracking-wider text-[10px] border-b border-zinc-100">
                            <tr>
                                <th class="px-4 py-3">Produit</th>
                                <th class="px-4 py-3">Catégorie</th>
                                <th class="px-4 py-3">Prix Initial (DH)</th>
                                <th class="px-4 py-3">Prix Soldé (DH)</th>
                                <th class="px-4 py-3">Remise Calculée</th>
                                <th class="px-4 py-3 text-right">Mettre à jour</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            ${products.map(p => {
                                const origPrice = parseFloat(p.price) || 0;
                                const salePrice = p.discounted_price ? parseFloat(p.discounted_price) : '';
                                const discountPct = (salePrice && origPrice > salePrice) ? Math.round((1 - salePrice / origPrice) * 100) : null;

                                return `
                                    <tr class="hover:bg-pink-50/20 transition-colors" id="sale-row-${p.id}">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2.5">
                                                <img src="${p.image || '/images/sdj_bum_bum_set.png'}" alt="" class="w-8 h-8 rounded-lg object-contain bg-zinc-100 border border-zinc-200" />
                                                <span class="font-bold text-zinc-900">${p.name}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-zinc-500">${p.category?.name || '—'}</td>
                                        <td class="px-4 py-3 font-bold text-zinc-900">${formatDH(origPrice)}</td>
                                        <td class="px-4 py-3">
                                            <input type="number" step="0.01" min="0" max="${origPrice - 0.01}" value="${salePrice}" placeholder="Pas de promo" class="w-28 bg-zinc-50 border border-zinc-200 rounded-xl px-2.5 py-1.5 font-black text-pink-600 text-xs focus:outline-none focus:border-pink-500 focus:bg-white sale-input" data-product-id="${p.id}" data-orig-price="${origPrice}" />
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="discount-calc-label font-bold ${discountPct ? 'text-pink-600 bg-pink-50 px-2 py-0.5 rounded-full text-[10px]' : 'text-zinc-400'}">
                                                ${discountPct ? `-${discountPct}%` : 'Aucune'}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <button type="button" data-save-sale="${p.id}" class="px-3 py-1.5 rounded-xl bg-zinc-900 hover:bg-pink-600 text-white font-bold text-xs transition cursor-pointer shadow-2xs">
                                                Enregistrer
                                            </button>
                                        </td>
                                    </tr>
                                `;
                            }).join('')}
                        </tbody>
                    </table>
                `;

                // Live calculation of discount percentage when typing
                box.querySelectorAll('.sale-input').forEach(inp => {
                    inp.addEventListener('input', () => {
                        const row = inp.closest('tr');
                        const orig = parseFloat(inp.dataset.origPrice) || 0;
                        const sale = parseFloat(inp.value);
                        const label = row.querySelector('.discount-calc-label');
                        if (sale && orig > sale) {
                            const pct = Math.round((1 - sale / orig) * 100);
                            label.textContent = `-${pct}%`;
                            label.className = 'discount-calc-label font-bold text-pink-600 bg-pink-50 px-2 py-0.5 rounded-full text-[10px]';
                        } else {
                            label.textContent = 'Aucune';
                            label.className = 'discount-calc-label font-bold text-zinc-400';
                        }
                    });
                });

                // Wire save buttons
                box.querySelectorAll('[data-save-sale]').forEach(btn => {
                    btn.addEventListener('click', async () => {
                        const id = btn.dataset.saveSale;
                        const row = document.getElementById(`sale-row-${id}`);
                        const inp = row?.querySelector('.sale-input');
                        const rawVal = inp?.value.trim();
                        const saleVal = rawVal === '' ? null : parseFloat(rawVal);
                        const product = products.find(p => String(p.id) === String(id));

                        btn.disabled = true;
                        btn.textContent = '...';

                        try {
                            const payload = {
                                ...product,
                                discounted_price: saleVal
                            };
                            await api.put(`/api/admin/products/${id}`, payload);
                            toast.show(`Prix promotionnel de "${product.name}" mis à jour.`);
                            btn.disabled = false;
                            btn.textContent = 'Enregistré ✓';
                            setTimeout(() => { btn.textContent = 'Enregistrer'; }, 1500);
                        } catch (err) {
                            toast.show(err.message || 'Erreur', 'error');
                            btn.disabled = false;
                            btn.textContent = 'Erreur';
                        }
                    });
                });

            } catch (err) {
                box.innerHTML = `<p class="text-xs text-red-500 text-center py-6">Erreur : ${err.message}</p>`;
            }
        },

        // ═════════════════════════════════════════════════════════════════════
        // VIEW 6: ORDERS
        // ═════════════════════════════════════════════════════════════════════
        async renderOrders(page = 1) {
            appState.ordersFilter.page = page;
            const filter = appState.ordersFilter;
            const orderStatusLabels = {
                '': 'Tous les statuts',
                'pending': 'En attente',
                'confirmed': 'Confirmée',
                'processing': 'En préparation',
                'shipped': 'Expédiée',
                'delivered': 'Livrée',
                'cancelled': 'Annulée'
            };
            const orderStatusLabel = orderStatusLabels[filter.status] || 'Tous les statuts';

            this.root.innerHTML = `
                <div class="space-y-6 animate-fadeIn">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-black text-zinc-900 tracking-tight">Gestion des Commandes</h1>
                            <p class="text-xs text-zinc-400 font-medium">Suivez l'expédition, le statut de livraison et les paiements à la livraison</p>
                        </div>
                    </div>

                    <!-- Search & Status Filter -->
                    <div class="bg-white rounded-3xl p-4 border border-zinc-100 shadow-sm flex flex-col md:flex-row items-center gap-3">
                        <div class="admin-search-container ${filter.search ? 'is-expanded' : ''} flex-1 w-full">
                            <i class="ti ti-search admin-search-icon"></i>
                            <input type="text" id="order-search-input" value="${filter.search || ''}" placeholder="Rechercher par nom de client, téléphone, N° de commande..." class="admin-search-input" autocomplete="off" />
                            <button type="button" id="order-search-clear" class="admin-search-clear-btn ${filter.search ? '' : 'hidden'}" title="Effacer la recherche">
                                <i class="ti ti-x text-xs"></i>
                            </button>
                        </div>
                        <div class="flex items-center gap-2.5 w-full md:w-auto shrink-0 flex-wrap sm:flex-nowrap">
                            <!-- Order Status Custom Dropdown -->
                            <div class="relative custom-admin-dropdown" id="order-status-dropdown-wrap">
                                <button type="button" class="btn-pill-secondary btn-pill-sm cursor-pointer flex items-center justify-between gap-2.5 select-none" id="order-status-trigger">
                                    <span class="text-zinc-900 font-bold">${orderStatusLabel}</span>
                                    <i class="ti ti-chevron-down text-xs text-zinc-400 transition-transform duration-200 chevron-icon"></i>
                                </button>
                                <div class="custom-dropdown-panel absolute right-0 top-full mt-2 min-w-[190px] bg-white/95 backdrop-blur-md rounded-2xl shadow-[0_20px_45px_rgba(0,0,0,0.14)] border border-zinc-100 p-1.5 z-50 hidden">
                                    ${[
                                        ['', 'Tous les statuts'],
                                        ['pending', 'En attente'],
                                        ['confirmed', 'Confirmée'],
                                        ['processing', 'En préparation'],
                                        ['shipped', 'Expédiée'],
                                        ['delivered', 'Livrée'],
                                        ['cancelled', 'Annulée']
                                    ].map(([val, lbl]) => `
                                        <button type="button" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all cursor-pointer ${filter.status === val ? 'bg-pink-50 text-pink-600' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black'}" data-order-status-val="${val}">
                                            <span>${lbl}</span>
                                            ${filter.status === val ? '<i class="ti ti-check text-xs"></i>' : ''}
                                        </button>
                                    `).join('')}
                                </div>
                            </div>

                            ${(filter.search || filter.status) ? `
                                <button type="button" id="order-reset-filters" class="btn-circle-action w-9 h-9 rounded-full bg-zinc-100 hover:bg-zinc-200 text-zinc-600 text-xs transition cursor-pointer shrink-0" title="Réinitialiser les filtres">
                                    <i class="ti ti-x text-sm"></i>
                                </button>
                            ` : ''}
                        </div>
                    </div>

                    <!-- Orders Table Container -->
                    <div id="orders-table-box" class="bg-white rounded-3xl border border-zinc-100 shadow-sm overflow-hidden">
                        <div class="flex items-center justify-center py-20">
                            <div class="w-8 h-8 border-2 border-pink-500/20 border-t-pink-500 rounded-full animate-spin"></div>
                        </div>
                    </div>
                </div>
            `;

            // Filter wiring
            const searchInput = document.getElementById('order-search-input');
            const searchContainer = searchInput?.closest('.admin-search-container');
            const searchClearBtn = document.getElementById('order-search-clear');
            let timer = null;

            searchInput?.addEventListener('focus', () => {
                searchContainer?.classList.add('is-expanded');
            });

            searchInput?.addEventListener('blur', () => {
                if (!searchInput.value.trim()) {
                    searchContainer?.classList.remove('is-expanded');
                }
            });

            searchInput?.addEventListener('input', (e) => {
                const val = e.target.value;
                if (searchClearBtn) {
                    if (val.trim().length > 0) {
                        searchClearBtn.classList.remove('hidden');
                        searchContainer?.classList.add('is-expanded');
                    } else {
                        searchClearBtn.classList.add('hidden');
                    }
                }
                clearTimeout(timer);
                timer = setTimeout(() => {
                    appState.ordersFilter.search = val;
                    appState.ordersFilter.page = 1;
                    this.loadOrdersList();
                }, 300);
            });

            searchInput?.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    if (searchInput.value) {
                        searchInput.value = '';
                        searchClearBtn?.classList.add('hidden');
                        appState.ordersFilter.search = '';
                        appState.ordersFilter.page = 1;
                        this.loadOrdersList();
                    }
                    searchInput.blur();
                    searchContainer?.classList.remove('is-expanded');
                }
            });

            searchClearBtn?.addEventListener('click', () => {
                if (searchInput) {
                    searchInput.value = '';
                    searchClearBtn.classList.add('hidden');
                    searchInput.focus();
                    appState.ordersFilter.search = '';
                    appState.ordersFilter.page = 1;
                    this.loadOrdersList();
                }
            });

            // Order status custom dropdown wiring
            const orderStatusWrap = document.getElementById('order-status-dropdown-wrap');
            const orderStatusTrigger = document.getElementById('order-status-trigger');
            const orderStatusPanel = orderStatusWrap?.querySelector('.custom-dropdown-panel');
            const orderStatusChevron = orderStatusTrigger?.querySelector('.chevron-icon');

            orderStatusTrigger?.addEventListener('click', (e) => {
                e.stopPropagation();
                document.querySelectorAll('.custom-dropdown-panel').forEach(p => {
                    if (p !== orderStatusPanel) p.classList.add('hidden');
                });
                document.querySelectorAll('.custom-admin-dropdown .chevron-icon').forEach(c => {
                    if (c !== orderStatusChevron) c.classList.remove('rotate-180');
                });
                const isHidden = orderStatusPanel.classList.toggle('hidden');
                orderStatusChevron?.classList.toggle('rotate-180', !isHidden);
            });

            orderStatusWrap?.querySelectorAll('[data-order-status-val]').forEach(opt => {
                opt.addEventListener('click', (e) => {
                    e.stopPropagation();
                    orderStatusPanel?.classList.add('hidden');
                    orderStatusChevron?.classList.remove('rotate-180');
                    appState.ordersFilter.status = opt.dataset.orderStatusVal;
                    appState.ordersFilter.page = 1;
                    this.renderOrders(1);
                });
            });

            document.getElementById('order-reset-filters')?.addEventListener('click', () => {
                appState.ordersFilter = { search: '', status: '', page: 1 };
                this.renderOrders(1);
            });

            await this.loadOrdersList();
        },

        async loadOrdersList() {
            const box = document.getElementById('orders-table-box');
            if (!box) return;

            const filter = appState.ordersFilter;
            let query = `?page=${filter.page}`;
            if (filter.search) query += `&search=${encodeURIComponent(filter.search)}`;
            if (filter.status) query += `&status=${filter.status}`;

            try {
                const data = await api.get(`/api/admin/orders${query}`);
                appState.ordersData = data;
                const orders = data.data || [];

                if (orders.length === 0) {
                    box.innerHTML = `
                        <div class="py-16 text-center">
                            <div class="w-14 h-14 rounded-2xl bg-zinc-50 text-zinc-300 flex items-center justify-center mx-auto text-2xl mb-3">
                                <i class="ti ti-shopping-bag"></i>
                            </div>
                            <p class="text-sm font-bold text-zinc-700">Aucune commande trouvée</p>
                            <p class="text-xs text-zinc-400 mt-1">Ajustez vos filtres de recherche ou attendez les prochaines commandes.</p>
                        </div>
                    `;
                    return;
                }

                box.innerHTML = `
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-zinc-50/80 text-zinc-400 font-bold uppercase tracking-wider text-[10px] border-b border-zinc-100">
                                <tr>
                                    <th class="px-6 py-3.5">N° Commande</th>
                                    <th class="px-6 py-3.5">Client & Destination</th>
                                    <th class="px-6 py-3.5">Articles</th>
                                    <th class="px-6 py-3.5">Total TTC</th>
                                    <th class="px-6 py-3.5">Statut</th>
                                    <th class="px-6 py-3.5">Date</th>
                                    <th class="px-6 py-3.5 text-right">Détails</th>
                                </tr>
                            </thead>
                                <tbody class="divide-y divide-zinc-100">
                                ${orders.map(o => {
                                    const isPreorder = (o.notes && o.notes.includes('[PRÉCOMMANDE]')) || false;
                                    return `
                                        <tr class="hover:bg-pink-50/30 transition-colors group">
                                            <td class="px-6 py-4 font-mono font-bold text-zinc-900">
                                                <div class="flex items-center gap-1.5">
                                                    <span>#${o.id}</span>
                                                    ${isPreorder ? '<span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[9px] font-black border border-amber-200">Précommande</span>' : ''}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-zinc-900">${o.customer_name}</div>
                                                <div class="text-[11px] text-zinc-400 font-medium">${o.customer_phone} • ${o.city || 'Maroc'}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center gap-1 font-semibold text-zinc-700">
                                                    <i class="ti ti-package text-zinc-400"></i>
                                                    ${o.items?.length || 0} article(s)
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 font-black text-zinc-900">${formatDH(o.total)}</td>
                                            <td class="px-6 py-4">${orderStatusBadge(o.status)}</td>
                                            <td class="px-6 py-4 text-zinc-400 font-medium">${formatDate(o.created_at)}</td>
                                            <td class="px-6 py-4 text-right">
                                                <button type="button" data-order-view="${o.id}" class="px-3.5 py-1.5 rounded-xl bg-zinc-100 hover:bg-zinc-900 hover:text-white text-zinc-800 font-bold text-xs transition cursor-pointer">
                                                    Gérer
                                                </button>
                                            </td>
                                        </tr>
                                    `;
                                }).join('')}
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    ${data.last_page > 1 ? `
                        <div class="px-6 py-4 border-t border-zinc-100 flex items-center justify-between text-xs">
                            <span class="text-zinc-400 font-medium">Page ${data.current_page} sur ${data.last_page} (${data.total} commandes)</span>
                            <div class="flex items-center gap-1.5">
                                <button type="button" ${data.current_page === 1 ? 'disabled class="opacity-40 cursor-not-allowed"' : ''} id="order-prev-page" class="px-3 py-1.5 rounded-xl border border-zinc-200 font-bold text-zinc-700 hover:bg-zinc-50 transition cursor-pointer">
                                    Précédent
                                </button>
                                <button type="button" ${data.current_page === data.last_page ? 'disabled class="opacity-40 cursor-not-allowed"' : ''} id="order-next-page" class="px-3 py-1.5 rounded-xl border border-zinc-200 font-bold text-zinc-700 hover:bg-zinc-50 transition cursor-pointer">
                                    Suivant
                                </button>
                            </div>
                        </div>
                    ` : ''}
                `;

                // Wire view detail buttons
                box.querySelectorAll('[data-order-view]').forEach(btn => {
                    btn.addEventListener('click', () => {
                        this.openOrderDetail(btn.dataset.orderView);
                    });
                });

                // Wire pagination
                document.getElementById('order-prev-page')?.addEventListener('click', () => {
                    if (data.current_page > 1) {
                        this.renderOrders(data.current_page - 1);
                    }
                });

                document.getElementById('order-next-page')?.addEventListener('click', () => {
                    if (data.current_page < data.last_page) {
                        this.renderOrders(data.current_page + 1);
                    }
                });

            } catch (err) {
                box.innerHTML = `
                    <div class="py-12 text-center text-red-500 text-xs">
                        <i class="ti ti-alert-circle text-lg mb-1 block"></i>
                        Erreur : ${err.message}
                    </div>
                `;
            }
        },

        async openOrderDetail(orderId) {
            try {
                const order = await api.get(`/api/admin/orders/${orderId}`);

                const cleanPhone = (order.customer_phone || '').replace(/[^0-9]/g, '');
                const whatsappUrl = cleanPhone ? `https://wa.me/${cleanPhone.startsWith('0') ? '212' + cleanPhone.substring(1) : cleanPhone}` : '#';
                const isPreorder = (order.notes && order.notes.includes('[PRÉCOMMANDE]')) || false;

                const html = `
                    <div class="h-full flex flex-col bg-white">
                        <!-- Header -->
                        <div class="px-6 py-4 border-b border-zinc-100 flex items-center justify-between bg-zinc-50/50 shrink-0">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl ${isPreorder ? 'bg-amber-50 text-amber-600' : 'bg-pink-50 text-pink-600'} flex items-center justify-center text-lg">
                                    <i class="ti ${isPreorder ? 'ti-clock-hour-4' : 'ti-shopping-bag'}"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-sm font-bold text-zinc-900">Commande #${order.id}</h3>
                                        ${isPreorder ? '<span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[9px] font-black border border-amber-200">Précommande</span>' : ''}
                                    </div>
                                    <p class="text-[11px] text-zinc-400 font-medium">Reçue le ${formatDate(order.created_at)}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                ${orderStatusBadge(order.status)}
                                <button type="button" data-close-drawer class="p-2 rounded-xl text-zinc-400 hover:text-zinc-800 hover:bg-zinc-100 transition cursor-pointer">
                                    <i class="ti ti-x text-base"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Scrollable Body -->
                        <div class="flex-1 overflow-y-auto p-6 space-y-6 text-xs">
                            ${isPreorder ? `
                                <!-- Preorder Highlight Banner -->
                                <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200/90 text-amber-900 text-xs font-bold flex items-center gap-3 shadow-2xs">
                                    <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center text-lg shrink-0">
                                        <i class="ti ti-clock-hour-4"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block font-black text-amber-900 uppercase tracking-wider text-[11px]">Commande en Précommande</span>
                                        <p class="text-[11px] text-amber-800 font-medium mt-0.5">Cette commande a été passée en précommande pour un produit temporairement indisponible. Notre équipe contactera le client dès que le stock est disponible.</p>
                                    </div>
                                </div>
                            ` : ''}
                            <!-- Status Transition Controller Card -->
                            <div class="bg-gradient-to-br from-pink-50/60 to-rose-50/40 rounded-2xl p-4 border border-pink-100 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-zinc-800">Changer le statut de la commande</span>
                                    <span class="text-[11px] font-medium text-pink-600">Mise à jour en direct</span>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    ${[
                                        ['pending', 'En attente', 'ti-clock'],
                                        ['confirmed', 'Confirmée', 'ti-check'],
                                        ['processing', 'Préparation', 'ti-box'],
                                        ['shipped', 'Expédiée', 'ti-truck'],
                                        ['delivered', 'Livrée', 'ti-circle-check'],
                                        ['cancelled', 'Annulée', 'ti-ban'],
                                    ].map(([val, label, icon]) => `
                                        <button type="button" data-set-status="${val}" class="px-2.5 py-2 rounded-xl border text-[11px] font-bold flex items-center justify-center gap-1.5 transition cursor-pointer ${
                                            order.status === val
                                                ? 'bg-zinc-900 text-white border-zinc-900 shadow-sm'
                                                : 'bg-white text-zinc-700 border-zinc-200 hover:border-pink-300 hover:text-pink-600'
                                        }">
                                            <i class="ti ${icon} text-xs"></i>
                                            <span>${label}</span>
                                        </button>
                                    `).join('')}
                                </div>
                            </div>

                            <!-- Customer & Delivery Information Card -->
                            <div class="bg-zinc-50 rounded-2xl p-4 border border-zinc-200/80 space-y-3">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-bold text-zinc-900">Coordonnées du client</h4>
                                    <div class="flex items-center gap-2">
                                        ${order.customer_phone ? `
                                            <a href="${whatsappUrl}" target="_blank" class="px-2.5 py-1 rounded-lg bg-emerald-500 text-white font-bold text-[10px] flex items-center gap-1 hover:bg-emerald-600 transition">
                                                <i class="ti ti-brand-whatsapp text-xs"></i> WhatsApp
                                            </a>
                                            <a href="tel:${order.customer_phone}" class="px-2.5 py-1 rounded-lg bg-zinc-200 text-zinc-800 font-bold text-[10px] flex items-center gap-1 hover:bg-zinc-300 transition">
                                                <i class="ti ti-phone text-xs"></i> Appeler
                                            </a>
                                        ` : ''}
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3 text-xs">
                                    <div>
                                        <span class="text-zinc-400 text-[11px]">Nom complet</span>
                                        <div class="font-bold text-zinc-900 mt-0.5">${order.customer_name}</div>
                                    </div>
                                    <div>
                                        <span class="text-zinc-400 text-[11px]">Téléphone</span>
                                        <div class="font-bold text-zinc-900 mt-0.5 font-mono">${order.customer_phone}</div>
                                    </div>
                                    ${order.customer_email ? `
                                        <div class="col-span-2">
                                            <span class="text-zinc-400 text-[11px]">Email</span>
                                            <div class="font-medium text-zinc-900 mt-0.5">${order.customer_email}</div>
                                        </div>
                                    ` : ''}
                                    <div class="col-span-2">
                                        <span class="text-zinc-400 text-[11px]">Adresse de livraison</span>
                                        <div class="font-medium text-zinc-900 mt-0.5">${order.shipping_address}, <span class="font-bold text-zinc-900">${order.city || 'Maroc'}</span></div>
                                    </div>
                                    ${order.notes ? `
                                        <div class="col-span-2 bg-amber-50 p-2.5 rounded-xl border border-amber-200 text-amber-900">
                                            <span class="font-bold text-[10px] uppercase text-amber-700 block mb-0.5">Note client / Instructions</span>
                                            <p class="font-medium italic">${order.notes}</p>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>

                            <!-- Ordered Items Snapshot -->
                            <div class="space-y-3">
                                <h4 class="font-bold text-zinc-900">Articles commandés (${order.items?.length || 0})</h4>
                                <div class="bg-white rounded-2xl border border-zinc-200 overflow-hidden divide-y divide-zinc-100">
                                    ${(order.items || []).map(item => `
                                        <div class="p-3.5 flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-xl bg-zinc-50 border border-zinc-100 overflow-hidden shrink-0 flex items-center justify-center p-1">
                                                <img src="${item.product_image || '/images/sdj_bum_bum_set.png'}" alt="${item.product_name}" class="w-full h-full object-contain" onerror="this.src='/images/sdj_bum_bum_set.png'" />
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="font-bold text-zinc-900 truncate">${item.product_name}</div>
                                                <div class="text-[11px] text-zinc-400 font-medium">${item.variant || 'Format Standard'} • Qté : <span class="font-bold text-zinc-700">${item.quantity}</span></div>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-black text-zinc-900">${formatDH(item.subtotal)}</div>
                                                <div class="text-[10px] text-zinc-400">${formatDH(item.unit_price)} / u</div>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>

                            <!-- Financial Summary Breakdown -->
                            <div class="bg-zinc-50 rounded-2xl p-4 border border-zinc-200/80 space-y-2">
                                <div class="flex justify-between text-zinc-600">
                                    <span>Sous-total articles</span>
                                    <span class="font-bold text-zinc-900">${formatDH(order.subtotal)}</span>
                                </div>
                                <div class="flex justify-between text-zinc-600">
                                    <span>Frais de livraison</span>
                                    <span class="font-bold ${parseFloat(order.shipping_cost) === 0 ? 'text-emerald-600' : 'text-zinc-900'}">
                                        ${parseFloat(order.shipping_cost) === 0 ? 'GRATUITE' : formatDH(order.shipping_cost)}
                                    </span>
                                </div>
                                ${parseFloat(order.discount_amount) > 0 ? `
                                    <div class="flex justify-between text-emerald-600 font-bold">
                                        <span>Code promo appliqué (${order.coupon_code || 'PROMO'})</span>
                                        <span>-${formatDH(order.discount_amount)}</span>
                                    </div>
                                ` : ''}
                                <div class="pt-2 border-t border-zinc-200 flex justify-between text-sm font-black text-zinc-900">
                                    <span>Total TTC (Paiement à la livraison)</span>
                                    <span class="text-pink-600 text-base">${formatDH(order.total)}</span>
                                </div>
                            </div>

                            <!-- Tracking Timestamps -->
                            <div class="text-[11px] text-zinc-400 space-y-1">
                                <div>• Commande enregistrée le : <span class="font-medium text-zinc-600">${formatDate(order.created_at)}</span></div>
                                ${order.confirmed_at ? `<div>• Confirmée le : <span class="font-medium text-zinc-600">${formatDate(order.confirmed_at)}</span></div>` : ''}
                                ${order.shipped_at ? `<div>• Expédiée le : <span class="font-medium text-zinc-600">${formatDate(order.shipped_at)}</span></div>` : ''}
                                ${order.delivered_at ? `<div>• Livrée le : <span class="font-medium text-zinc-600">${formatDate(order.delivered_at)}</span></div>` : ''}
                            </div>
                        </div>
                    </div>
                `;

                modalSystem.openDrawer(html, {
                    maxWidth: 'max-w-xl',
                    onMount: (panel) => {
                        panel.querySelectorAll('[data-set-status]').forEach(btn => {
                            btn.addEventListener('click', async () => {
                                const newStatus = btn.dataset.setStatus;
                                try {
                                    await api.patch(`/api/admin/orders/${orderId}/status`, { status: newStatus });
                                    toast.show(`Statut de la commande #${orderId} mis à jour : ${newStatus}`);
                                    modalSystem.closeDrawer();
                                    if (appState.currentView === 'orders') {
                                        this.loadOrdersList();
                                    } else if (appState.currentView === 'dashboard') {
                                        this.renderDashboard();
                                    }
                                } catch (err) {
                                    toast.show(err.message || 'Erreur lors de la mise à jour', 'error');
                                }
                            });
                        });
                    }
                });

            } catch (err) {
                toast.show(err.message || 'Impossible d\'ouvrir la commande', 'error');
            }
        },

        // ═════════════════════════════════════════════════════════════════════
        // VIEW 7: CONTACT MESSAGES INBOX
        // ═════════════════════════════════════════════════════════════════════
        async renderMessages(page = 1) {
            appState.messagesFilter.page = page;

            this.root.innerHTML = `
                <div class="space-y-6 animate-fadeIn">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-black text-zinc-900 tracking-tight">Messagerie & Support Client</h1>
                            <p class="text-xs text-zinc-400 font-medium">Consultez les demandes envoyées depuis le formulaire de contact</p>
                        </div>
                    </div>

                    <!-- Messages Table Container -->
                    <div id="messages-table-box" class="bg-white rounded-3xl border border-zinc-100 shadow-sm overflow-hidden">
                        <div class="flex items-center justify-center py-20">
                            <div class="w-8 h-8 border-2 border-pink-500/20 border-t-pink-500 rounded-full animate-spin"></div>
                        </div>
                    </div>
                </div>
            `;

            await this.loadMessagesList();
        },

        async loadMessagesList() {
            const box = document.getElementById('messages-table-box');
            if (!box) return;

            try {
                const data = await api.get(`/api/admin/messages?page=${appState.messagesFilter.page}`);
                appState.messagesData = data;
                const messages = data.data || [];

                if (data.unread_messages !== undefined) {
                    this.updateSidebarBadges(undefined, data.unread_messages);
                }

                if (messages.length === 0) {
                    box.innerHTML = `
                        <div class="py-16 text-center">
                            <div class="w-14 h-14 rounded-2xl bg-zinc-50 text-zinc-300 flex items-center justify-center mx-auto text-2xl mb-3">
                                <i class="ti ti-mail"></i>
                            </div>
                            <p class="text-sm font-bold text-zinc-700">Aucun message de contact</p>
                            <p class="text-xs text-zinc-400 mt-1">Les questions de vos visiteurs apparaîtront ici.</p>
                        </div>
                    `;
                    return;
                }

                box.innerHTML = `
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-zinc-50/80 text-zinc-400 font-bold uppercase tracking-wider text-[10px] border-b border-zinc-100">
                                <tr>
                                    <th class="px-6 py-3.5">Statut</th>
                                    <th class="px-6 py-3.5">Expéditeur</th>
                                    <th class="px-6 py-3.5">Sujet</th>
                                    <th class="px-6 py-3.5">Date</th>
                                    <th class="px-6 py-3.5 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                ${messages.map(m => `
                                    <tr class="hover:bg-pink-50/30 transition-colors ${!m.is_read ? 'bg-pink-50/20 font-bold' : ''}">
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold ${
                                                !m.is_read ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-zinc-100 text-zinc-500'
                                            }">
                                                <span class="w-1.5 h-1.5 rounded-full ${!m.is_read ? 'bg-red-500 animate-pulse' : 'bg-zinc-400'}"></span>
                                                <span>${!m.is_read ? 'Non lu' : 'Lu'}</span>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-zinc-900 font-bold">${m.name}</div>
                                            <div class="text-[11px] text-zinc-400 font-normal">${m.email}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-zinc-800 font-bold truncate max-w-sm">${m.subject || 'Demande d\'information'}</div>
                                            <div class="text-[11px] text-zinc-400 font-normal truncate max-w-sm mt-0.5">${m.message}</div>
                                        </td>
                                        <td class="px-6 py-4 text-zinc-400 font-medium">${formatDate(m.created_at)}</td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <button type="button" data-read-msg="${m.id}" class="btn-pill-secondary btn-pill-sm cursor-pointer">
                                                    Lire
                                                </button>
                                                ${!m.is_read ? `
                                                    <button type="button" data-mark-read="${m.id}" class="btn-circle-action w-8 h-8 rounded-full border border-zinc-200 text-zinc-400 hover:text-emerald-600 hover:border-emerald-300 transition cursor-pointer" title="Marquer comme lu">
                                                        <i class="ti ti-check text-base"></i>
                                                    </button>
                                                ` : ''}
                                                <button type="button" data-delete-msg="${m.id}" data-name="${m.name}" class="btn-circle-action w-8 h-8 rounded-full border border-zinc-200 text-zinc-400 hover:text-red-600 hover:border-red-300 transition cursor-pointer" title="Supprimer ce message">
                                                    <i class="ti ti-trash text-sm"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    ${data.last_page > 1 ? `
                        <div class="px-6 py-4 border-t border-zinc-100 flex items-center justify-between text-xs">
                            <span class="text-zinc-400 font-medium">Page ${data.current_page} sur ${data.last_page}</span>
                            <div class="flex items-center gap-1.5">
                                <button type="button" ${data.current_page === 1 ? 'disabled class="opacity-40 cursor-not-allowed"' : ''} id="msg-prev-page" class="btn-pill-secondary btn-pill-sm">
                                    Précédent
                                </button>
                                <button type="button" ${data.current_page === data.last_page ? 'disabled class="opacity-40 cursor-not-allowed"' : ''} id="msg-next-page" class="btn-pill-secondary btn-pill-sm">
                                    Suivant
                                </button>
                            </div>
                        </div>
                    ` : ''}
                `;

                // Wire read modal buttons
                box.querySelectorAll('[data-read-msg]').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = btn.dataset.readMsg;
                        const msg = messages.find(m => String(m.id) === String(id));
                        this.openMessageModal(msg);
                    });
                });

                // Wire quick mark as read
                box.querySelectorAll('[data-mark-read]').forEach(btn => {
                    btn.addEventListener('click', async () => {
                        const id = btn.dataset.markRead;
                        try {
                            const res = await api.patch(`/api/admin/messages/${id}/read`);
                            toast.show('Message marqué comme lu.');
                            if (res?.unread_messages !== undefined) {
                                this.updateSidebarBadges(undefined, res.unread_messages);
                            }
                            this.loadMessagesList();
                        } catch (err) {
                            toast.show(err.message || 'Erreur', 'error');
                        }
                    });
                });

                // Wire delete message
                box.querySelectorAll('[data-delete-msg]').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = btn.dataset.deleteMsg;
                        const name = btn.dataset.name;
                        modalSystem.confirm({
                            title: 'Supprimer le message',
                            message: `Voulez-vous supprimer le message reçu de "${name}" ?`,
                            confirmText: 'Supprimer',
                            type: 'danger',
                            onConfirm: async () => {
                                try {
                                    const res = await api.delete(`/api/admin/messages/${id}`);
                                    toast.show('Message supprimé.');
                                    if (res?.unread_messages !== undefined) {
                                        this.updateSidebarBadges(undefined, res.unread_messages);
                                    }
                                    this.loadMessagesList();
                                } catch (err) {
                                    toast.show(err.message || 'Erreur', 'error');
                                }
                            }
                        });
                    });
                });

                // Wire pagination
                document.getElementById('msg-prev-page')?.addEventListener('click', () => {
                    if (data.current_page > 1) {
                        this.renderMessages(data.current_page - 1);
                    }
                });

                document.getElementById('msg-next-page')?.addEventListener('click', () => {
                    if (data.current_page < data.last_page) {
                        this.renderMessages(data.current_page + 1);
                    }
                });

            } catch (err) {
                box.innerHTML = `
                    <div class="py-12 text-center text-red-500 text-xs">
                        <i class="ti ti-alert-circle text-lg mb-1 block"></i>
                        Erreur : ${err.message}
                    </div>
                `;
            }
        },

        openMessageModal(msg) {
            if (!msg) return;

            const html = `
                <div class="p-6 md:p-7">
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-zinc-100">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center text-lg">
                                <i class="ti ti-mail"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-zinc-900">${msg.subject || 'Message de contact'}</h3>
                                <p class="text-[11px] text-zinc-400 font-medium">Reçu le ${formatDate(msg.created_at)}</p>
                            </div>
                        </div>
                        <button type="button" data-close-modal class="text-zinc-400 hover:text-zinc-700 cursor-pointer">
                            <i class="ti ti-x text-base"></i>
                        </button>
                    </div>

                    <div class="space-y-4 text-xs">
                        <div class="bg-zinc-50 p-3.5 rounded-2xl border border-zinc-200/80 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-zinc-900 text-sm block">${msg.name}</span>
                                <a href="mailto:${msg.email}" class="text-pink-600 hover:underline font-semibold text-xs">${msg.email}</a>
                            </div>
                            <a href="mailto:${msg.email}?subject=${encodeURIComponent('Re: ' + (msg.subject || ''))}" class="btn-pill-primary btn-pill-sm">
                                <i class="ti ti-send text-xs"></i>
                                <span>Répondre</span>
                            </a>
                        </div>

                        <div>
                            <span class="text-zinc-400 font-bold uppercase tracking-wider text-[10px] block mb-1.5">Contenu du message :</span>
                            <div class="p-4 rounded-2xl bg-zinc-50 border border-zinc-200 text-zinc-800 leading-relaxed font-medium whitespace-pre-wrap">
                                ${msg.message}
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-zinc-100 mt-5">
                            <span class="text-[11px] text-zinc-400">
                                Statut : <span class="font-bold ${msg.is_read ? 'text-zinc-600' : 'text-red-600'}">${msg.is_read ? 'Déjà lu' : 'Non lu'}</span>
                            </span>
                            <div class="flex items-center gap-2">
                                ${!msg.is_read ? `
                                    <button type="button" id="msg-mark-read-btn" class="btn-pill-primary btn-pill-sm">
                                        <i class="ti ti-check"></i>
                                        <span>Marquer comme lu</span>
                                    </button>
                                ` : ''}
                                <button type="button" data-close-modal class="btn-pill-secondary btn-pill-sm cursor-pointer">
                                    Fermer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            modalSystem.openModal(html, {
                maxWidth: 'max-w-lg',
                onMount: (box) => {
                    box.querySelector('#msg-mark-read-btn')?.addEventListener('click', async () => {
                        try {
                            const res = await api.patch(`/api/admin/messages/${msg.id}/read`);
                            toast.show('Message marqué comme lu.');
                            if (res?.unread_messages !== undefined) {
                                this.updateSidebarBadges(undefined, res.unread_messages);
                            }
                            modalSystem.closeModal();
                            this.loadMessagesList();
                        } catch (err) {
                            toast.show(err.message || 'Erreur', 'error');
                        }
                    });
                }
            });
        },

        // ═════════════════════════════════════════════════════════════════════
        // VIEW 7: REVIEWS MANAGEMENT (AVIS CLIENTS)
        // ═════════════════════════════════════════════════════════════════════
        async renderReviews() {
            this.root.innerHTML = `
                <div class="space-y-6 animate-fadeIn">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-black text-zinc-900 tracking-tight">Avis & Témoignages Clients</h1>
                            <p class="text-xs text-zinc-400 font-medium">Gérez les avis affichés en temps réel sur le storefront et personnalisez leur visibilité</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" id="btn-create-review" class="btn-pill-primary btn-pill-sm shrink-0 cursor-pointer">
                                <i class="ti ti-plus text-sm"></i>
                                <span>Ajouter un avis</span>
                            </button>
                        </div>
                    </div>

                    <!-- Summary Stats Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4" id="reviews-stats-cards">
                        <div class="bg-white rounded-3xl p-5 border border-zinc-100 shadow-sm flex items-center justify-between">
                            <div>
                                <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Total Avis</span>
                                <div class="text-2xl font-black text-zinc-900 mt-1" id="stat-total-reviews">--</div>
                            </div>
                            <div class="w-11 h-11 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center text-xl">
                                <i class="ti ti-star"></i>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl p-5 border border-zinc-100 shadow-sm flex items-center justify-between">
                            <div>
                                <span class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider">Visibles en vitrine</span>
                                <div class="text-2xl font-black text-emerald-700 mt-1" id="stat-visible-reviews">--</div>
                            </div>
                            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                                <i class="ti ti-eye"></i>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl p-5 border border-zinc-100 shadow-sm flex items-center justify-between">
                            <div>
                                <span class="text-[11px] font-bold text-amber-600 uppercase tracking-wider">Masqués du site</span>
                                <div class="text-2xl font-black text-amber-700 mt-1" id="stat-hidden-reviews">--</div>
                            </div>
                            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                                <i class="ti ti-eye-off"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Toolbar -->
                    <div class="bg-white rounded-3xl p-4 border border-zinc-100 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                        <!-- Search Box -->
                        <div class="relative w-full md:w-80">
                            <i class="ti ti-search absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-400 text-sm"></i>
                            <input type="text" id="review-search-input" value="${appState.reviewsFilter.search || ''}" placeholder="Rechercher par cliente, produit ou texte..." class="input-luxury w-full pl-9 pr-4 text-xs py-2" />
                        </div>

                        <!-- Status Filter Tabs -->
                        <div class="flex items-center gap-1.5 bg-zinc-100/80 p-1 rounded-2xl w-full md:w-auto overflow-x-auto">
                            <button type="button" data-review-status="" class="review-status-tab px-3.5 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer ${!appState.reviewsFilter.status ? 'bg-white text-zinc-900 shadow-xs' : 'text-zinc-500 hover:text-zinc-900'}">
                                Tous
                            </button>
                            <button type="button" data-review-status="visible" class="review-status-tab px-3.5 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer ${appState.reviewsFilter.status === 'visible' ? 'bg-white text-zinc-900 shadow-xs' : 'text-zinc-500 hover:text-zinc-900'}">
                                Visibles
                            </button>
                            <button type="button" data-review-status="hidden" class="review-status-tab px-3.5 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer ${appState.reviewsFilter.status === 'hidden' ? 'bg-white text-zinc-900 shadow-xs' : 'text-zinc-500 hover:text-zinc-900'}">
                                Masqués
                            </button>
                        </div>
                    </div>

                    <!-- Reviews List Container -->
                    <div id="reviews-list-box" class="space-y-4">
                        <div class="flex items-center justify-center py-20 bg-white rounded-3xl border border-zinc-100">
                            <div class="w-8 h-8 border-2 border-pink-500/20 border-t-pink-500 rounded-full animate-spin"></div>
                        </div>
                    </div>
                </div>
            `;

            // Wire Toolbar Events
            const searchInput = document.getElementById('review-search-input');
            let searchTimeout;
            searchInput?.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    appState.reviewsFilter.search = e.target.value.trim();
                    this.loadReviewsList();
                }, 300);
            });

            document.querySelectorAll('.review-status-tab').forEach(tab => {
                tab.addEventListener('click', () => {
                    document.querySelectorAll('.review-status-tab').forEach(t => {
                        t.className = 'review-status-tab px-3.5 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer text-zinc-500 hover:text-zinc-900';
                    });
                    tab.className = 'review-status-tab px-3.5 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer bg-white text-zinc-900 shadow-xs';
                    appState.reviewsFilter.status = tab.dataset.reviewStatus;
                    this.loadReviewsList();
                });
            });

            document.getElementById('btn-create-review')?.addEventListener('click', () => {
                this.openReviewEditor(null);
            });

            await this.loadReviewsList();
        },

        async loadReviewsList() {
            const box = document.getElementById('reviews-list-box');
            if (!box) return;

            try {
                let url = '/api/admin/reviews';
                const params = new URLSearchParams();
                if (appState.reviewsFilter.search) params.set('search', appState.reviewsFilter.search);
                if (appState.reviewsFilter.status) params.set('status', appState.reviewsFilter.status);
                const queryString = params.toString();
                if (queryString) url += `?${queryString}`;

                const reviews = await api.get(url);
                appState.reviewsData = reviews || [];

                // Update Stats
                const total = reviews.length;
                const visibleCount = reviews.filter(r => r.is_visible).length;
                const hiddenCount = total - visibleCount;

                const totalEl = document.getElementById('stat-total-reviews');
                const visEl = document.getElementById('stat-visible-reviews');
                const hidEl = document.getElementById('stat-hidden-reviews');
                if (totalEl) totalEl.textContent = total;
                if (visEl) visEl.textContent = visibleCount;
                if (hidEl) hidEl.textContent = hiddenCount;

                if (reviews.length === 0) {
                    box.innerHTML = `
                        <div class="py-16 text-center bg-white rounded-3xl border border-zinc-100 shadow-sm">
                            <div class="w-14 h-14 rounded-2xl bg-zinc-50 text-zinc-300 flex items-center justify-center mx-auto text-2xl mb-3">
                                <i class="ti ti-star"></i>
                            </div>
                            <p class="text-sm font-bold text-zinc-700">Aucun avis trouvé</p>
                            <p class="text-xs text-zinc-400 mt-1">Créez votre premier avis client ou modifiez vos critères de recherche.</p>
                            <button type="button" onclick="window.adminApp.openReviewEditor(null)" class="btn-pill-primary btn-pill-sm mt-4 inline-flex items-center gap-1.5 cursor-pointer">
                                <i class="ti ti-plus"></i>
                                <span>Ajouter un avis</span>
                            </button>
                        </div>
                    `;
                    return;
                }

                const ringClasses = {
                    pink: 'ring-pink-500/30 bg-pink-50',
                    amber: 'ring-amber-500/30 bg-amber-50',
                    rose: 'ring-rose-500/30 bg-rose-50',
                    purple: 'ring-purple-500/30 bg-purple-50',
                    emerald: 'ring-emerald-500/30 bg-emerald-50',
                    teal: 'ring-teal-500/30 bg-teal-50',
                    blue: 'ring-blue-500/30 bg-blue-50',
                    indigo: 'ring-indigo-500/30 bg-indigo-50',
                };

                box.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        ${reviews.map(r => {
                            const ringStyle = ringClasses[r.ring_color] || ringClasses.pink;
                            const ratingNum = parseInt(r.rating, 10) || 5;
                            const avatarSrc = r.avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(r.author_name)}&background=ff1b7a&color=fff&size=128`;

                            let starsHtml = '';
                            for (let i = 1; i <= 5; i++) {
                                if (i <= ratingNum) {
                                    starsHtml += '<i class="ti ti-star-filled text-amber-400 text-xs"></i>';
                                } else {
                                    starsHtml += '<i class="ti ti-star text-zinc-300 text-xs"></i>';
                                }
                            }

                            return `
                                <div class="bg-white rounded-3xl p-5 sm:p-6 border border-zinc-100 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group relative ${!r.is_visible ? 'opacity-75 bg-zinc-50/70 border-dashed border-zinc-300' : ''}">
                                    <div>
                                        <!-- Card Top Header -->
                                        <div class="flex items-start justify-between gap-3 mb-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-12 h-12 rounded-full overflow-hidden shrink-0 ring-2 ${ringStyle} shadow-2xs">
                                                    <img src="${avatarSrc}" alt="${escapeHtml(r.author_name)}" class="w-full h-full object-cover select-none" onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(r.author_name)}&background=ff1b7a&color=fff&size=128'" />
                                                </div>
                                                <div>
                                                    <div class="flex items-center gap-1.5 flex-wrap">
                                                        <h3 class="text-sm font-extrabold text-zinc-900 leading-tight">${escapeHtml(r.author_name)}</h3>
                                                        <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-pink-50 text-pink-700 border border-pink-200/60">
                                                            <i class="ti ti-circle-check-filled text-pink-600 text-xs"></i>
                                                            ${escapeHtml(r.badge || 'Achat vérifié')}
                                                        </span>
                                                    </div>
                                                    ${r.author_role ? `<p class="text-xs text-zinc-500 font-medium mt-0.5">${escapeHtml(r.author_role)}</p>` : ''}
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-1 shrink-0">
                                                <span class="px-2 py-0.5 rounded-lg bg-zinc-100 text-zinc-600 font-mono text-[10px] font-bold" title="Ordre d'affichage">#${r.sort_order || 0}</span>
                                            </div>
                                        </div>

                                        <!-- Stars -->
                                        <div class="flex items-center gap-1 mb-2.5">
                                            ${starsHtml}
                                            <span class="text-[11px] font-bold text-zinc-400 ml-1">(${ratingNum}/5)</span>
                                        </div>

                                        <!-- Comment Quote -->
                                        <blockquote class="text-xs text-zinc-600 leading-relaxed italic bg-zinc-50/80 rounded-2xl p-3 border border-zinc-100 mb-4">
                                            &laquo;&nbsp;${escapeHtml(r.comment)}&nbsp;&raquo;
                                        </blockquote>
                                    </div>

                                    <!-- Bottom Action Bar & Visibility Toggle -->
                                    <div class="pt-3 border-t border-zinc-100 flex items-center justify-between gap-2 flex-wrap">
                                        <!-- Visibility Switch -->
                                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                                            <input type="checkbox" data-review-toggle="${r.id}" ${r.is_visible ? 'checked' : ''} class="w-4 h-4 rounded accent-pink-600 cursor-pointer" />
                                            <span class="text-xs font-bold ${r.is_visible ? 'text-emerald-700' : 'text-zinc-400'}">
                                                ${r.is_visible ? 'Visible en vitrine' : 'Masqué'}
                                            </span>
                                        </label>

                                        <!-- Actions -->
                                        <div class="flex items-center gap-1.5">
                                            <button type="button" data-review-edit="${r.id}" class="btn-pill-secondary btn-pill-sm cursor-pointer" title="Modifier l'avis">
                                                <i class="ti ti-edit text-xs"></i>
                                                <span class="text-xs">Modifier</span>
                                            </button>
                                            <button type="button" data-review-delete="${r.id}" class="btn-pill-danger btn-pill-sm cursor-pointer" title="Supprimer">
                                                <i class="ti ti-trash text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('')}
                    </div>
                `;

                // Wire Actions
                box.querySelectorAll('[data-review-toggle]').forEach(checkbox => {
                    checkbox.addEventListener('change', async (e) => {
                        const id = e.target.dataset.reviewToggle;
                        try {
                            const updated = await api.post(`/api/admin/reviews/${id}/toggle`);
                            toast.show(updated.is_visible ? 'Avis maintenant visible sur le site' : 'Avis masqué du storefront');
                            this.loadReviewsList();
                        } catch (err) {
                            toast.show(err.message || 'Erreur lors du changement de visibilité', 'error');
                            e.target.checked = !e.target.checked;
                        }
                    });
                });

                box.querySelectorAll('[data-review-edit]').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = parseInt(btn.dataset.reviewEdit, 10);
                        const review = (appState.reviewsData || []).find(r => r.id === id);
                        if (review) {
                            this.openReviewEditor(review);
                        } else {
                            this.openReviewEditorById(id);
                        }
                    });
                });

                box.querySelectorAll('[data-review-delete]').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = parseInt(btn.dataset.reviewDelete, 10);
                        const review = (appState.reviewsData || []).find(r => r.id === id);
                        if (review) {
                            this.deleteReviewConfirm(review);
                        }
                    });
                });

            } catch (err) {
                console.error('Reviews load error:', err);
                box.innerHTML = `
                    <div class="py-12 text-center text-red-500 bg-red-50/50 rounded-3xl border border-red-200">
                        <i class="ti ti-alert-triangle text-2xl mb-1 block"></i>
                        <p class="text-xs font-bold">Impossible de charger les avis clients</p>
                        <p class="text-[11px] text-red-400 mt-0.5">${escapeHtml(err.message)}</p>
                    </div>
                `;
            }
        },

        async openReviewEditorById(id) {
            try {
                const review = await api.get(`/api/admin/reviews/${id}`);
                this.openReviewEditor(review);
            } catch (err) {
                toast.show(err.message || 'Erreur de chargement de l\'avis', 'error');
            }
        },

        openReviewEditor(review = null) {
            const isEditing = !!review;
            const data = review || {
                author_name: '',
                author_role: 'Cliente vérifiée • Produit commandé',
                rating: 5,
                comment: '',
                avatar: '',
                badge: 'Achat vérifié',
                ring_color: 'pink',
                is_visible: true,
                sort_order: (appState.reviewsData ? appState.reviewsData.length + 1 : 1),
            };

            const ringOptions = [
                { key: 'pink', label: 'Rose Fuchsia', class: 'bg-pink-500' },
                { key: 'amber', label: 'Ambre Doré', class: 'bg-amber-500' },
                { key: 'rose', label: 'Rose Poudré', class: 'bg-rose-400' },
                { key: 'purple', label: 'Violet Prestige', class: 'bg-purple-500' },
                { key: 'emerald', label: 'Émeraude Frais', class: 'bg-emerald-500' },
                { key: 'teal', label: 'Turquoise Océan', class: 'bg-teal-500' },
                { key: 'blue', label: 'Bleu Ciel', class: 'bg-blue-500' },
                { key: 'indigo', label: 'Indigo Nuit', class: 'bg-indigo-500' },
            ];

            const presetAvatars = [
                { name: 'Sarah', src: '/images/reviews/sarah.jpg' },
                { name: 'Yasmine', src: '/images/reviews/yasmine.jpg' },
                { name: 'Camille', src: '/images/reviews/camille.jpg' },
                { name: 'Léa', src: '/images/reviews/lea.jpg' },
                { name: 'Nadia', src: '/images/reviews/nadia.jpg' },
                { name: 'Emma', src: '/images/reviews/emma.jpg' },
            ];

            const html = `
                <div class="p-6 md:p-7">
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-zinc-100">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center text-lg">
                                <i class="ti ${isEditing ? 'ti-edit' : 'ti-star'}"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-zinc-900">${isEditing ? 'Modifier l\'avis client' : 'Ajouter un avis client'}</h3>
                                <p class="text-[11px] text-zinc-400">Ce témoignage sera visible sur le carrousel d'avis de la vitrine</p>
                            </div>
                        </div>
                        <button type="button" data-close-modal class="text-zinc-400 hover:text-zinc-700 cursor-pointer">
                            <i class="ti ti-x text-base"></i>
                        </button>
                    </div>

                    <form id="review-modal-form" class="space-y-4 text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-zinc-700 mb-1">Nom de la cliente <span class="text-pink-600">*</span></label>
                                <input type="text" name="author_name" value="${escapeHtml(data.author_name || '')}" required placeholder="ex: Sarah Laurent" class="input-luxury w-full" />
                            </div>

                            <div>
                                <label class="block font-bold text-zinc-700 mb-1">Badge d'authenticité</label>
                                <input type="text" name="badge" value="${escapeHtml(data.badge || 'Achat vérifié')}" placeholder="ex: Achat vérifié" class="input-luxury w-full" />
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-zinc-700 mb-1">Sous-titre / Rôle ou Produit acheté</label>
                            <input type="text" name="author_role" value="${escapeHtml(data.author_role || '')}" placeholder="ex: Cliente vérifiée • Bare Vanilla Duo" class="input-luxury w-full" />
                        </div>

                        <!-- Rating & Ring Color -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-zinc-700 mb-1">Note attribuée (1 à 5 étoiles) <span class="text-pink-600">*</span></label>
                                <select name="rating" class="input-luxury w-full font-bold">
                                    <option value="5" ${data.rating == 5 ? 'selected' : ''}>★★★★★ (5 / 5 étoiles)</option>
                                    <option value="4" ${data.rating == 4 ? 'selected' : ''}>★★★★☆ (4 / 5 étoiles)</option>
                                    <option value="3" ${data.rating == 3 ? 'selected' : ''}>★★★☆☆ (3 / 5 étoiles)</option>
                                    <option value="2" ${data.rating == 2 ? 'selected' : ''}>★★☆☆☆ (2 / 5 étoiles)</option>
                                    <option value="1" ${data.rating == 1 ? 'selected' : ''}>★☆☆☆☆ (1 / 5 étoiles)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-bold text-zinc-700 mb-1">Couleur de l'anneau avatar</label>
                                <select name="ring_color" class="input-luxury w-full">
                                    ${ringOptions.map(ro => `
                                        <option value="${ro.key}" ${data.ring_color === ro.key ? 'selected' : ''}>${ro.label}</option>
                                    `).join('')}
                                </select>
                            </div>
                        </div>

                        <!-- Avatar Picker & Upload -->
                        <div>
                            <label class="block font-bold text-zinc-700 mb-1">Photo de profil / Avatar</label>
                            <input type="hidden" name="avatar" id="rev-avatar-input" value="${data.avatar || ''}" />
                            <input type="file" id="rev-file-input" accept="image/*" class="hidden" />

                            <div class="space-y-3">
                                <!-- Preset Avatars Selection -->
                                <div class="bg-zinc-50 p-3 rounded-2xl border border-zinc-200">
                                    <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider mb-2">Choisir parmi les avatars prédéfinis :</p>
                                    <div class="flex items-center gap-2 overflow-x-auto pb-1">
                                        ${presetAvatars.map(p => `
                                            <button type="button" class="preset-avatar-btn flex flex-col items-center gap-1 p-1 rounded-xl border border-zinc-200 hover:border-pink-500 bg-white cursor-pointer transition shrink-0 ${data.avatar === p.src ? 'ring-2 ring-pink-500 border-pink-500' : ''}" data-avatar-src="${p.src}">
                                                <img src="${p.src}" alt="${p.name}" class="w-9 h-9 rounded-full object-cover" />
                                                <span class="text-[9px] font-bold text-zinc-700">${p.name}</span>
                                            </button>
                                        `).join('')}
                                    </div>
                                </div>

                                <!-- Custom File Upload Zone -->
                                <div id="rev-upload-zone" class="border-2 border-dashed border-zinc-200 hover:border-pink-500 rounded-2xl p-3 text-center cursor-pointer transition-all bg-zinc-50/50 hover:bg-pink-50/30 group">
                                    <i class="ti ti-photo-up text-lg text-pink-600 mb-0.5 block group-hover:scale-110 transition-transform"></i>
                                    <span class="text-xs font-bold text-zinc-800">Ou importer une photo personnalisée</span>
                                    <p class="text-[10px] text-zinc-400">JPG, PNG, WEBP compressé automatiquement</p>
                                </div>

                                <!-- Current Avatar Preview -->
                                <div id="rev-preview-card" class="${data.avatar ? '' : 'hidden'} bg-zinc-50/70 border border-zinc-200 rounded-2xl p-2.5 flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-full bg-white border border-zinc-200 overflow-hidden shrink-0 flex items-center justify-center">
                                        <img id="rev-preview-img" src="${data.avatar || ''}" alt="Preview" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=User&background=ff1b7a&color=fff'" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-zinc-900 truncate" id="rev-preview-label">Avatar sélectionné</p>
                                        <button type="button" id="rev-clear-avatar-btn" class="text-[11px] font-bold text-red-600 hover:underline cursor-pointer mt-0.5">Effacer</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Comment Textarea -->
                        <div>
                            <label class="block font-bold text-zinc-700 mb-1">Commentaire / Avis client <span class="text-pink-600">*</span></label>
                            <textarea name="comment" rows="3" required placeholder="ex: Commande reçue en 48h chrono ! Le pack est absolument divin et 100% authentique..." class="textarea-luxury w-full">${escapeHtml(data.comment || '')}</textarea>
                        </div>

                        <!-- Sort Order & Visibility Checkbox -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                            <div>
                                <label class="block font-bold text-zinc-700 mb-1">Position / Ordre d'affichage</label>
                                <input type="number" name="sort_order" value="${data.sort_order || 0}" class="input-luxury w-full" />
                            </div>
                            <div class="flex items-center">
                                <label class="flex items-center gap-2.5 p-3 bg-zinc-50 rounded-2xl border border-zinc-200 cursor-pointer w-full mt-4 hover:bg-pink-50/30 transition">
                                    <input type="checkbox" name="is_visible" ${data.is_visible ? 'checked' : ''} class="w-4 h-4 rounded accent-pink-600 cursor-pointer" />
                                    <span class="font-bold text-zinc-800 text-xs">Visible sur le storefront</span>
                                </label>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-100 mt-5">
                            <button type="button" data-close-modal class="btn-pill-secondary btn-pill-sm cursor-pointer">
                                Annuler
                            </button>
                            <button type="submit" id="rev-submit-btn" class="btn-pill-primary btn-pill-sm cursor-pointer">
                                <i class="ti ti-check"></i>
                                <span>${isEditing ? 'Enregistrer les modifications' : 'Créer l\'avis'}</span>
                            </button>
                        </div>
                    </form>
                </div>
            `;

            modalSystem.openModal(html, {
                maxWidth: 'max-w-lg',
                onMount: (box) => {
                    const avatarInput = box.querySelector('#rev-avatar-input');
                    const fileInput = box.querySelector('#rev-file-input');
                    const uploadZone = box.querySelector('#rev-upload-zone');
                    const previewCard = box.querySelector('#rev-preview-card');
                    const previewImg = box.querySelector('#rev-preview-img');
                    const previewLabel = box.querySelector('#rev-preview-label');
                    const clearBtn = box.querySelector('#rev-clear-avatar-btn');

                    // Preset avatar click handler
                    box.querySelectorAll('.preset-avatar-btn').forEach(btn => {
                        btn.addEventListener('click', () => {
                            box.querySelectorAll('.preset-avatar-btn').forEach(b => b.classList.remove('ring-2', 'ring-pink-500', 'border-pink-500'));
                            btn.classList.add('ring-2', 'ring-pink-500', 'border-pink-500');
                            const src = btn.dataset.avatarSrc;
                            avatarInput.value = src;
                            previewImg.src = src;
                            previewLabel.textContent = 'Avatar prédéfini';
                            previewCard.classList.remove('hidden');
                        });
                    });

                    uploadZone?.addEventListener('click', () => fileInput?.click());

                    fileInput?.addEventListener('change', async (e) => {
                        const file = e.target.files?.[0];
                        if (!file) return;
                        try {
                            const dataUrl = await readFileAsCompressedDataUrl(file, 400, 0.85);
                            avatarInput.value = dataUrl;
                            previewImg.src = dataUrl;
                            previewLabel.textContent = file.name || 'Photo personnalisée';
                            previewCard.classList.remove('hidden');
                            box.querySelectorAll('.preset-avatar-btn').forEach(b => b.classList.remove('ring-2', 'ring-pink-500', 'border-pink-500'));
                        } catch (err) {
                            toast.show(err.message || 'Erreur lors du traitement de la photo', 'error');
                        }
                    });

                    clearBtn?.addEventListener('click', () => {
                        avatarInput.value = '';
                        fileInput.value = '';
                        previewImg.src = '';
                        previewCard.classList.add('hidden');
                        box.querySelectorAll('.preset-avatar-btn').forEach(b => b.classList.remove('ring-2', 'ring-pink-500', 'border-pink-500'));
                    });

                    // Form Submission
                    const form = box.querySelector('#review-modal-form');
                    form?.addEventListener('submit', async (e) => {
                        e.preventDefault();
                        const btn = box.querySelector('#rev-submit-btn');
                        btn.disabled = true;
                        btn.innerHTML = '<i class="ti ti-loader-2 animate-spin mr-1"></i> Traitement...';

                        const payload = {
                            author_name: form.author_name.value.trim(),
                            author_role: form.author_role.value.trim() || null,
                            badge: form.badge.value.trim() || 'Achat vérifié',
                            rating: parseInt(form.rating.value, 10) || 5,
                            ring_color: form.ring_color.value || 'pink',
                            avatar: form.avatar.value.trim() || null,
                            comment: form.comment.value.trim(),
                            sort_order: parseInt(form.sort_order.value, 10) || 0,
                            is_visible: form.is_visible.checked,
                        };

                        try {
                            if (isEditing) {
                                await api.put(`/api/admin/reviews/${data.id}`, payload);
                                toast.show(`Avis de "${payload.author_name}" mis à jour avec succès.`);
                            } else {
                                await api.post('/api/admin/reviews', payload);
                                toast.show(`Nouvel avis de "${payload.author_name}" créé avec succès.`);
                            }
                            modalSystem.closeModal();
                            this.loadReviewsList();
                        } catch (err) {
                            toast.show(err.message || 'Erreur lors de l\'enregistrement', 'error');
                            btn.disabled = false;
                            btn.innerHTML = `<i class="ti ti-check"></i> <span>${isEditing ? 'Enregistrer les modifications' : 'Créer l\'avis'}</span>`;
                        }
                    });
                }
            });
        },

        deleteReviewConfirm(review) {
            const html = `
                <div class="p-6 text-center">
                    <div class="w-12 h-12 rounded-full bg-red-50 text-red-600 flex items-center justify-center mx-auto text-xl mb-3">
                        <i class="ti ti-trash"></i>
                    </div>
                    <h3 class="text-sm font-bold text-zinc-900 mb-1">Supprimer cet avis client ?</h3>
                    <p class="text-xs text-zinc-500 mb-6">
                        L'avis de <strong>${escapeHtml(review.author_name)}</strong> sera définitivement supprimé et retiré du storefront.
                    </p>
                    <div class="flex items-center justify-center gap-3">
                        <button type="button" data-close-modal class="btn-pill-secondary btn-pill-sm cursor-pointer">
                            Annuler
                        </button>
                        <button type="button" id="confirm-delete-review-btn" class="btn-pill-danger btn-pill-sm cursor-pointer">
                            <i class="ti ti-trash"></i>
                            <span>Confirmer la suppression</span>
                        </button>
                    </div>
                </div>
            `;

            modalSystem.openModal(html, {
                maxWidth: 'max-w-sm',
                onMount: (box) => {
                    box.querySelector('#confirm-delete-review-btn')?.addEventListener('click', async () => {
                        try {
                            await api.delete(`/api/admin/reviews/${review.id}`);
                            toast.show('Avis supprimé avec succès.');
                            modalSystem.closeModal();
                            this.loadReviewsList();
                        } catch (err) {
                            toast.show(err.message || 'Erreur de suppression', 'error');
                        }
                    });
                }
            });
        }
    };

    // Expose globally
    window.adminApp = app;

    // Boot on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => app.init());
    } else {
        app.init();
    }
})();
