# NOIR Collective — Deployment Guide

Production deployment for the Laravel 12 ecommerce platform.

## Requirements

- PHP 8.3+ (8.2 supported on Laravel 12.12)
- MySQL 8.0+
- Node.js 20.19+ or 22.12+
- Composer 2.x
- Redis (recommended for cache/queue)
- Supervisor (queue worker)

## 1. Server Setup

```bash
# Clone repository
git clone <your-repo> /var/www/noir-collective
cd /var/www/noir-collective

# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install frontend assets
npm ci && npm run build
```

## 2. Environment Configuration

Copy `.env.example` to `.env` and configure:

```env
APP_NAME="NOIR Collective"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com.au

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=noir_ecommerce
DB_USERNAME=noir
DB_PASSWORD=secure_password

QUEUE_CONNECTION=redis
CACHE_STORE=redis
SESSION_DRIVER=redis

# Shop
SHOP_NAME="NOIR Collective"
SHOP_CURRENCY=AUD
SHOP_TAX_RATE=10
SHOP_SHIPPING_FEE=12.95
SHOP_FREE_SHIPPING=150

# Payments
STRIPE_KEY=pk_live_xxx
STRIPE_SECRET=sk_live_xxx
STRIPE_WEBHOOK_SECRET=whsec_xxx
RAZORPAY_KEY=rzp_live_xxx
RAZORPAY_SECRET=xxx

# Google OAuth
GOOGLE_CLIENT_ID=xxx
GOOGLE_CLIENT_SECRET=xxx
GOOGLE_REDIRECT_URI=https://yourdomain.com.au/auth/google/callback

# Analytics
GOOGLE_ANALYTICS_ID=G-XXXXXXXX
FACEBOOK_PIXEL_ID=xxxxxxxx

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
# ...

# Files
FILESYSTEM_DISK=public
```

Generate application key:

```bash
php artisan key:generate
```

## 3. Database

```bash
php artisan migrate --force
php artisan db:seed --class=EcommerceSeeder --force
php artisan storage:link
```

## 4. Permissions

```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

## 5. Optimize Laravel

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan filament:optimize
php artisan icons:cache
```

## 6. Queue Worker (Supervisor)

```ini
[program:noir-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/noir-collective/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/noir-collective/storage/logs/worker.log
```

```bash
sudo supervisorctl reread && sudo supervisorctl update
```

## 7. Scheduler (Cron)

```cron
* * * * * cd /var/www/noir-collective && php artisan schedule:run >> /dev/null 2>&1
```

## 8. Nginx Configuration

```nginx
server {
    listen 80;
    server_name yourdomain.com.au;
    root /var/www/noir-collective/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 9. SSL

Use Certbot:

```bash
sudo certbot --nginx -d yourdomain.com.au
```

## 10. Admin Access

- URL: `https://yourdomain.com.au/admin`
- Default seeded admin: `admin@noircollective.com` / `password` (change immediately)

```bash
php artisan make:filament-user
```

## 11. Stripe Webhooks

Point Stripe webhook to: `https://yourdomain.com.au/stripe/webhook`

## 12. Backups

- Daily MySQL dumps
- Sync `storage/app` to S3 for product media

## Local Development

```bash
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
npm install && npm run dev
php artisan serve
```

Visit:
- Storefront: http://localhost:8000
- Admin: http://localhost:8000/admin
