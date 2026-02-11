# Multi Tenant Module System

A professional **Multi-Tenant Module System** built with Laravel, featuring separate databases per tenant and a modular feature architecture.

## Features

- **Multi-Tenancy**: Automated database isolation using `stancl/tenancy`.
- **Modular Design**: Extensible feature management using `nwidart/laravel-modules`.
- **RBAC**: Role-Based Access Control (Roles & Permissions).
- **Central Admin**: Manage tenants, module requests, and module settings from a central dashboard.
- **Tenant Module**: Manage modules from a tenant dashboard.
- **Middleware**: Tenant and module middleware to ensure that only authorized users can access tenant-specific routes.

## Requirements

- **PHP** 8.2+
- **MySQL** 8.0+
- **Node.js & NPM**

## Quick Start

```bash
# 1. Install dependencies
composer install
npm install

# 2. Environment setup
cp .env.example .env
php artisan key:generate

# 3. Database setup (Configure .env first)
php artisan migrate --seed
npm run build
# for development
npm run dev

# 4. Launch
php artisan serve

# 5. Queue
php artisan queue:work
```

## Default Credentials

- **URL**: `http://localhost:8000`
- **User**: `superadmin@example.com`
- **Pass**: `password`

**Tenant Credentials**

- **URL**: `http://tenant1.localhost`
- **User**: `admin@example.com`
- **Pass**: `password`

## Multi-Tenant Workflow

- **Central App**: Manage your tenants at the default domain.
- **Tenant Domains**: Each tenant operates on their own subdomain/domain (e.g., `tenant1.localhost`).
- **Module Request**: Request modules from the central app.
- **Module Installation**: Install modules from the tenant app.
- **Module Uninstallation**: Uninstall modules from the tenant app.

---

_Built with Laravel 12, Tenancy for Laravel, and Laravel Modules._
