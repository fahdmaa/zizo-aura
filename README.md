<div align="center">

# 🌸 zizo aura — Luxury Cosmetics & Bodycare E-Commerce

<p align="center">
  <strong>Expérience e-commerce haut de gamme inspirée par les standards D2C et Sephora.</strong><br>
  Formules solaires, brumes corps et coffrets iconiques : <em>Sol de Janeiro</em>, <em>Victoria's Secret</em> & <em>Rituals</em>.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-v4.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS v4" />
  <img src="https://img.shields.io/badge/Vite-8.x-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite" />
  <img src="https://img.shields.io/badge/Tabler_Icons-Webfont-206BC4?style=for-the-badge&logo=tabler&logoColor=white" alt="Tabler Icons" />
  <img src="https://img.shields.io/badge/Currency-Moroccan_DH-E11D48?style=for-the-badge" alt="Moroccan DH" />
</p>

---

</div>

## ✨ Fonctionnalités Clés & Expérience Utilisateur

### 🛍️ Catalogue & Boutique Interactive
- **Navigation & Filtres Instantanés :** Filtrez par marques (*Sol de Janeiro*, *Victoria's Secret*, *Rituals*) ou triez par popularité, notes et prix.
- **Recherche In-line Extensible & Suggestions en direct :** Champ de recherche minimaliste intégré dans la bannière avec menu flottant prédictif et résultats filtrés sur la boutique.
- **Tarification en Dirhams Marocains (DH) :** Affichage clair des remises et calculs de prix en temps réel.
- **Sélecteur de Quantité & Ajout au Panier :** Compteur tactile avec recalcul dynamique du montant total et badge de panier interactif.

### 🎨 Design & Micro-Interactions (D2C / Sephora aesthetic)
- **Animations au défilement (*Scroll Reveal*) :** Apparition progressive des sections, étapes et cartes en cascade (*staggered effect*).
- **Transitions fluides de page à page :** Effet de fondu et d'élévation douce (*page-fade-in*) lors de chaque changement d'URL.
- **Menus déroulants sur-mesure (*Glassmorphism*) :** Remplacement complet des éléments natifs par des panneaux flottants floutés avec coches actives roses.
- **Modal promotionnel d'été :** Carte pop-up sur la page d'accueil avec code promotionnel copiable en 1 clic (`RIO35`).
- **Système de boutons unifié :** Effets de survol standardisés avec élévation et halo rose signature (`#ff1b7a`).
- **Carrousel d'Avis Clientes :** Slider horizontal fluide avec navigation tactile et avis d'acheteurs vérifiés.
- **Typographie soignée :** Intégration de *Plus Jakarta Sans* et *Syne* pour une identité visuelle moderne et épurée.

---

## 🗂️ Architecture du Projet

```text
sephora-laravel/
├── app/
│   └── Http/Controllers/
│       ├── BrandController.php     # Page d'accueil & sélection des meilleures offres
│       ├── ShopController.php      # Catalogue boutique, fiches produits & API de recherche
│       └── ContactController.php   # Gestion du formulaire de contact et support
├── resources/
│   ├── css/
│   │   └── app.css                 # Configuration Tailwind CSS v4, keyframes & animations
│   ├── js/
│   │   └── app.js                  # Logique d'interaction : panier, recherche, modal, quantitomètre
│   └── views/
│       ├── brand.blade.php         # Page d'accueil principale (Landing page)
│       ├── contact.blade.php       # Page Contact & Support client
│       ├── layouts/
│       │   └── app.blade.php       # Template maître avec navigation et footer
│       ├── components/
│       │   ├── navbar.blade.php            # Bannière supérieure avec recherche extensible & panier
│       │   ├── footer.blade.php            # Pied de page & réseaux sociaux (@zizo_aura_)
│       │   ├── hero-hismile.blade.php      # Section Hero D2C
│       │   ├── products-marquee.blade.php  # Carrousel des meilleures offres
│       │   ├── delivery-process.blade.php  # Grille des 4 étapes de livraison
│       │   ├── customer-reviews.blade.php  # Slider des avis clientes
│       │   └── promo-modal.blade.php       # Pop-up promotionnel d'été
│       └── shop/
│           ├── index.blade.php     # Catalogue complet de la boutique
│           └── product.blade.php   # Page de détails produit avec sélecteur d'unités
├── public/
│   └── images/                     # Visuels officiels des duos et coffrets
└── routes/
    └── web.php                     # Définition des routes et points de terminaison API
```

---

## 🚀 Installation & Lancement Rapide

### Prérequis
- **PHP 8.2+**
- **Composer**
- **Node.js (v18+)** & **NPM**

### 1. Cloner le dépôt
```bash
git clone https://github.com/fahdmaa/zizo-aura.git
cd zizo-aura
```

### 2. Installer les dépendances PHP & Node
```bash
composer install
npm install
```

### 3. Configurer l'environnement
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Compiler les assets et démarrer le serveur
```bash
# Compilation des assets Vite (Tailwind v4)
npm run build

# Lancement du serveur de développement Laravel
php artisan serve --port=8008
```

👉 Ouvrez votre navigateur sur **[http://localhost:8008](http://localhost:8008)**.

---

## 📱 Réseaux Sociaux & Contact
- **Instagram :** [@zizo_aura_](https://www.instagram.com/zizo_aura_/)
- **E-mail :** contact@zizoaura.com
- **Téléphone :** +33 (0)1 89 71 22 00

---

## 📄 Licence
Projet développé sous licence [MIT](LICENSE).
