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
                this.container.className = 'fixed bottom-6 right-6 z-50 flex flex-col gap-2.5 max-w-sm pointer-events-none';
                document.body.appendChild(this.container);
            }
        },
        show(message, type = 'success') {
            this.init();
            const el = document.createElement('div');
            el.className = `pointer-events-auto transform translate-y-3 opacity-0 transition-all duration-300 ease-out flex items-center gap-3 px-4 py-3 rounded-2xl shadow-xl border text-sm font-medium ${
                type === 'error'
                    ? 'bg-red-950 text-red-100 border-red-800/80 shadow-red-950/20'
                    : type === 'info'
                    ? 'bg-zinc-900 text-white border-zinc-800 shadow-zinc-950/20'
                    : 'bg-zinc-900 text-white border-pink-500/30 shadow-pink-950/20'
            }`;

            const iconSvg = type === 'error'
                ? '<i class="ti ti-alert-circle text-rose-400 text-lg"></i>'
                : type === 'info'
                ? '<i class="ti ti-info-circle text-sky-400 text-lg"></i>'
                : '<i class="ti ti-check text-pink-400 text-lg"></i>';

            el.innerHTML = `
                <div class="shrink-0 flex items-center justify-center">${iconSvg}</div>
                <div class="flex-1 text-xs leading-snug">${message}</div>
                <button type="button" class="text-zinc-400 hover:text-white transition-colors cursor-pointer shrink-0 ml-1">
                    <i class="ti ti-x text-sm"></i>
                </button>
            `;

            el.querySelector('button').addEventListener('click', () => {
                el.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => el.remove(), 300);
            });

            this.container.appendChild(el);

            // Animate in
            requestAnimationFrame(() => {
                el.classList.remove('translate-y-3', 'opacity-0');
            });

            setTimeout(() => {
                if (el.parentNode) {
                    el.classList.add('opacity-0', 'translate-y-2');
                    setTimeout(() => el.remove(), 300);
                }
            }, 3600);
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

    // ─── Number / Currency Helpers ───────────────────────────────────────────
    const formatDH = (num) => {
        const val = parseFloat(num) || 0;
        return new Intl.NumberFormat('fr-FR', {
            maximumFractionDigits: 2,
            minimumFractionDigits: 0
        }).format(val) + ' DH';
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

            // Router hash listener
            window.addEventListener('hashchange', () => this.handleHashChange());
            window.addEventListener('resize', () => this.updateNotchPositions());
            this.handleHashChange();

            // Initial preload of categories for dropdowns
            this.preloadCategories();
        },

        async preloadCategories() {
            try {
                const cats = await api.get('/api/admin/categories');
                appState.categoriesCache = cats || [];
            } catch (err) {
                console.warn('Could not preload categories', err);
            }
        },

        handleHashChange() {
            const hash = window.location.hash.replace('#', '') || 'dashboard';
            const [view, param] = hash.split('/');
            this.navigate(view || 'dashboard', param);
        },

        updateNotchPositions() {
            // Desktop notch
            const desktopNav = document.getElementById('admin-pill-nav');
            const desktopNotch = document.getElementById('navbar-notch-wrapper');
            if (desktopNav && desktopNotch) {
                const activeTab = desktopNav.querySelector('.admin-pill-tab.active') || desktopNav.querySelector('.admin-pill-tab');
                if (activeTab) {
                    const navRect = desktopNav.getBoundingClientRect();
                    const tabRect = activeTab.getBoundingClientRect();
                    const targetX = (tabRect.left - navRect.left) + (tabRect.width / 2) - (desktopNotch.offsetWidth / 2);
                    desktopNotch.style.transform = `translateX(${targetX}px)`;
                }
            }

            // Mobile notch
            const mobileNav = document.getElementById('mobile-pill-nav');
            const mobileNotch = document.getElementById('mobile-notch-wrapper');
            if (mobileNav && mobileNotch) {
                const activeTab = mobileNav.querySelector('.mobile-pill-tab.active') || mobileNav.querySelector('.mobile-pill-tab');
                if (activeTab) {
                    const navRect = mobileNav.getBoundingClientRect();
                    const tabRect = activeTab.getBoundingClientRect();
                    const targetX = (tabRect.left - navRect.left) + (tabRect.width / 2) - (mobileNotch.offsetWidth / 2);
                    mobileNotch.style.transform = `translateX(${targetX}px)`;
                }
            }
        },

        updateSidebarActive(view) {
            // Update desktop pill nav
            document.querySelectorAll('.admin-pill-tab').forEach(el => {
                const target = el.dataset.view;
                const icon = el.querySelector('.tab-icon-wrap');
                const label = el.querySelector('.tab-label');
                if (target === view) {
                    el.classList.add('active', 'text-pink-600');
                    el.classList.remove('text-zinc-600');
                    if (icon) {
                        icon.classList.add('text-pink-600');
                        icon.classList.remove('text-zinc-700');
                    }
                    if (label) {
                        label.classList.add('text-pink-600', 'font-bold');
                        label.classList.remove('font-medium');
                    }
                } else {
                    el.classList.remove('active', 'text-pink-600');
                    el.classList.add('text-zinc-600');
                    if (icon) {
                        icon.classList.remove('text-pink-600');
                        icon.classList.add('text-zinc-700');
                    }
                    if (label) {
                        label.classList.remove('text-pink-600', 'font-bold');
                        label.classList.add('font-medium');
                    }
                }
            });

            // Update mobile pill nav
            document.querySelectorAll('.mobile-pill-tab').forEach(el => {
                const target = el.dataset.view;
                const icon = el.querySelector('.tab-icon-wrap');
                const label = el.querySelector('.tab-label');
                if (target === view) {
                    el.classList.add('active', 'text-pink-600');
                    el.classList.remove('text-zinc-600');
                    if (icon) {
                        icon.classList.add('text-pink-600');
                        icon.classList.remove('text-zinc-700');
                    }
                    if (label) {
                        label.classList.add('text-pink-600', 'font-bold');
                        label.classList.remove('font-medium');
                    }
                } else {
                    el.classList.remove('active', 'text-pink-600');
                    el.classList.add('text-zinc-600');
                    if (icon) {
                        icon.classList.remove('text-pink-600');
                        icon.classList.add('text-zinc-700');
                    }
                    if (label) {
                        label.classList.remove('text-pink-600', 'font-bold');
                        label.classList.add('font-medium');
                    }
                }
            });

            // Legacy admin-nav-item support if present
            document.querySelectorAll('.admin-nav-item').forEach(el => {
                const target = el.dataset.view;
                if (target === view) {
                    el.classList.add('bg-pink-50/80', 'text-pink-600', 'font-extrabold', 'shadow-xs');
                    el.classList.remove('text-zinc-600', 'font-medium');
                } else {
                    el.classList.remove('bg-pink-50/80', 'text-pink-600', 'font-extrabold', 'shadow-xs');
                    el.classList.add('text-zinc-600', 'font-medium');
                }
            });

            // Reposition the floating notches
            setTimeout(() => this.updateNotchPositions(), 30);
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
                        <div class="bg-gradient-to-r from-zinc-900 via-zinc-950 to-black rounded-3xl p-6 md:p-8 text-white relative overflow-hidden shadow-xl border border-zinc-800">
                            <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full bg-pink-600/15 blur-3xl pointer-events-none"></div>
                            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                                <div>
                                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-pink-500/10 border border-pink-500/20 text-pink-400 text-xs font-extrabold uppercase tracking-wider mb-3">
                                        <span class="w-2 h-2 rounded-full bg-pink-500 animate-pulse"></span>
                                        Espace Administrateur
                                    </div>
                                    <h1 class="text-2xl md:text-3xl font-black tracking-tight text-white">Tableau de bord Zizo Aura</h1>
                                    <p class="text-zinc-400 text-xs md:text-sm mt-1 max-w-xl font-medium">
                                        Vue d'ensemble de vos ventes, commandes en direct et gestion du catalogue cosmétique.
                                    </p>
                                </div>
                                <div class="flex flex-wrap items-center gap-3">
                                    <button type="button" id="dash-quick-prod" class="btn-primary-pink px-4 py-2.5 rounded-2xl text-xs font-bold flex items-center gap-2 cursor-pointer shadow-lg shadow-pink-600/20">
                                        <i class="ti ti-plus text-sm"></i>
                                        <span>Nouveau produit</span>
                                    </button>
                                    <button type="button" id="dash-quick-coupon" class="bg-zinc-800 hover:bg-zinc-700 text-white px-4 py-2.5 rounded-2xl text-xs font-bold flex items-center gap-2 transition cursor-pointer border border-zinc-700">
                                        <i class="ti ti-ticket text-sm"></i>
                                        <span>Nouveau code promo</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- KPI Cards Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                            <!-- KPI: Revenue -->
                            <div class="bg-white rounded-3xl p-5 border border-zinc-100 shadow-sm flex flex-col justify-between hover:border-pink-200 transition-all group">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Chiffre d'Affaires</span>
                                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base group-hover:scale-110 transition-transform">
                                        <i class="ti ti-currency-dirham"></i>
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
                                        <i class="ti ti-sparkles"></i>
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
            const pendingBadges = [
                document.getElementById('sidebar-pending-badge'),
                document.getElementById('pill-pending-badge')
            ];
            pendingBadges.forEach(badge => {
                if (badge) {
                    if (pendingOrders > 0) {
                        badge.textContent = pendingOrders;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            });

            const unreadBadges = [
                document.getElementById('sidebar-unread-badge'),
                document.getElementById('pill-unread-badge')
            ];
            unreadBadges.forEach(badge => {
                if (badge) {
                    if (unreadMessages > 0) {
                        badge.textContent = unreadMessages;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            });
        },

        // ═════════════════════════════════════════════════════════════════════
        // VIEW 2: PRODUCTS
        // ═════════════════════════════════════════════════════════════════════
        async renderProducts(page = 1) {
            appState.productsFilter.page = page;
            const filter = appState.productsFilter;

            let query = `?page=${filter.page}`;
            if (filter.search) query += `&search=${encodeURIComponent(filter.search)}`;
            if (filter.category_id) query += `&category_id=${filter.category_id}`;
            if (filter.status) query += `&status=${filter.status}`;

            this.root.innerHTML = `
                <div class="space-y-6 animate-fadeIn">
                    <!-- Products Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-black text-zinc-900 tracking-tight">Catalogue Produits</h1>
                            <p class="text-xs text-zinc-400 font-medium">Gérez vos formules, variantes olfactives, stocks et tarifs</p>
                        </div>
                        <button type="button" id="btn-create-product" class="btn-primary-pink px-4 py-2.5 rounded-2xl text-xs font-bold flex items-center gap-2 cursor-pointer shadow-lg shadow-pink-600/20 shrink-0">
                            <i class="ti ti-plus text-sm"></i>
                            <span>Nouveau produit</span>
                        </button>
                    </div>

                    <!-- Search & Filter Controls -->
                    <div class="bg-white rounded-2xl p-4 border border-zinc-100 shadow-sm flex flex-col md:flex-row items-center gap-3">
                        <div class="relative flex-1 w-full">
                            <i class="ti ti-search absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-400 text-base"></i>
                            <input type="text" id="prod-search-input" value="${filter.search || ''}" placeholder="Rechercher par nom..." class="w-full bg-zinc-50 border border-zinc-200 rounded-xl pl-10 pr-4 py-2 text-xs font-medium text-zinc-800 placeholder-zinc-400 focus:outline-none focus:border-pink-500 focus:bg-white transition" />
                        </div>
                        <div class="flex items-center gap-2 w-full md:w-auto">
                            <select id="prod-cat-select" class="bg-zinc-50 border border-zinc-200 rounded-xl px-3 py-2 text-xs font-medium text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white transition flex-1 md:w-44">
                                <option value="">Toutes les catégories</option>
                                ${appState.categoriesCache.map(cat => `
                                    <option value="${cat.id}" ${filter.category_id == cat.id ? 'selected' : ''}>${cat.name}</option>
                                `).join('')}
                            </select>

                            <select id="prod-status-select" class="bg-zinc-50 border border-zinc-200 rounded-xl px-3 py-2 text-xs font-medium text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white transition flex-1 md:w-36">
                                <option value="" ${filter.status === '' ? 'selected' : ''}>Tous les statuts</option>
                                <option value="active" ${filter.status === 'active' ? 'selected' : ''}>Actifs</option>
                                <option value="inactive" ${filter.status === 'inactive' ? 'selected' : ''}>Inactifs</option>
                                <option value="deleted" ${filter.status === 'deleted' ? 'selected' : ''}>Archivés</option>
                            </select>

                            ${(filter.search || filter.category_id || filter.status) ? `
                                <button type="button" id="prod-reset-filters" class="p-2 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-600 text-xs transition cursor-pointer" title="Réinitialiser les filtres">
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
            let timer = null;
            searchInput?.addEventListener('input', (e) => {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    appState.productsFilter.search = e.target.value;
                    appState.productsFilter.page = 1;
                    this.loadProductsList();
                }, 300);
            });

            document.getElementById('prod-cat-select')?.addEventListener('change', (e) => {
                appState.productsFilter.category_id = e.target.value;
                appState.productsFilter.page = 1;
                this.loadProductsList();
            });

            document.getElementById('prod-status-select')?.addEventListener('change', (e) => {
                appState.productsFilter.status = e.target.value;
                appState.productsFilter.page = 1;
                this.loadProductsList();
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
                                                        <img src="${p.image || '/images/sdj_bum_bum_set.jpg'}" alt="${p.name}" class="w-full h-full object-contain" onerror="this.src='/images/sdj_bum_bum_set.jpg'" />
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
                                                    <span class="inline-flex px-2.5 py-1 rounded-full bg-zinc-100 text-zinc-600 text-[10px] font-bold">Inactif</span>
                                                `}
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    ${isDeleted ? `
                                                        <button type="button" data-action="restore" data-id="${p.id}" class="px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white font-bold text-xs transition cursor-pointer">
                                                            <i class="ti ti-refresh mr-1"></i> Restaurer
                                                        </button>
                                                    ` : `
                                                        <button type="button" data-action="edit" data-id="${p.id}" class="p-2 rounded-xl bg-zinc-100 hover:bg-zinc-900 hover:text-white text-zinc-700 transition cursor-pointer" title="Modifier">
                                                            <i class="ti ti-edit text-sm"></i>
                                                        </button>
                                                        <button type="button" data-action="delete" data-id="${p.id}" data-name="${p.name}" class="p-2 rounded-xl bg-red-50 hover:bg-red-600 hover:text-white text-red-600 transition cursor-pointer" title="Archiver">
                                                            <i class="ti ti-trash text-sm"></i>
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
                tableBox.querySelectorAll('button[data-action="edit"]').forEach(btn => {
                    btn.addEventListener('click', () => this.openProductEditor(btn.dataset.id));
                });

                tableBox.querySelectorAll('button[data-action="delete"]').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = btn.dataset.id;
                        const name = btn.dataset.name;
                        modalSystem.confirm({
                            title: 'Archiver le produit',
                            message: `Voulez-vous vraiment archiver "${name}" ? Le produit ne sera plus visible par les clients mais pourra être restauré.`,
                            confirmText: 'Archiver le produit',
                            type: 'danger',
                            onConfirm: async () => {
                                await api.delete(`/api/admin/products/${id}`);
                                toast.show(`Produit "${name}" archivé.`);
                                this.loadProductsList();
                            }
                        });
                    });
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
        // VIEW 3: PRODUCT EDITOR (DRAWER / FORM)
        // ═════════════════════════════════════════════════════════════════════
        async openProductEditor(productId = null) {
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

            const html = `
                <div class="h-full flex flex-col bg-white">
                    <!-- Drawer Header -->
                    <div class="px-6 py-4 border-b border-zinc-100 flex items-center justify-between bg-zinc-50/50 shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center text-lg">
                                <i class="ti ${isEditing ? 'ti-edit' : 'ti-plus'}"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-zinc-900">${isEditing ? 'Modifier le produit' : 'Nouveau produit'}</h3>
                                <p class="text-[11px] text-zinc-400 font-medium">${isEditing ? product.name : 'Ajoutez une référence à votre boutique'}</p>
                            </div>
                        </div>
                        <button type="button" data-close-drawer class="p-2 rounded-xl text-zinc-400 hover:text-zinc-800 hover:bg-zinc-100 transition cursor-pointer">
                            <i class="ti ti-x text-base"></i>
                        </button>
                    </div>

                    <!-- Drawer Scrollable Form Body -->
                    <form id="product-editor-form" class="flex-1 overflow-y-auto p-6 space-y-6 text-xs">
                        <!-- Section 1: Basic Info -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-black uppercase tracking-wider text-zinc-400 border-b border-zinc-100 pb-2">Informations Générales</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <label class="block font-bold text-zinc-700 mb-1">Nom du produit <span class="text-pink-600">*</span></label>
                                    <input type="text" name="name" id="pe-name" value="${product.name || ''}" required placeholder="ex: Brume Parfumée Cheirosa 68" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 font-medium text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white transition" />
                                </div>

                                <div>
                                    <label class="block font-bold text-zinc-700 mb-1">Catégorie <span class="text-pink-600">*</span></label>
                                    <select name="category_id" required class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 font-medium text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white transition">
                                        <option value="">-- Choisir une catégorie --</option>
                                        ${appState.categoriesCache.map(cat => `
                                            <option value="${cat.id}" ${product.category_id == cat.id ? 'selected' : ''}>${cat.name}</option>
                                        `).join('')}
                                    </select>
                                </div>

                                <div>
                                    <label class="block font-bold text-zinc-700 mb-1">Slug URL <span class="text-zinc-400 font-normal">(auto si vide)</span></label>
                                    <input type="text" name="slug" value="${product.slug || ''}" placeholder="ex: brume-cheirosa-68" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 font-mono text-[11px] text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white transition" />
                                </div>

                                <div class="col-span-2">
                                    <label class="block font-bold text-zinc-700 mb-1">Sous-titre / Accroche</label>
                                    <input type="text" name="subtitle" value="${product.subtitle || ''}" placeholder="ex: Brume florale & gourmande pour le corps" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 font-medium text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white transition" />
                                </div>

                                <div class="col-span-2">
                                    <label class="block font-bold text-zinc-700 mb-1">Description détaillée</label>
                                    <textarea name="description" rows="3" placeholder="Présentation du produit..." class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 font-medium text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white transition">${product.description || ''}</textarea>
                                </div>

                                <div>
                                    <label class="block font-bold text-zinc-700 mb-1">Notes Olfactives</label>
                                    <textarea name="olfactory" rows="2" placeholder="ex: Pitahaya rose, Jasmin du Brésil, Vanille pure" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 font-medium text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white transition">${product.olfactory || ''}</textarea>
                                </div>

                                <div>
                                    <label class="block font-bold text-zinc-700 mb-1">Conseils d'utilisation</label>
                                    <textarea name="usage" rows="2" placeholder="ex: Vaporisez sur l'ensemble du corps et des cheveux" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 font-medium text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white transition">${product.usage || ''}</textarea>
                                </div>

                                <div class="col-span-2">
                                    <label class="block font-bold text-zinc-700 mb-1">Ingrédients clés</label>
                                    <textarea name="ingredients" rows="2" placeholder="ex: Aqua, Alcohol Denat., Parfum, Benzyl Salicylate..." class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 font-medium text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white transition">${product.ingredients || ''}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Pricing -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-black uppercase tracking-wider text-zinc-400 border-b border-zinc-100 pb-2">Prix & Promotions (en DH)</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-bold text-zinc-700 mb-1">Prix original (DH) <span class="text-pink-600">*</span></label>
                                    <input type="number" step="0.01" min="0.01" name="price" id="pe-price" value="${product.price || ''}" required placeholder="350" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 font-bold text-zinc-900 focus:outline-none focus:border-pink-500 focus:bg-white transition" />
                                </div>
                                <div>
                                    <label class="block font-bold text-zinc-700 mb-1">Prix soldé (DH) <span class="text-zinc-400 font-normal">(optionnel)</span></label>
                                    <input type="number" step="0.01" min="0" name="discounted_price" id="pe-discount" value="${product.discounted_price || ''}" placeholder="280" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 font-bold text-pink-600 focus:outline-none focus:border-pink-500 focus:bg-white transition" />
                                </div>
                            </div>
                            <div id="pe-discount-badge-preview" class="hidden text-[11px] font-bold text-pink-600 bg-pink-50 p-2.5 rounded-xl flex items-center gap-2">
                                <i class="ti ti-percentage text-sm"></i>
                                <span id="pe-discount-calc">Réduction : -0%</span>
                            </div>
                        </div>

                        <!-- Section 3: Media & Images -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-black uppercase tracking-wider text-zinc-400 border-b border-zinc-100 pb-2">Visuels & Galerie</h4>
                            <div>
                                <label class="block font-bold text-zinc-700 mb-1">URL Image principale <span class="text-pink-600">*</span></label>
                                <div class="flex gap-3">
                                    <input type="text" name="image" id="pe-image" value="${product.image || ''}" required placeholder="https://... ou /images/..." class="flex-1 bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 font-medium text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white transition" />
                                    <div class="w-11 h-11 rounded-xl bg-zinc-100 border border-zinc-200 overflow-hidden shrink-0 flex items-center justify-center">
                                        <img id="pe-image-preview" src="${product.image || '/images/sdj_bum_bum_set.jpg'}" alt="Preview" class="w-full h-full object-contain" onerror="this.src='/images/sdj_bum_bum_set.jpg'" />
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label class="font-bold text-zinc-700">Galerie d'images additionnelles</label>
                                    <button type="button" id="pe-add-gallery-btn" class="text-pink-600 hover:text-pink-700 font-bold flex items-center gap-1 cursor-pointer">
                                        <i class="ti ti-plus"></i> Ajouter une image
                                    </button>
                                </div>
                                <div id="pe-gallery-list" class="space-y-2">
                                    ${(product.gallery || []).map((url, i) => `
                                        <div class="flex items-center gap-2 gallery-row">
                                            <input type="text" name="gallery[]" value="${url}" placeholder="URL image galerie" class="flex-1 bg-zinc-50 border border-zinc-200 rounded-xl px-3 py-2 text-xs font-medium text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white transition" />
                                            <button type="button" class="p-2 text-zinc-400 hover:text-red-600 rounded-lg cursor-pointer remove-gallery-btn">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Badges & Availability -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-black uppercase tracking-wider text-zinc-400 border-b border-zinc-100 pb-2">Disponibilité, Avis & Badges</h4>
                            
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                <label class="flex items-center gap-2 p-3 bg-zinc-50 rounded-xl border border-zinc-200 cursor-pointer hover:bg-zinc-100/70 transition">
                                    <input type="checkbox" name="in_stock" ${product.in_stock ? 'checked' : ''} class="w-4 h-4 rounded accent-pink-600" />
                                    <span class="font-bold text-zinc-800">En stock</span>
                                </label>

                                <label class="flex items-center gap-2 p-3 bg-zinc-50 rounded-xl border border-zinc-200 cursor-pointer hover:bg-zinc-100/70 transition">
                                    <input type="checkbox" name="is_active" ${product.is_active ? 'checked' : ''} class="w-4 h-4 rounded accent-pink-600" />
                                    <span class="font-bold text-zinc-800">Actif (Visible)</span>
                                </label>

                                <label class="flex items-center gap-2 p-3 bg-zinc-50 rounded-xl border border-zinc-200 cursor-pointer hover:bg-zinc-100/70 transition">
                                    <input type="checkbox" name="is_bestseller" ${product.is_bestseller ? 'checked' : ''} class="w-4 h-4 rounded accent-pink-600" />
                                    <span class="font-bold text-zinc-800">Best-seller</span>
                                </label>

                                <label class="flex items-center gap-2 p-3 bg-zinc-50 rounded-xl border border-zinc-200 cursor-pointer hover:bg-zinc-100/70 transition">
                                    <input type="checkbox" name="is_new" ${product.is_new ? 'checked' : ''} class="w-4 h-4 rounded accent-pink-600" />
                                    <span class="font-bold text-zinc-800">Nouveau</span>
                                </label>

                                <label class="flex items-center gap-2 p-3 bg-zinc-50 rounded-xl border border-zinc-200 cursor-pointer hover:bg-zinc-100/70 transition">
                                    <input type="checkbox" name="has_sizes" id="pe-has-sizes" ${product.has_sizes ? 'checked' : ''} class="w-4 h-4 rounded accent-pink-600" />
                                    <span class="font-bold text-zinc-800">Variantes Tailles</span>
                                </label>

                                <label class="flex items-center gap-2 p-3 bg-zinc-50 rounded-xl border border-zinc-200 cursor-pointer hover:bg-zinc-100/70 transition">
                                    <input type="checkbox" name="has_flavors" id="pe-has-flavors" ${product.has_flavors ? 'checked' : ''} class="w-4 h-4 rounded accent-pink-600" />
                                    <span class="font-bold text-zinc-800">Variantes Parfums</span>
                                </label>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <div>
                                    <label class="block font-bold text-zinc-700 mb-1">Quantité en stock</label>
                                    <input type="number" min="0" name="stock_quantity" value="${product.stock_quantity ?? ''}" placeholder="ex: 50" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3 py-2 font-medium text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white" />
                                </div>
                                <div>
                                    <label class="block font-bold text-zinc-700 mb-1">Position / Tri</label>
                                    <input type="number" name="sort_order" value="${product.sort_order || 0}" placeholder="0" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3 py-2 font-medium text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white" />
                                </div>
                                <div>
                                    <label class="block font-bold text-zinc-700 mb-1">Note (0-5)</label>
                                    <input type="number" step="0.1" min="0" max="5" name="rating" value="${product.rating || 5.0}" placeholder="4.8" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3 py-2 font-medium text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white" />
                                </div>
                                <div>
                                    <label class="block font-bold text-zinc-700 mb-1">Nb d'avis</label>
                                    <input type="number" min="0" name="review_count" value="${product.review_count || 1}" placeholder="12" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3 py-2 font-medium text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white" />
                                </div>
                            </div>
                        </div>

                        <!-- Section 5: Size Variants -->
                        <div id="pe-sizes-wrapper" class="space-y-3 ${product.has_sizes || product.sizes?.length ? '' : 'hidden'}">
                            <div class="flex items-center justify-between border-b border-zinc-100 pb-2">
                                <h4 class="text-xs font-black uppercase tracking-wider text-zinc-400">Variantes de Format / Contenance</h4>
                                <button type="button" id="pe-add-size-btn" class="text-pink-600 hover:text-pink-700 font-bold flex items-center gap-1 cursor-pointer">
                                    <i class="ti ti-plus"></i> Ajouter une contenance
                                </button>
                            </div>
                            <div id="pe-sizes-list" class="space-y-2.5">
                                ${(product.sizes || []).map((s, i) => `
                                    <div class="flex items-center gap-2 bg-zinc-50 p-2.5 rounded-xl border border-zinc-200 size-row">
                                        <input type="text" name="size_label[]" value="${s.label || ''}" placeholder="ex: 90ml" required class="flex-1 bg-white border border-zinc-200 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-zinc-800" />
                                        <input type="number" step="0.01" name="size_price[]" value="${s.price || ''}" placeholder="Prix spécifique (DH)" class="w-32 bg-white border border-zinc-200 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-zinc-800" />
                                        <label class="flex items-center gap-1 text-[11px] font-bold text-zinc-600 cursor-pointer">
                                            <input type="checkbox" name="size_instock[]" ${s.in_stock ? 'checked' : ''} class="w-3.5 h-3.5 accent-pink-600 rounded" />
                                            <span>En stock</span>
                                        </label>
                                        <button type="button" class="p-1 text-zinc-400 hover:text-red-600 rounded remove-size-btn cursor-pointer">
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
                                <button type="button" id="pe-add-flavor-btn" class="text-pink-600 hover:text-pink-700 font-bold flex items-center gap-1 cursor-pointer">
                                    <i class="ti ti-plus"></i> Ajouter un parfum
                                </button>
                            </div>
                            <div id="pe-flavors-list" class="space-y-2.5">
                                ${(product.flavors || []).map((f, i) => `
                                    <div class="flex items-center gap-2 bg-zinc-50 p-2.5 rounded-xl border border-zinc-200 flavor-row">
                                        <input type="text" name="flavor_label[]" value="${f.label || ''}" placeholder="ex: Cheirosa 68" required class="flex-1 bg-white border border-zinc-200 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-zinc-800" />
                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <input type="color" value="${f.color_hex || '#ff1b7a'}" class="w-8 h-8 rounded-lg cursor-pointer border border-zinc-200 flavor-color-picker" />
                                            <input type="text" name="flavor_color[]" value="${f.color_hex || '#ff1b7a'}" placeholder="#ff1b7a" class="w-24 bg-white border border-zinc-200 rounded-lg px-2 py-1.5 font-mono text-[11px] text-zinc-800 flavor-color-hex" />
                                        </div>
                                        <label class="flex items-center gap-1 text-[11px] font-bold text-zinc-600 cursor-pointer">
                                            <input type="checkbox" name="flavor_instock[]" ${f.in_stock ? 'checked' : ''} class="w-3.5 h-3.5 accent-pink-600 rounded" />
                                            <span>En stock</span>
                                        </label>
                                        <button type="button" class="p-1 text-zinc-400 hover:text-red-600 rounded remove-flavor-btn cursor-pointer">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </form>

                    <!-- Drawer Sticky Footer -->
                    <div class="px-6 py-4 border-t border-zinc-100 bg-zinc-50/70 flex items-center justify-end gap-3 shrink-0">
                        <button type="button" data-close-drawer class="px-4 py-2.5 rounded-2xl border border-zinc-200 text-xs font-bold text-zinc-700 hover:bg-zinc-100 transition cursor-pointer">
                            Annuler
                        </button>
                        <button type="submit" form="product-editor-form" id="pe-submit-btn" class="btn-primary-pink px-6 py-2.5 rounded-2xl text-xs font-bold flex items-center gap-2 cursor-pointer shadow-lg shadow-pink-600/20">
                            <i class="ti ti-check text-sm"></i>
                            <span>${isEditing ? 'Enregistrer les modifications' : 'Créer le produit'}</span>
                        </button>
                    </div>
                </div>
            `;

            modalSystem.openDrawer(html, {
                maxWidth: 'max-w-2xl',
                onMount: (panel) => {
                    // Image preview live update
                    const imgInput = panel.querySelector('#pe-image');
                    const imgPreview = panel.querySelector('#pe-image-preview');
                    imgInput?.addEventListener('input', () => {
                        if (imgPreview) imgPreview.src = imgInput.value || '/images/sdj_bum_bum_set.jpg';
                    });

                    // Discount preview calculation
                    const priceInput = panel.querySelector('#pe-price');
                    const discountInput = panel.querySelector('#pe-discount');
                    const discountBadge = panel.querySelector('#pe-discount-badge-preview');
                    const discountCalc = panel.querySelector('#pe-discount-calc');

                    const updateDiscountPreview = () => {
                        const p = parseFloat(priceInput.value) || 0;
                        const d = parseFloat(discountInput.value) || 0;
                        if (d > 0 && p > d) {
                            const pct = Math.round((1 - d / p) * 100);
                            discountCalc.textContent = `Remise active : -${pct}% (${formatDH(p - d)} d'économie)`;
                            discountBadge.classList.remove('hidden');
                        } else {
                            discountBadge.classList.add('hidden');
                        }
                    };
                    priceInput?.addEventListener('input', updateDiscountPreview);
                    discountInput?.addEventListener('input', updateDiscountPreview);
                    updateDiscountPreview();

                    // Gallery dynamic rows
                    const galleryList = panel.querySelector('#pe-gallery-list');
                    panel.querySelector('#pe-add-gallery-btn')?.addEventListener('click', () => {
                        const row = document.createElement('div');
                        row.className = 'flex items-center gap-2 gallery-row';
                        row.innerHTML = `
                            <input type="text" name="gallery[]" value="" placeholder="URL image galerie" class="flex-1 bg-zinc-50 border border-zinc-200 rounded-xl px-3 py-2 text-xs font-medium text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white transition" />
                            <button type="button" class="p-2 text-zinc-400 hover:text-red-600 rounded-lg cursor-pointer remove-gallery-btn">
                                <i class="ti ti-trash"></i>
                            </button>
                        `;
                        row.querySelector('.remove-gallery-btn').addEventListener('click', () => row.remove());
                        galleryList.appendChild(row);
                    });

                    panel.querySelectorAll('.remove-gallery-btn').forEach(btn => {
                        btn.addEventListener('click', () => btn.closest('.gallery-row')?.remove());
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

                            modalSystem.closeDrawer();
                            if (appState.currentView === 'products') {
                                this.loadProductsList();
                            }
                        } catch (err) {
                            toast.show(err.message || 'Erreur lors de l\'enregistrement', 'error');
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = `<i class="ti ti-check text-sm"></i><span>${isEditing ? 'Enregistrer les modifications' : 'Créer le produit'}</span>`;
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
                        <button type="button" id="btn-create-category" class="btn-primary-pink px-4 py-2.5 rounded-2xl text-xs font-bold flex items-center gap-2 cursor-pointer shadow-lg shadow-pink-600/20 shrink-0">
                            <i class="ti ti-plus text-sm"></i>
                            <span>Nouvelle catégorie</span>
                        </button>
                    </div>

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
                                <i class="ti ti-tags"></i>
                            </div>
                            <p class="text-sm font-bold text-zinc-700">Aucune catégorie</p>
                            <p class="text-xs text-zinc-400 mt-1">Créez votre première collection pour classer vos produits.</p>
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
                                    <th class="px-6 py-3.5">Ordre de tri</th>
                                    <th class="px-6 py-3.5">Statut</th>
                                    <th class="px-6 py-3.5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                ${categories.map(c => `
                                    <tr class="hover:bg-pink-50/30 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl bg-zinc-100 border border-zinc-200 overflow-hidden shrink-0 flex items-center justify-center p-1">
                                                    <img src="${c.image || '/images/sdj_bum_bum_set.jpg'}" alt="${c.name}" class="w-full h-full object-contain" onerror="this.src='/images/sdj_bum_bum_set.jpg'" />
                                                </div>
                                                <div>
                                                    <div class="font-bold text-zinc-900 text-xs">${c.name}</div>
                                                    <div class="text-[11px] text-zinc-400 truncate max-w-xs">${c.description || '—'}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-mono text-zinc-400 text-[11px]">${c.slug}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-zinc-100 text-zinc-700 text-[11px] font-bold">
                                                <i class="ti ti-package text-zinc-400"></i>
                                                ${c.products_count ?? 0} produit(s)
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 font-bold text-zinc-600">${c.sort_order || 0}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold ${
                                                c.is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-zinc-100 text-zinc-600'
                                            }">
                                                ${c.is_active ? 'Active' : 'Inactive'}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <button type="button" data-cat-edit="${c.id}" class="p-2 rounded-xl bg-zinc-100 hover:bg-zinc-900 hover:text-white text-zinc-700 transition cursor-pointer" title="Modifier">
                                                    <i class="ti ti-edit text-sm"></i>
                                                </button>
                                                <button type="button" data-cat-delete="${c.id}" data-name="${c.name}" data-count="${c.products_count || 0}" class="p-2 rounded-xl bg-red-50 hover:bg-red-600 hover:text-white text-red-600 transition cursor-pointer" title="Supprimer">
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

                // Wire edit / delete buttons
                box.querySelectorAll('[data-cat-edit]').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = btn.dataset.catEdit;
                        const cat = categories.find(c => String(c.id) === String(id));
                        this.openCategoryEditor(cat);
                    });
                });

                box.querySelectorAll('[data-cat-delete]').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = btn.dataset.catDelete;
                        const name = btn.dataset.name;
                        const count = parseInt(btn.dataset.count, 10);

                        if (count > 0) {
                            modalSystem.confirm({
                                title: 'Suppression impossible',
                                message: `La catégorie "${name}" contient encore ${count} produit(s). Vous devez d'abord réassigner ou supprimer ces produits avant de pouvoir la supprimer.`,
                                confirmText: 'Compris',
                                cancelText: 'Fermer',
                                type: 'danger',
                                onConfirm: async () => {}
                            });
                            return;
                        }

                        modalSystem.confirm({
                            title: 'Supprimer la catégorie',
                            message: `Voulez-vous définitivement supprimer la catégorie "${name}" ?`,
                            confirmText: 'Supprimer la catégorie',
                            type: 'danger',
                            onConfirm: async () => {
                                await api.delete(`/api/admin/categories/${id}`);
                                toast.show(`Catégorie "${name}" supprimée.`);
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
                            <input type="text" name="name" value="${data.name || ''}" required placeholder="ex: Soins Corps & Solaires" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 font-medium text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white" />
                        </div>

                        <div>
                            <label class="block font-bold text-zinc-700 mb-1">Slug URL <span class="text-zinc-400 font-normal">(auto si vide)</span></label>
                            <input type="text" name="slug" value="${data.slug || ''}" placeholder="ex: soins-corps" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 font-mono text-[11px] text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white" />
                        </div>

                        <div>
                            <label class="block font-bold text-zinc-700 mb-1">URL Image d'illustration</label>
                            <input type="text" name="image" value="${data.image || ''}" placeholder="https://... ou /images/..." class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 font-medium text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white" />
                        </div>

                        <div>
                            <label class="block font-bold text-zinc-700 mb-1">Description</label>
                            <textarea name="description" rows="2" placeholder="Brève description de la collection..." class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 font-medium text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white">${data.description || ''}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-1">
                            <div>
                                <label class="block font-bold text-zinc-700 mb-1">Position / Ordre</label>
                                <input type="number" name="sort_order" value="${data.sort_order || 0}" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3 py-2 font-medium text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white" />
                            </div>
                            <div class="flex items-center">
                                <label class="flex items-center gap-2 p-2.5 bg-zinc-50 rounded-xl border border-zinc-200 cursor-pointer w-full mt-4">
                                    <input type="checkbox" name="is_active" ${data.is_active ? 'checked' : ''} class="w-4 h-4 rounded accent-pink-600" />
                                    <span class="font-bold text-zinc-800">Catégorie active</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-100 mt-5">
                            <button type="button" data-close-modal class="px-4 py-2.5 rounded-xl border border-zinc-200 font-bold text-zinc-700 hover:bg-zinc-50 cursor-pointer">
                                Annuler
                            </button>
                            <button type="submit" id="cat-submit-btn" class="btn-primary-pink px-5 py-2.5 rounded-xl font-bold flex items-center gap-1.5 cursor-pointer shadow-md">
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
                        <button type="button" id="btn-create-coupon" class="btn-primary-pink px-4 py-2.5 rounded-2xl text-xs font-bold flex items-center gap-2 cursor-pointer shadow-lg shadow-pink-600/20 shrink-0">
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
                                                }">
                                                    <span class="w-1.5 h-1.5 rounded-full ${c.is_active ? 'bg-emerald-500' : 'bg-zinc-400'}"></span>
                                                    <span>${c.is_active ? 'Actif' : 'Désactivé'}</span>
                                                </button>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex items-center justify-end gap-2">
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
                        try {
                            const res = await api.post(`/api/admin/coupons/${id}/toggle`);
                            toast.show(`Code promo ${res.code} ${res.is_active ? 'activé' : 'désactivé'}.`);
                            this.loadCouponsList();
                        } catch (err) {
                            toast.show(err.message || 'Erreur', 'error');
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
                            <input type="text" name="code" value="${data.code || ''}" required placeholder="ex: SUMMER20" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 font-mono uppercase text-sm font-black text-pink-600 tracking-wider focus:outline-none focus:border-pink-500 focus:bg-white" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-zinc-700 mb-1">Type de remise <span class="text-pink-600">*</span></label>
                                <select name="type" required class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 font-semibold text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white">
                                    <option value="percent" ${data.type === 'percent' ? 'selected' : ''}>% Pourcentage</option>
                                    <option value="fixed" ${data.type === 'fixed' ? 'selected' : ''}>DH Montant fixe</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-zinc-700 mb-1">Valeur de la réduction <span class="text-pink-600">*</span></label>
                                <input type="number" step="0.01" min="0.01" name="value" value="${data.value || ''}" required placeholder="ex: 20 ou 50" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 font-black text-zinc-900 focus:outline-none focus:border-pink-500 focus:bg-white" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-zinc-700 mb-1">Panier minimum (DH)</label>
                                <input type="number" step="0.01" min="0" name="min_order_amount" value="${data.min_order_amount ?? 0}" placeholder="0" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 font-medium text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white" />
                            </div>
                            <div>
                                <label class="block font-bold text-zinc-700 mb-1">Max utilisations <span class="text-zinc-400 font-normal">(vide = illimité)</span></label>
                                <input type="number" min="1" name="max_uses" value="${data.max_uses || ''}" placeholder="Illimité" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 font-medium text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white" />
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-zinc-700 mb-1">Date d'expiration <span class="text-zinc-400 font-normal">(optionnel)</span></label>
                            <input type="date" name="expires_at" value="${expiryFormatted}" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 font-medium text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white" />
                        </div>

                        <div>
                            <label class="flex items-center gap-2 p-3 bg-zinc-50 rounded-xl border border-zinc-200 cursor-pointer">
                                <input type="checkbox" name="is_active" ${data.is_active ? 'checked' : ''} class="w-4 h-4 rounded accent-pink-600" />
                                <span class="font-bold text-zinc-800">Code promo actif immédiatement</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-100 mt-5">
                            <button type="button" data-close-modal class="px-4 py-2.5 rounded-xl border border-zinc-200 font-bold text-zinc-700 hover:bg-zinc-50 cursor-pointer">
                                Annuler
                            </button>
                            <button type="submit" id="coupon-submit-btn" class="btn-primary-pink px-5 py-2.5 rounded-xl font-bold flex items-center gap-1.5 cursor-pointer shadow-md">
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
                                                <img src="${p.image || '/images/sdj_bum_bum_set.jpg'}" alt="" class="w-8 h-8 rounded-lg object-contain bg-zinc-100 border border-zinc-200" />
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

            this.root.innerHTML = `
                <div class="space-y-6 animate-fadeIn">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-black text-zinc-900 tracking-tight">Gestion des Commandes</h1>
                            <p class="text-xs text-zinc-400 font-medium">Suivez l'expédition, le statut de livraison et les paiements à la livraison</p>
                        </div>
                    </div>

                    <!-- Search & Status Filter -->
                    <div class="bg-white rounded-2xl p-4 border border-zinc-100 shadow-sm flex flex-col md:flex-row items-center gap-3">
                        <div class="relative flex-1 w-full">
                            <i class="ti ti-search absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-400 text-base"></i>
                            <input type="text" id="order-search-input" value="${filter.search || ''}" placeholder="Rechercher par nom de client, téléphone, N° de commande..." class="w-full bg-zinc-50 border border-zinc-200 rounded-xl pl-10 pr-4 py-2 text-xs font-medium text-zinc-800 placeholder-zinc-400 focus:outline-none focus:border-pink-500 focus:bg-white transition" />
                        </div>
                        <div class="flex items-center gap-2 w-full md:w-auto">
                            <select id="order-status-select" class="bg-zinc-50 border border-zinc-200 rounded-xl px-3 py-2 text-xs font-medium text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white transition flex-1 md:w-48">
                                <option value="" ${filter.status === '' ? 'selected' : ''}>Tous les statuts</option>
                                <option value="pending" ${filter.status === 'pending' ? 'selected' : ''}>En attente</option>
                                <option value="confirmed" ${filter.status === 'confirmed' ? 'selected' : ''}>Confirmée</option>
                                <option value="processing" ${filter.status === 'processing' ? 'selected' : ''}>En préparation</option>
                                <option value="shipped" ${filter.status === 'shipped' ? 'selected' : ''}>Expédiée</option>
                                <option value="delivered" ${filter.status === 'delivered' ? 'selected' : ''}>Livrée</option>
                                <option value="cancelled" ${filter.status === 'cancelled' ? 'selected' : ''}>Annulée</option>
                            </select>

                            ${(filter.search || filter.status) ? `
                                <button type="button" id="order-reset-filters" class="p-2 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-600 text-xs transition cursor-pointer" title="Réinitialiser les filtres">
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
            let timer = null;
            searchInput?.addEventListener('input', (e) => {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    appState.ordersFilter.search = e.target.value;
                    appState.ordersFilter.page = 1;
                    this.loadOrdersList();
                }, 300);
            });

            document.getElementById('order-status-select')?.addEventListener('change', (e) => {
                appState.ordersFilter.status = e.target.value;
                appState.ordersFilter.page = 1;
                this.loadOrdersList();
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
                                ${orders.map(o => `
                                    <tr class="hover:bg-pink-50/30 transition-colors group">
                                        <td class="px-6 py-4 font-mono font-bold text-zinc-900">#${o.id}</td>
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
                                `).join('')}
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

                const html = `
                    <div class="h-full flex flex-col bg-white">
                        <!-- Header -->
                        <div class="px-6 py-4 border-b border-zinc-100 flex items-center justify-between bg-zinc-50/50 shrink-0">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center text-lg">
                                    <i class="ti ti-shopping-bag"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-zinc-900">Commande #${order.id}</h3>
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
                                                <img src="${item.product_image || '/images/sdj_bum_bum_set.jpg'}" alt="${item.product_name}" class="w-full h-full object-contain" onerror="this.src='/images/sdj_bum_bum_set.jpg'" />
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
                                                !m.is_read ? 'bg-rose-50 text-rose-700 border border-rose-200 animate-pulse' : 'bg-zinc-100 text-zinc-500'
                                            }">
                                                <span class="w-1.5 h-1.5 rounded-full ${!m.is_read ? 'bg-rose-500' : 'bg-zinc-400'}"></span>
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
                                                <button type="button" data-read-msg="${m.id}" class="px-3 py-1.5 rounded-xl bg-zinc-100 hover:bg-zinc-900 hover:text-white text-zinc-800 font-bold text-xs transition cursor-pointer">
                                                    Lire
                                                </button>
                                                ${!m.is_read ? `
                                                    <button type="button" data-mark-read="${m.id}" class="p-1.5 text-zinc-400 hover:text-pink-600 transition cursor-pointer" title="Marquer comme lu">
                                                        <i class="ti ti-check text-base"></i>
                                                    </button>
                                                ` : ''}
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
                                <button type="button" ${data.current_page === 1 ? 'disabled class="opacity-40 cursor-not-allowed"' : ''} id="msg-prev-page" class="px-3 py-1.5 rounded-xl border border-zinc-200 font-bold text-zinc-700 hover:bg-zinc-50 transition cursor-pointer">
                                    Précédent
                                </button>
                                <button type="button" ${data.current_page === data.last_page ? 'disabled class="opacity-40 cursor-not-allowed"' : ''} id="msg-next-page" class="px-3 py-1.5 rounded-xl border border-zinc-200 font-bold text-zinc-700 hover:bg-zinc-50 transition cursor-pointer">
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
                            await api.patch(`/api/admin/messages/${id}/read`);
                            toast.show('Message marqué comme lu.');
                            this.loadMessagesList();
                        } catch (err) {
                            toast.show(err.message || 'Erreur', 'error');
                        }
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
                            <a href="mailto:${msg.email}?subject=${encodeURIComponent('Re: ' + (msg.subject || ''))}" class="px-3.5 py-1.5 rounded-xl bg-zinc-900 text-white font-bold text-xs hover:bg-pink-600 transition flex items-center gap-1.5">
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
                                Statut : <span class="font-bold ${msg.is_read ? 'text-zinc-600' : 'text-rose-600'}">${msg.is_read ? 'Déjà lu' : 'Non lu'}</span>
                            </span>
                            <div class="flex items-center gap-2">
                                ${!msg.is_read ? `
                                    <button type="button" id="msg-mark-read-btn" class="btn-primary-pink px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5 cursor-pointer shadow-md">
                                        <i class="ti ti-check"></i>
                                        <span>Marquer comme lu</span>
                                    </button>
                                ` : ''}
                                <button type="button" data-close-modal class="px-4 py-2 rounded-xl border border-zinc-200 font-bold text-zinc-700 hover:bg-zinc-50 cursor-pointer">
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
                            await api.patch(`/api/admin/messages/${msg.id}/read`);
                            toast.show('Message marqué comme lu.');
                            modalSystem.closeModal();
                            this.loadMessagesList();
                        } catch (err) {
                            toast.show(err.message || 'Erreur', 'error');
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
