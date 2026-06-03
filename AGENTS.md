# NexusMart Enterprise Marketplace — Agent Guide

## Architecture

- **Modules**: `/app/Modules/{Marketplace,Product,Order,Vendor,Customer,Inventory,POS,ERP,Employee,Shareholder,Reseller,Payment,Cms,Builder,Affiliate}`
- **Repositories**: `/app/Repositories/{BaseRepository,ProductRepository,...}`
- **Services**: `/app/Services/{BaseService,ProductService,...}`
- **Contracts**: `/app/Contracts/{RepositoryInterface,ServiceInterface}`
- **Traits**: `/app/Traits/{HasUUID,HasSlug,...}`

## Key Commands

```bash
# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Seed with specific seeder
php artisan db:seed --class=RolePermissionSeeder

# Install marketplace (first-time setup)
php artisan marketplace:install

# Start Octane
php artisan octane:start --server=swoole --port=8000

# Start Horizon (queue worker)
php artisan horizon

# Start Reverb (websocket)
php artisan reverb:start

# Queue a job
php artisan queue:work redis

# Generate sitemap
php artisan sitemap:generate

# Distribute monthly dividends
php artisan dividends:distribute

# Calculate daily profit
php artisan profit:calculate:daily

# Clear expired data
php artisan data:clean:expired

# Run tests
php artisan test
# or
php vendor/bin/phpunit

# Build frontend
npm run build
npm run dev
```

## Route Structure

| Prefix | File | Middleware |
|--------|------|------------|
| `/` | `routes/web.php` | web, guest/auth |
| `/admin` | `routes/admin.php` | web, auth, admin |
| `/vendor` | `routes/vendor.php` | web, auth, vendor |
| `/api/v1` | `routes/api.php` | api, sanctum |
| Broadcast | `routes/channels.php` | reverb |

## Permission Names

Format: `module.action` (e.g. `product.create`, `order.view`, `vendor.approve`)

## Key Services

- `ProductService` - Product CRUD, variant management, search, import/export
- `OrderService` - Order placement, status workflow, refunds, returns
- `VendorService` - Registration, KYC, commissions, payouts
- `CartService` - Cart management, coupon application
- `CheckoutService` - Multi-step checkout with payment
- `PaymentService` - Payment gateway abstraction
- `ShareholderService` - Profit calculation, dividend distribution (6 × 16.6667%)
- `ProfitCalculationService` - Net profit after expenses, equal distribution
- `InventoryService` - Multi-warehouse stock, transfers, purchase orders
- `PosService` - Register sessions, retail/wholesale POS
- `AnalyticsService` - Revenue, product, customer, vendor analytics
- `ReportService` - Sales, profit, inventory reports (PDF/Excel export)
- `BuilderService` - Header/Footer/Homepage drag-drop layout builder
- `CmsService` - Pages, banners, sliders, menus
- `ElasticsearchService` - Product indexing and full-text search
- `NotificationService` - Email and real-time notifications via Reverb

## Gateways

| Gateway | File |
|---------|------|
| SSLCommerz | `Gateways/SSLCommerzGateway.php` |
| Stripe | `Gateways/StripeGateway.php` |
| PayPal | `Gateways/PayPalGateway.php` |
| bKash | `Gateways/BkashGateway.php` |
| Nagad | `Gateways/NagadGateway.php` |
| Rocket | `Gateways/RocketGateway.php` |
| Upay | `Gateways/UpayGateway.php` |
| AmarPay | `Gateways/AmarPayGateway.php` |
| UddoktaPay | `Gateways/UddoktaPayGateway.php` |
| ShurjoPay | `Gateways/ShurjoPayGateway.php` |

## Shareholder System (Exactly 6)

- Each gets exactly `16.6667%` (`100/6`) of net profit
- Profit = Order revenue - cost of goods - commissions - expenses
- Automatically distributed via `ProfitCalculationService`
- Ledger tracked in `shareholder_ledgers` table
- Monthly/yearly reports via `ShareholderService`
- Dividend distributions via `DividendDistribution` model

## Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run specific test file
php artisan test tests/Feature/ProductTest.php
```
