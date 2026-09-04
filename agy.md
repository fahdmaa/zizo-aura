# 🌸 Antigravity Project Memory & Guidelines — `zizo aura`

Welcome to **zizo aura** (`🇪🇺🇫🇷🇲🇦`), a luxury beauty and bodycare e-commerce platform inspired by the clean, high-conversion direct-to-consumer (D2C) architecture of Sephora and Hismile.

---

## 📌 Strict Project Rules & Standards

### 1. Icons Standard
- **Rule:** Use icons strictly from **[Tabler Icons](https://github.com/tabler/tabler-icons)** (`@tabler/icons-webfont`).
- **Syntax:** `<i class="ti ti-[icon-name]"></i>`
- **Forbidden:** Never use Lucide, FontAwesome, Heroicons, or unapproved SVGs.

### 2. Localization & Currency
- **Language:** **French (`FR`)** across the entire website, buttons, modals, toasts, error states, and product descriptions.
- **Currency:** **Moroccan Dirhams (`DH`)**, formatted as `350 DH`, `490 DH`, `590 DH`.
- **Delivery:** Express delivery partout au Maroc 🇲🇦 (`35 DH` flat rate • 24/48h • Paiement à la livraison).

### 3. Aesthetics & Visual Hierarchy
- **Brand Name:** `zizo aura` (lowercase in logo).
- **Highlight Accent:** Soft vibrant pink (`#ff1b7a` / Tailwind `pink-600` / `bg-pink-50 text-pink-600`).
- **Typography:** `Plus Jakarta Sans` for body copy & details, `Syne` / `Impact` for luxury badges and watermark discounts.
- **Dropdowns:** Custom floating glassmorphism panels with active pink checkmarks (never native OS `<select>` elements).

### 4. Unified Button Motion & Interactions
- Standardized hover motion across all CTA pills (`.btn-card-pill`, `.btn-hero-pill`, `.btn-secondary-pill`, `.btn-circle-action`):
  - Elevation: `transform: translateY(-2px) scale(1.02);`
  - Glow: `box-shadow: 0 8px 22px rgba(255, 27, 122, 0.32);`
  - Transition timing: `all 0.22s cubic-bezier(0.16, 1, 0.3, 1)`.

### 5. Page-to-Page & Scroll Animations
- **Page Transitions:** Content container inherits `.page-transition-enter` (`pageFadeIn` opacity `0 -> 1` and `translateY(12px) -> 0`).
- **Scroll Reveal:** `.reveal-on-scroll` with `IntersectionObserver` triggering `.is-revealed` and staggered delays on row cards (`0ms`, `80ms`, `160ms`, `240ms`).

### 6. Customer Reviews & Feedback Cards
- **Card Styling:** Light grey surface (`bg-zinc-100/90`), defined hairline border (`border-zinc-200`), micro-shadow (`shadow-xs`).
- **Customer Avatars:** Photorealistic portraits in `public/images/reviews/` in circular frames (`w-12 h-12 rounded-full overflow-hidden ring-2 shadow-xs`).
- **Smooth Hover Interaction:** Silky elevation (`hover:-translate-y-2 hover:border-zinc-300 hover:shadow-lg`) with vertical track padding (`pt-3 pb-8`) to eliminate overflow clipping.

### 7. Promotional Modal
- **Clean Headline:** Elevated spacing (`pt-2 sm:pt-4`) with top promotional tag removed for a minimal, high-conversion look.
- **Copyable Code:** Dedicated coupon pill (`RIO35`) with one-click clipboard copy.

---

## 🛠️ Tech Stack & Runtime Architecture

- **Backend:** Laravel 12 on **PHP 8.3 / 8.5**
- **Frontend Assets:** Tailwind CSS v4, Vite 8, `@tabler/icons-webfont`
- **Serverless Hosting (Vercel):**
  - Config: `vercel.json` targeting `outputDirectory: "public"`
  - Runtime: `vercel-php@0.7.4` (PHP 8.3)
  - Entry point: `api/index.php` initializing `/tmp/storage` directories
  - Encryption: Reads `APP_KEY_1` or `APP_KEY` seamlessly in `config/app.php` & `api/index.php`.

---

## 📁 Key File Structure

```text
sephora-laravel/
├── app/Http/Controllers/
│   ├── BrandController.php        # Landing page & top 8 discount selections
│   ├── ShopController.php         # Product catalog, category filters, sorting & /api/search
│   └── ContactController.php      # Contact form handling
├── config/
│   └── app.php                    # App config & APP_KEY_1 fallback support
├── resources/
│   ├── css/app.css                # Tailwind CSS v4, animations (marquee, float, hover glow)
│   ├── js/app.js                  # Cart counter, live search pill, quantity controller, scroll observer
│   └── views/
│       ├── brand.blade.php        # Landing page
│       ├── contact.blade.php      # Contact page with custom subject dropdown
│       ├── layouts/app.blade.php  # Master layout with navbar, footer, toast & promo modal
│       ├── components/
│       │   ├── navbar.blade.php            # Top brand navigation + expandable search
│       │   ├── footer.blade.php            # Footer & social (@zizo_aura_)
│       │   ├── hero-hismile.blade.php      # Hero section
│       │   ├── products-marquee.blade.php  # Best discount offers marquee
│       │   ├── delivery-process.blade.php  # 4-step delivery grid
│       │   ├── customer-reviews.blade.php  # Reviews carousel slider with photo avatars & smooth hover
│       │   └── promo-modal.blade.php       # Summer sale promo popup (RIO35)
│       └── shop/
│           ├── index.blade.php    # Shop catalog grid
│           └── product.blade.php  # Product detail view with compact quantity selector
├── public/
│   └── images/
│       ├── reviews/               # Customer review avatars (sarah.jpg, yasmine.jpg, etc.)
│       └── ...                    # Product visuals (VS, Rituals, Sol de Janeiro)
├── api/index.php                  # Vercel serverless forwarder
├── vercel.json                    # Vercel deployment configuration
└── agy.md                         # Antigravity project memory & guidelines
```

---

## 💻 Essential CLI Commands

```bash
# Local development server
export PATH="/opt/homebrew/bin:$PATH"
php artisan serve --port=8000

# Compile production assets
npm run build

# Watch frontend assets during development
npm run dev

# Clear cache when debugging config
php artisan config:clear
php artisan view:clear
```

---

## 🔗 Official Links
- **GitHub Repository:** [https://github.com/fahdmaa/zizo-aura](https://github.com/fahdmaa/zizo-aura)
- **Instagram:** [@zizo_aura_](https://www.instagram.com/zizo_aura_/)
- **Contact Support:** `contact@zizoaura.com`
