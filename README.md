# Lara-UMS (Laravel User Management System)

A comprehensive user management system built with Laravel, featuring role-based access control, permissions, and user administration.

## Features

-   **User Management**: Create, view, edit, and delete user accounts
-   **Role-Based Access Control**: Assign roles to users with specific permissions
-   **Permission Management**: Create and manage permissions for different features
-   **Feature Management**: Organize permissions by features
-   **Authentication**: Secure login, registration, and password reset functionality
-   **Profile Management**: Allow users to update their profile information
-   **Responsive UI**: Built with Tailwind CSS and Alpine.js

## Laravel Features Utilized

-   **Policies**: Implements Laravel's authorization policies for granular access control
-   **Resource Controllers**: Follows RESTful resource controller pattern
-   **Form Requests**: Uses dedicated request classes for validation
-   **Eloquent Relationships**: Leverages many-to-many and belongs-to relationships
-   **Middleware**: Custom middleware for authentication and authorization
-   **Blade Components**: Modular UI components with Blade

## Requirements

-   PHP 8.2 or higher
-   Composer
-   Node.js and NPM
-   MySQL or another Laravel-supported database

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
    DB_DATABASE=lara_ums
    DB_USERNAME=root
    DB_PASSWORD=
    ```

7. Run database migrations and seeders:

    ```bash
    php artisan migrate --seed
    ```

8. Build frontend assets:

    ```bash
    npm run build
    ```

9. Start the development server:

    ```bash
    php artisan serve
    ```

10. Visit `http://localhost:8000` in your browser.

## Usage

### Default Credentials

After running the seeders, you can log in with the following default admin account:

-   Email: admin@example.com
-   Password: password

### Default User Account

A regular user account is also available for testing:

-   Email: user@example.com
-   Password: password

### Managing Users

1. Log in as an administrator
2. Navigate to the Users section
3. From here, you can create, view, edit, or delete users
4. When creating or editing a user, you can assign them a role

### Managing Roles and Permissions

1. Navigate to the Roles section
2. Create a new role or edit an existing one
3. Assign permissions to the role by selecting them from the list
4. Users with this role will inherit all assigned permissions

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Credits

-   Built with [Laravel](https://laravel.com/)
-   UI components with [Tailwind CSS](https://tailwindcss.com/)
-   Interactive UI with [Alpine.js](https://alpinejs.dev/)
