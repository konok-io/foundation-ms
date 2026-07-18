# Foundation Management System - Installation Guide

## Requirements

- PHP 8.2 or higher
- Composer 2.x
- MySQL 5.7+ or MariaDB 10.3+
- Node.js 18+ (for frontend assets)
- Git

## Installation Steps

### 1. Clone the Repository

```bash
cd /workspace/project/foundation-ms
# Or if starting fresh:
git clone <repository-url> foundation-management-system
cd foundation-management-system
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies (if needed)
npm install
```

### 3. Environment Configuration

```bash
# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Generate JWT secret (if using Sanctum)
php artisan jwt:secret
```

### 4. Database Configuration

Edit the `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=foundation_management_system
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create the database:

```sql
CREATE DATABASE foundation_management_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Run Migrations and Seeders

```bash
# Run migrations
php artisan migrate

# Seed the database with initial data
php artisan db:seed

# Or run both at once
php artisan migrate --seed
```

### 6. Create Storage Link

```bash
php artisan storage:link
```

### 7. Build Frontend Assets (Optional)

```bash
npm install
npm run dev
# Or for production
npm run build
```

### 8. Start the Server

```bash
# Development server
php artisan serve

# The application will be available at http://localhost:8000
```

## Default Login Credentials

After seeding, you can login with:

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@example.com | password |
| Admin | admin@foundation.org | password |
| Accountant | accountant@foundation.org | password |
| Executive | executive@foundation.org | password |

## Permissions Setup

The seeder automatically creates all required permissions and assigns them to roles. The permissions include:

- User Management (view, create, edit, delete)
- Role Management (view, create, edit, delete)
- Member Management (view, create, edit, delete, export, card)
- Contributions (view, create, edit, delete, process)
- Emergency Collections (view, create, edit, delete)
- Payments (view, create, process, refund)
- Donations (view, create, manage)
- Accounting (view, income, expense, voucher, ledger)
- Reports (view, financial, member, export)
- Settings (view, update, cms)
- And more...

## Setting Up Payment Gateways

### Stripe Configuration

1. Create a Stripe account at https://stripe.com
2. Get your API keys from the Stripe Dashboard
3. Add to `.env`:

```env
STRIPE_KEY=pk_test_xxxxx
STRIPE_SECRET=sk_test_xxxxx
STRIPE_WEBHOOK_SECRET=whsec_xxxxx
```

### PayPal Configuration

1. Create a PayPal Developer account
2. Get your Client ID and Secret
3. Add to `.env`:

```env
PAYPAL_CLIENT_ID=xxxxx
PAYPAL_CLIENT_SECRET=xxxxx
PAYPAL_MODE=sandbox
```

## Scheduled Tasks

Add the Laravel scheduler to your crontab:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Queue Worker (Optional)

For better performance with email sending and notifications:

```bash
php artisan queue:work
```

## Troubleshooting

### Permission Issues

If you encounter permission errors:

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Recreate Permissions

If permissions don't work correctly:

```bash
php artisan permission:cache:clear
```

## Next Steps

1. Log in to the admin panel
2. Update organization settings
3. Add foundation logo and favicon
4. Configure email settings
5. Set up payment gateway credentials
6. Start adding members

## Support

For issues and questions, please refer to the project documentation or contact the development team.
