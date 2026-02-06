# Multi Tenant User Management System (UMS)

Multi-tenant user management system with roles and permissions. Each tenant gets their own database.

## Features

- Multi-tenancy (separate database per tenant)
- User management
- Roles and permissions
- Authentication

## Requirements

- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL

## Installation

```bash
# Clone and install
git clone https://github.com/yourusername/lara-ums.git
cd lara-ums
composer install
npm install

# Setup
cp .env.example .env
php artisan key:generate

# Configure .env
DB_DATABASE=lara_ums_central
TENANCY_CENTRAL_DOMAIN=localhost

# Run
php artisan migrate --seed
npm run build
php artisan serve
```

Visit `http://localhost:8000`

## Usage

### Default Login

- Email: `superadmin@example.com`
- Password: `password`

### How It Works

**Central App** (localhost) - Manage tenants here

**Tenant Apps** (tenant domains e.g `company.local`) - Each tenant has separate users, roles, and data

### Tenant Commands

```bash
php artisan tenants:migrate    # Run migrations for all tenants
php artisan tenants:seed        # Seed tenant databases
```
