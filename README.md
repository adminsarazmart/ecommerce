# NexusMart Enterprise Marketplace

The world's most advanced enterprise-grade Multi-Vendor Ecommerce Marketplace Platform.

## Tech Stack

- **Backend**: Laravel 12, PHP 8.4, MySQL
- **Frontend**: Vue 3, Inertia.js, Tailwind CSS, GSAP
- **Queue**: Laravel Horizon, Redis
- **Realtime**: Laravel Reverb (WebSockets)
- **Search**: Elasticsearch (via Laravel Scout)
- **Performance**: Laravel Octane (Swoole/RoadRunner)

## Features

### Multi-Vendor Marketplace
Vendor registration, KYC verification, commission management, wallet & withdrawals, subscription plans, ratings & reviews, vendor chat

### Advanced Product System
Unlimited categories, attributes, variants, variant images/pricing/SKU/barcode, color/size system with dynamic pricing

### Image Selection Cart
Click thumbnail → auto-select variant/color → update price/stock → add to cart

### Inventory Management
Multi-warehouse, stock transfers, adjustments, purchase orders, supplier management, barcode

### POS System
Retail & wholesale POS, barcode scanner, thermal printing, cash drawer, invoice printing

### ERP System
Chart of accounts, journal entries, profit & loss, balance sheet, cash flow, expense tracking

### Employee Management
Departments, attendance, leaves, payroll, salary, performance tracking

### Shareholder System (Exactly 6)
Profit calculation → expense deduction → equal distribution (16.6667% each) → automatic ledger → reports

### Reseller & Affiliate System
Reseller registration, referral links, commission tracking, withdrawals

### Customer Features
Wishlist, compare, reviews, Q&A, loyalty points, wallet, cashback, reward system, membership levels

### Payment Gateways (10)
SSLCommerz, Stripe, PayPal, bKash, Nagad, Rocket, Upay, AmarPay, UddoktaPay, ShurjoPay

### Admin Panel
Drag-and-drop header/footer/homepage builder, theme builder, CMS pages, banners, sliders, menus

### Enterprise Security
RBAC (Spatie), 2FA, audit logs, activity logs, device management, login history

### Reports & Analytics
Daily/weekly/monthly/yearly reports for sales, profit, vendors, customers, products, inventory, POS

## Getting Started

```bash
# Clone & install
composer install
npm install

# Configure
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate
php artisan db:seed

# Install marketplace
php artisan marketplace:install

# Start development
npm run dev
php artisan octane:start --server=swoole --port=8000
php artisan horizon
php artisan reverb:start
```

## Route Structure

| Prefix | Description |
|--------|-------------|
| `/` | Storefront |
| `/admin` | Admin Panel |
| `/vendor` | Vendor Dashboard |
| `/api/v1` | REST API |

## Structure

```
app/
├── Modules/          # Modular business logic
├── Repositories/     # Repository pattern
├── Services/         # Service layer
├── Contracts/        # Interfaces
├── Traits/           # Reusable traits
├── Events/           # Event classes
├── Jobs/             # Queueable jobs
├── Notifications/    # Notifications
├── Exceptions/       # Custom exceptions
├── Http/
│   ├── Controllers/  # Web + API controllers
│   ├── Middleware/    # HTTP middleware
│   └── Requests/     # Form requests
└── Models/           # Eloquent models
resources/js/
├── Pages/            # Inertia pages
├── Components/       # Vue components
├── Composables/      # Vue composables
├── Layouts/          # Layouts
└── Stores/           # Pinia stores
```
