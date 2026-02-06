# Lara-UMS (Laravel User Management System)

A comprehensive multi-tenant user management system built with Laravel, featuring role-based access control, permissions, and user administration with complete tenant isolation.

## Features

- **Multi-Tenancy**: Full multi-tenant architecture with automatic database isolation per tenant
- **Tenant Management**: Create and manage multiple tenants with separate domains/subdomains
- **User Management**: Create, view, edit, and delete user accounts within each tenant
- **Role-Based Access Control**: Assign roles to users with specific permissions
- **Permission Management**: Create and manage permissions for different features
- **Feature Management**: Organize permissions by features
- **Authentication**: Secure login, registration, and password reset functionality
- **Profile Management**: Allow users to update their profile information
- **Responsive UI**: Built with Tailwind CSS and Alpine.js

## Architecture

### Multi-Tenancy

This application uses [stancl/tenancy](https://tenancyforlaravel.com/) for multi-tenant architecture:

- **Database Per Tenant**: Each tenant gets its own isolated database
- **Domain-Based Identification**: Tenants are identified by their domain/subdomain
- **Automatic Tenant Context**: Middleware automatically switches to the correct tenant database
- **Central App Management**: Manage tenants from a central application
- **Separate Migrations**: Tenant-specific migrations in `database/migrations/tenant/`

### Laravel Features Utilized

- **Policies**: Implements Laravel's authorization policies for granular access control
- **Resource Controllers**: Follows RESTful resource controller pattern
- **Form Requests**: Uses dedicated request classes for validation
- **Eloquent Relationships**: Leverages many-to-many and belongs-to relationships
- **Middleware**: Custom middleware for authentication, authorization, and tenancy
- **Blade Components**: Modular UI components with Blade

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js and NPM
- MySQL or another Laravel-supported database

## Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/yourusername/lara-ums.git
    cd lara-ums
    ```

2. Install PHP dependencies:

    ```bash
    composer install
    ```

3. Install JavaScript dependencies:

    ```bash
    npm install
    ```

4. Create a copy of the .env file:

    ```bash
    cp .env.example .env
    ```

5. Generate an application key:

    ```bash
    php artisan key:generate
    ```

6. Configure your database in the .env file:

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=lara_ums_central
    DB_USERNAME=root
    DB_PASSWORD=

    # Central domain for tenant management
    TENANCY_CENTRAL_DOMAIN=localhost
    ```

7. Run central database migrations:

    ```bash
    php artisan migrate --seed
    ```

    This creates the central database with tenant management tables.

8. (Optional) Run tenant migrations for existing tenants:

    ```bash
    php artisan tenants:migrate
    ```

9. Build frontend assets:

    ```bash
    npm run build
    ```

10. Start the development server:

    ```bash
    php artisan serve
    ```

11. Visit `http://localhost:8000` in your browser to access the central tenant management app.

## Usage

### Multi-Tenancy Setup

#### Central Application (Tenant Management)

The central application (accessible at your `TENANCY_CENTRAL_DOMAIN`) is used to manage tenants:

1. **Create a Tenant**:
    - Navigate to the Tenants section
    - Click "Create New Tenant"
    - Provide tenant details and a domain/subdomain
    - The system automatically creates a separate database and runs migrations

2. **Manage Tenants**:
    - View all tenants
    - Edit tenant information
    - Delete tenants (removes the tenant database)

#### Tenant Applications

Each tenant has its own application accessible via their assigned domain:

1. **Access Tenant App**: Visit the tenant's domain (e.g., `tenant1.yourdomain.com`)
2. **Separate User Base**: Each tenant has completely isolated users, roles, and permissions
3. **Independent Data**: All tenant data is stored in separate databases

### Default Credentials

After running the seeders, you can log in to the **central app** with:

- Email: superadmin@example.com
- Password: password

**Note**: Each tenant will need to register its own users or you can seed them separately per tenant.

### Managing Users (Within a Tenant)

1. Access the tenant's domain
2. Log in as an administrator
3. Navigate to the Users section
4. From here, you can create, view, edit, or delete users
5. When creating or editing a user, you can assign them a role

### Managing Roles and Permissions (Within a Tenant)

1. Navigate to the Roles section
2. Create a new role or edit an existing one
3. Assign permissions to the role by selecting them from the list
4. Users with this role will inherit all assigned permissions

### Artisan Commands for Multi-Tenancy

```bash
# Run migrations for all tenants
php artisan tenants:migrate

# Run a specific migration for all tenants
php artisan tenants:migrate --path=database/migrations/tenant/2025_03_02_031536_create_roles_table.php

# Rollback tenant migrations
php artisan tenants:rollback

# Seed all tenant databases
php artisan tenants:seed

# Run a command for a specific tenant
php artisan tenants:run <command> --tenant=<tenant_id>
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Credits

- Built with [Laravel](https://laravel.com/)
- UI components with [Tailwind CSS](https://tailwindcss.com/)
- Interactive UI with [Alpine.js](https://alpinejs.dev/)
