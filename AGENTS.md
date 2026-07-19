# Foundation Management System (Foundation-MS) - Knowledge Base

## Project Overview
A Laravel PHP-based Foundation/Member Management System with payment gateway integrations (Stripe, PayPal), accounting features, and role-based access control.

**Repository Path:** `/workspace/project/foundation-ms`

---

## Tech Stack
- **Framework:** Laravel 11
- **PHP Version:** 8.2+
- **Database:** MySQL (configurable)
- **Auth:** Laravel Sanctum
- **RBAC:** Spatie Permission
- **Payment Gateways:** Stripe, PayPal
- **Package Manager:** Composer (backend), npm (frontend)

---

## Project Structure

### Core Directories
```
app/
├── Console/          # Artisan commands
├── Http/
│   └── Controllers/
│       ├── Admin/     # Admin panel controllers
│       └── Controller.php
├── Mail/             # Email templates
├── Models/           # Eloquent models
├── Providers/        # Service providers
└── Services/         # Business logic services

config/               # Laravel configuration files
database/
├── migrations/       # Database schema
├── factories/        # Model factories
└── seeders/         # Database seeders

resources/views/
├── admin/           # Admin panel Blade views
├── frontend/        # Public frontend views
├── public/          # Public pages (events, donations)
└── emails/          # Email templates

routes/
├── api.php          # API routes
├── web.php          # Web routes
└── console.php      # Console routes
```

---

## Key Models & Relationships

### User System
- **User** → belongsTo → Member (one-to-one)
- **User** → hasMany → Payment, Donation, Document, ActivityLog

### Member System
- **Member** → belongsTo → User
- **Member** → hasMany → Document, MonthlyContribution, EmergencyCollectionPayment
- **Member** → belongsToMany → EmergencyCollection (pivot)

### Financial System
- **Payment** → morphTo reference() (polymorphic)
- **Payment** → hasOne → Receipt
- **MonthlyContribution** → belongsTo → Member, User
- **EmergencyCollection** → hasMany → EmergencyCollectionPayment
- **Donation** → belongsTo → Member, Payment, User
- **Income/Expense** → belongsTo → Category, Member, User
- **Ledger** → morphTo (polymorphic for all financial entries)

### Content System
- **Event** → hasMany → EventRegistration
- **Album** → hasMany → GalleryImage
- **Notice, Activity, CmsPage, Document** - Standalone content

---

## Key Services

| Service | Purpose |
|---------|---------|
| **PaymentService** | Unified payment handling (Stripe/PayPal) |
| **StripeService** | Stripe checkout, webhooks, refunds |
| **PayPalService** | PayPal orders, capture, webhooks |
| **ReceiptService** | PDF receipt generation, QR codes |
| **ReminderService** | Member notifications (birthday, dues) |

---

## Important Configuration Notes

### Cache Configuration (CRITICAL)
```php
// config/cache.php sets CACHE_STORE_OVERRIDE = 'array'
// This is required for Spatie Permission to work
```
**Issue:** Forces array store which may affect other cache-dependent features.

### Spatie Permission Config
```php
// config/permission.php requires cache.store = 'array'
// Custom SpatiePermissionServiceProvider loads BEFORE standard providers
```

### Timezone
- **Default:** Asia/Dhaka (Bangladesh)

---

## Common Commands

### Installation
```bash
composer install
npm install
php artisan migrate
php artisan db:seed
php artisan key:generate
```

### Development
```bash
php artisan serve
npm run dev
```

### Testing
```bash
php artisan test
```

### Database
```bash
php artisan migrate:fresh --seed
php artisan db:seed --class=DatabaseSeeder
```

---

## Admin Routes Structure

| Prefix | Controller | Purpose |
|--------|------------|---------|
| `/admin/dashboard` | DashboardController | Main dashboard |
| `/admin/users` | UserController | User management |
| `/admin/roles` | RoleController | Role/Permission |
| `/admin/members` | MemberController | Member CRUD |
| `/admin/contributions` | ContributionController | Monthly dues |
| `/admin/emergency-collections` | EmergencyCollectionController | Emergency funds |
| `/admin/payments` | PaymentController | Payment gateway |
| `/admin/receipts` | ReceiptController | Receipt management |
| `/admin/accounting/*` | Income/Expense/Ledger | Accounting |
| `/admin/reports/*` | ReportController | Financial reports |
| `/admin/events,notices,gallery` | Respective Controllers | Content CMS |

---

## Public Routes

| Route | Purpose |
|-------|---------|
| `/` | Home page |
| `/about, /contact` | Static pages |
| `/events, /notices, /gallery` | Public listings |
| `/members` | Member directory |
| `/blood-donors/search` | Blood donor search |
| `/verify/member/{code}` | QR member verification |
| `/verify/payment/{receipt}` | Receipt verification |
| `/donate` | Donation form |

---

## Known Issues & Patterns

### Schema Mismatches (Potential Bugs)
1. **DashboardController** uses `payment_date` but should be `created_at`
2. **ReportController** uses `payment_status` but Donation uses `status`
3. **ReportController** uses `membership_status` but Member uses `status` (boolean)

### Receipt Number Generation
- Both `Receipt::generateReceiptNo()` and `MonthlyContribution::generateReceiptNo()` exist
- Potential collision in concurrent requests

### PayPal Currency
- Hardcoded exchange rates (USD_SAR = 3.75)
- Should use real-time rates

---

## Security Features
- Spatie RBAC with role/permission management
- CSRF protection on all forms
- Route middleware: `auth`, `verified`, `permission`
- Audit logging in `audit_logs` table
- QR verification for receipts and member cards
- Webhook signature verification (Stripe/PayPal)

---

## File Naming Conventions
- **Controllers:** PascalCase (e.g., `MemberController`)
- **Models:** PascalCase singular (e.g., `Member`)
- **Migrations:** snake_case with timestamp (e.g., `2024_01_01_000000_create_members_table.php`)
- **Views:** kebab-case (e.g., `emergency-collections/`)
- **Routes:** kebab-case in URLs

---

## Documentation Files
- `DATABASE_SCHEMA.md` - Complete database schema
- `DEPLOYMENT.md` - Deployment instructions
- `INSTALLATION.md` - Setup guide
- `TESTING_CHECKLIST.md` - Testing procedures
- `README.md` - Project overview
