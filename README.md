# NOIR Collective — Premium Ecommerce Platform

A production-ready Laravel 12 ecommerce application with Filament admin, Livewire storefront, and luxury dark streetwear UI.

## Tech Stack

- **Laravel 12** · PHP 8.2+
- **Filament v3** Admin Panel
- **Livewire 3** + Breeze Authentication
- **Tailwind CSS** · Alpine.js
- **MySQL** (SQLite for local dev)
- **Spatie** Permission & Media Library
- **Stripe** · **Razorpay** · Cash on Delivery
- **Laravel Sanctum** REST API
- **DomPDF** Invoices · **Maatwebsite Excel** Exports

## Quick Start

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
npm install && npm run build
php artisan serve
```

| URL | Credentials |
|-----|-------------|
| Storefront | http://localhost:8000 |
| Admin | http://localhost:8000/admin |
| Admin login | `admin@noircollective.com` / `password` |

## Features

### Storefront
- Fullscreen hero slider with video support
- Product shop with filters, search, sort, pagination
- Product detail: variants, zoom, reviews, wishlist, buy now
- Cart, coupons, tax, shipping, guest checkout
- Customer account, orders, wallet, reward points
- Blog, FAQ, newsletter, Instagram feed
- Google OAuth login
- Premium dark luxury UI (original design)

### Admin (`/admin`)
- Dashboard analytics
- Products, categories, brands, coupons, banners, blogs
- Order management with status timeline
- Filament CRUD for all core entities

### API (`/api/v1`)
- `GET /products` · `GET /products/{slug}`
- `GET /categories`
- Authenticated cart & orders (Sanctum)

### Architecture
- Multi-vendor ready (`vendors` table)
- Multi-currency & locale fields on users
- Abandoned cart, flash sales, discount campaigns
- Activity logs, wallet & reward points
- SEO sitemap at `/sitemap.xml`

## Environment

See `.env.example` for:
- `STRIPE_*` · `RAZORPAY_*`
- `GOOGLE_CLIENT_ID` / `SECRET`
- `SHOP_*` settings
- `GOOGLE_ANALYTICS_ID` · `FACEBOOK_PIXEL_ID`

## Deployment

See **[DEPLOYMENT.md](DEPLOYMENT.md)** for production setup (Nginx, Supervisor, SSL, optimization).

## Project Structure

```
app/
├── Enums/           # OrderStatus, PaymentStatus, PaymentGateway
├── Filament/        # Admin resources & widgets
├── Http/Controllers/# Web, API, Auth, Invoice
├── Livewire/Store/  # Home, Shop, Product, Cart, Checkout
├── Models/          # Eloquent models & relationships
└── Services/        # Cart, Checkout, Payment
database/
├── migrations/      # Full ecommerce schema
└── seeders/         # EcommerceSeeder (demo data)
resources/views/
├── layouts/store.blade.php
├── livewire/store/
└── components/product-card.blade.php
```

## Test Coupons

- `NOIR10` — 10% off (min $50)
- `WELCOME20` — $20 off (min $100)

## License

MIT
