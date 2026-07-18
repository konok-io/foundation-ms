# Foundation Management System

A comprehensive, enterprise-grade Foundation Management System built with Laravel 12 for social welfare organizations.

## Features

### Core Modules
- **User & Role Management** - Complete RBAC system with permissions
- **Member Management** - Full member registration and profile management
- **Monthly Contributions** - Automated due generation and payment tracking
- **Emergency Collections** - Quick fund collection for urgent needs
- **Payment Gateway Integration** - Stripe & PayPal support
- **Receipt Management** - PDF receipts with QR verification
- **Donation System** - Public donation form and management
- **Accounting System** - Income, expense, vouchers, and ledger
- **Financial Reports** - Comprehensive reporting with exports

### Additional Modules
- Blood Donor Management
- Event Management
- Notice System
- Document Management
- Photo & Video Gallery
- Activity Tracking
- Analytics Dashboard

### Features
- Bilingual Support (English & Bangla)
- Responsive Design (Bootstrap 5)
- DataTables for data management
- Chart.js visualizations
- SweetAlert2 notifications
- QR Code generation
- PDF generation with DomPDF
- Excel export capabilities

## Technology Stack

- **Framework:** Laravel 12
- **PHP:** 8.2+
- **Database:** MySQL
- **Frontend:** Bootstrap 5, jQuery, DataTables
- **Charts:** Chart.js
- **Icons:** Bootstrap Icons

## Packages Used

- Laravel Breeze - Authentication
- Spatie Permission - Role & Permission management
- Laravel DomPDF - PDF generation
- Maatwebsite Excel - Excel exports
- Simple QR Code - QR code generation
- Laravel Media Library - File uploads
- Laravel Cashier - Payment integration
- Spatie Activity Log - Activity tracking
- Spatie Backup - Database backups

## Quick Start

```bash
# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate key
php artisan key:generate

# Run migrations with seeders
php artisan migrate --seed

# Create storage link
php artisan storage:link

# Start server
php artisan serve
```

Visit `http://localhost:8000` and login with:
- Email: `admin@example.com`
- Password: `password`

## Documentation

For detailed installation and configuration instructions, see [INSTALLATION.md](INSTALLATION.md).

## User Roles

1. **Super Admin** - Full system access
2. **Admin** - Administrative access
3. **Accountant** - Financial management
4. **Executive Member** - Decision making access
5. **General Member** - Basic member access
6. **Volunteer** - Event management access
7. **Donor** - Donation history access

## License

This project is open-sourced software licensed under the MIT license.